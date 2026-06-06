<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

/**
 * ChatSecurityService
 *
 * Cung cấp nhiều lớp bảo mật cho AI ChatBot:
 *  1. Rate limiting – giới hạn số request mỗi phút/giờ
 *  2. Phát hiện Prompt Injection – nhận biết các kỹ thuật tấn công phổ biến
 *  3. Sanitize input      – làm sạch đầu vào trước khi gửi lên AI
 *  4. Sanitize output     – lọc đầu ra, ngăn lộ thông tin nhạy cảm
 */
class ChatSecurityService
{
    // ── Cấu hình rate limit ────────────────────────────────
    protected int $maxPerMinute = 10;   // Tối đa 10 tin nhắn/phút/session
    protected int $maxPerHour   = 100;  // Tối đa 100 tin nhắn/giờ/session

    // ── Các pattern nguy hiểm (Prompt Injection) ───────────
    // Dùng regex không phân biệt hoa/thường (PCRE /i)
    protected array $injectionPatterns = [
        // Cố gắng ghi đè system prompt
        '/ignore\s+(all\s+)?(previous|above|prior)\s+(instructions?|prompts?|context)/i',
        '/forget\s+(everything|all|your|the)\s+(instructions?|rules?|system|above)/i',
        '/disregard\s+(all\s+)?(previous|prior|above)/i',
        '/new\s+(system\s+)?prompt\s*[:=]/i',
        '/you\s+are\s+now\s+a?\s*(?:different|new|another|evil|jailbreak)/i',
        '/act\s+as\s+(if\s+you\s+are\s+|a\s+)?(?:different|evil|unrestricted|DAN|jailbreak)/i',

        // Jailbreak phổ biến
        '/\bDAN\b.*\bjailbreak\b/i',
        '/do\s+anything\s+now/i',
        '/jailbreak(?:ed|ing)?\s+mode/i',
        '/developer\s+mode\s+(enabled|on|activated)/i',
        '/\[INST\]|\[\/INST\]|<\|system\|>|<\|user\|>|<\|assistant\|>/i',  // LLM delimiters

        // Cố lấy system prompt / thông tin nội bộ
        '/(?:print|show|reveal|tell|give|leak|expose|output)\s+(?:me\s+)?(?:your\s+)?(?:system\s+prompt|instructions?|api\s*key|secret|password|config)/i',
        '/what\s+(is|are)\s+your\s+(system\s+prompt|instructions?|rules?|config)/i',
        '/repeat\s+(everything|all)\s+(above|before|prior)/i',

        // Injection thông qua dữ liệu (data exfil)
        '/\$\{.*\}|\{\{.*\}\}|<%.*%>/i',   // Template injection
        '/<script\b[^>]*>.*?<\/script>/is',  // Script injection
        '/on(?:load|click|error|mouseover)\s*=/i', // Event handler injection

        // Tiếng Việt – cố thay đổi vai trò của bot
        '/bỏ\s+qua\s+(tất\s+cả\s+)?(hướng\s+dẫn|lệnh|quy\s+tắc)/i',
        '/quên\s+(đi\s+)?(tất\s+cả|mọi)\s+(hướng\s+dẫn|quy\s+tắc|lệnh)/i',
        '/bây\s+giờ\s+bạn\s+là\s+(một\s+)?(?:AI\s+)?khác/i',
        '/đóng\s+vai\s+(?:là\s+)?(?:AI|bot)\s+(?:không\s+có\s+giới\s+hạn|tự\s+do)/i',
        '/hãy\s+(?:tiết\s+lộ|cho\s+biết|in\s+ra)\s+(?:system\s+prompt|api\s*key|mật\s+khẩu)/i',
    ];

    // ── Thông tin nhạy cảm cần lọc khỏi output ────────────
    protected array $sensitiveOutputPatterns = [
        '/AIza[0-9A-Za-z\-_]{35}/i',            // Google API key
        '/sk-[a-zA-Z0-9]{48}/i',                 // OpenAI key
        '/ghp_[a-zA-Z0-9]{36}/i',               // GitHub token
        '/AKIA[0-9A-Z]{16}/i',                   // AWS key
        '/base64:[a-zA-Z0-9+\/=]{40,}/i',        // Laravel APP_KEY
        // Đường dẫn hệ thống Windows/Linux
        '/[A-Z]:\\\\(?:Users|Windows|Program Files)[\\\\][^\s]+/i',
        '/\/(?:etc|var|home|root|usr)\/[^\s]+/i',
        // Password/secret patterns
        '/(?:password|passwd|secret|token)\s*[:=]\s*[^\s\n]{8,}/i',
    ];

    // ── Từ khóa cần log (đáng ngờ nhưng chưa bị block) ───
    protected array $suspiciousKeywords = [
        'system prompt', 'api key', 'database', 'sql', 'injection',
        'bypass', 'override', 'ignore instructions', 'unlimited',
        'no restrictions', 'without limits', 'unrestricted',
    ];

    /**
     * Kiểm tra rate limit dựa trên session ID.
     * Trả về ['allowed' => bool, 'message' => string]
     */
    public function checkRateLimit(string $sessionId): array
    {
        $keyMinute = "chatbot_rl_min_{$sessionId}";
        $keyHour   = "chatbot_rl_hr_{$sessionId}";

        if (RateLimiter::tooManyAttempts($keyMinute, $this->maxPerMinute)) {
            $seconds = RateLimiter::availableIn($keyMinute);
            Log::warning("ChatBot rate limit (per minute) hit", ['session' => substr($sessionId, 0, 8)]);
            return [
                'allowed' => false,
                'message' => "⏳ Bạn đang gửi quá nhanh! Vui lòng chờ {$seconds} giây rồi thử lại.",
            ];
        }

        if (RateLimiter::tooManyAttempts($keyHour, $this->maxPerHour)) {
            Log::warning("ChatBot rate limit (per hour) hit", ['session' => substr($sessionId, 0, 8)]);
            return [
                'allowed' => false,
                'message' => '⏳ Bạn đã dùng EduBot quá nhiều trong giờ này. Vui lòng thử lại sau.',
            ];
        }

        RateLimiter::hit($keyMinute, 60);   // decay 60 giây
        RateLimiter::hit($keyHour, 3600);   // decay 1 giờ

        return ['allowed' => true, 'message' => ''];
    }

    /**
     * Phát hiện Prompt Injection trong tin nhắn.
     * Trả về ['safe' => bool, 'reason' => string]
     */
    public function detectInjection(string $input): array
    {
        foreach ($this->injectionPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                Log::warning('ChatBot prompt injection attempt detected', [
                    'pattern' => $pattern,
                    'input'   => substr($input, 0, 100),
                ]);
                return [
                    'safe'   => false,
                    'reason' => 'injection_detected',
                ];
            }
        }

        // Kiểm tra từ khóa đáng ngờ (chỉ log, không block)
        $lowerInput = strtolower($input);
        foreach ($this->suspiciousKeywords as $keyword) {
            if (str_contains($lowerInput, $keyword)) {
                Log::info('ChatBot suspicious keyword', [
                    'keyword' => $keyword,
                    'input'   => substr($input, 0, 100),
                ]);
                break; // Chỉ log 1 lần
            }
        }

        return ['safe' => true, 'reason' => ''];
    }

    /**
     * Làm sạch đầu vào người dùng:
     *  - Xóa null bytes và ký tự điều khiển nguy hiểm
     *  - Cắt bớt nếu quá dài
     *  - Giữ nguyên nội dung hợp lệ (không escape – để Gemini nhận đúng)
     */
    public function sanitizeInput(string $input): string
    {
        // Xóa null bytes
        $input = str_replace("\0", '', $input);

        // Xóa ký tự điều khiển (trừ tab \t và newline \n \r)
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);

        // Xóa Unicode invisible chars / zero-width chars thường dùng để bypass filter
        $input = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}\x{00AD}]/u', '', $input);

        // Xóa các LLM delimiter bị nhúng vào
        $input = preg_replace('/<\|(?:system|user|assistant|endoftext)\|>/i', '', $input);
        $input = preg_replace('/\[INST\]|\[\/INST\]|\[SYS\]|\[\/SYS\]/i', '', $input);

        // Trim và giới hạn độ dài
        $input = trim($input);
        if (mb_strlen($input) > 2000) {
            $input = mb_substr($input, 0, 2000);
        }

        return $input;
    }

    /**
     * Lọc đầu ra từ AI:
     *  - Xóa thông tin nhạy cảm (API key, path, password...)
     *  - Giới hạn độ dài output
     */
    public function sanitizeOutput(string $output): string
    {
        foreach ($this->sensitiveOutputPatterns as $pattern) {
            $output = preg_replace($pattern, '[ĐÃ XÓA - THÔNG TIN NHẠY CẢM]', $output);
        }

        // Giới hạn output tối đa 4000 ký tự
        if (mb_strlen($output) > 4000) {
            $output = mb_substr($output, 0, 4000) . '...';
        }

        return $output;
    }

    /**
     * Bọc tin nhắn của user trong delimiter rõ ràng
     * để AI không nhầm nội dung user với instruction.
     */
    public function wrapUserMessage(string $message): string
    {
        return "<<<USER_MESSAGE_START>>>\n{$message}\n<<<USER_MESSAGE_END>>>";
    }
}

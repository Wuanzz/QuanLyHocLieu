<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
use App\Models\TaiLieu;
use App\Services\GeminiService;
use App\Services\ChatSecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ChatBotController extends Controller
{
    protected GeminiService      $gemini;
    protected ChatSecurityService $security;

    // Số cặp hội thoại (user+model) tối đa lưu trong session
    protected int $maxHistory = 10;

    public function __construct(GeminiService $gemini, ChatSecurityService $security)
    {
        $this->gemini   = $gemini;
        $this->security = $security;
    }

    /**
     * Nhận tin nhắn từ user, áp dụng bảo mật, gọi Gemini, lưu lịch sử.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $sessionId = session()->getId();

        // ── 1. Rate Limiting ─────────────────────────────────────
        $rateCheck = $this->security->checkRateLimit($sessionId);
        if (!$rateCheck['allowed']) {
            return response()->json([
                'success' => false,
                'reply'   => $rateCheck['message'],
            ], 429);
        }

        // ── 2. Sanitize Input ────────────────────────────────────
        $userMessage = $this->security->sanitizeInput(
            $request->input('message')
        );

        if (empty($userMessage)) {
            return response()->json([
                'success' => false,
                'reply'   => '⚠️ Tin nhắn không hợp lệ. Vui lòng thử lại.',
            ], 422);
        }

        // ── 3. Phát hiện Prompt Injection ────────────────────────
        $injectionCheck = $this->security->detectInjection($userMessage);
        if (!$injectionCheck['safe']) {
            return response()->json([
                'success' => false,
                'reply'   => '🚫 EduBot không thể xử lý yêu cầu này. Mình chỉ hỗ trợ các câu hỏi về học liệu và hệ thống EduShare nhé!',
            ], 422);
        }

        // ── 4. Lấy lịch sử & DB context ──────────────────────────
        $history   = session('chatbot_history', []);
        $dbContext = Cache::remember('chatbot_db_context', 600, function () {
            return $this->buildDatabaseContext();
        });

        // ── 5. Gọi Gemini API ─────────────────────────────────────
        $botReply = $this->gemini->chat($userMessage, $history, $dbContext);

        // ── 6. Sanitize Output ────────────────────────────────────
        $botReply = $this->security->sanitizeOutput($botReply);

        // ── 7. Cập nhật lịch sử session ──────────────────────────
        // Lưu message gốc (chưa wrapped) vào history để lượt tiếp theo wrap lại
        $history[] = ['role' => 'user',  'parts' => [['text' => "<<<USER_MESSAGE_START>>>\n{$userMessage}\n<<<USER_MESSAGE_END>>>"]]];
        $history[] = ['role' => 'model', 'parts' => [['text' => $botReply]]];

        $maxPairs = $this->maxHistory * 2;
        if (count($history) > $maxPairs) {
            $history = array_slice($history, -$maxPairs);
        }

        session(['chatbot_history' => $history]);

        return response()->json([
            'success' => true,
            'reply'   => $botReply,
        ]);
    }

    /**
     * Xóa lịch sử hội thoại và cache DB context, bắt đầu lại.
     */
    public function resetSession(Request $request)
    {
        session()->forget('chatbot_history');
        Cache::forget('chatbot_db_context');

        return response()->json([
            'success' => true,
            'message' => '🔄 Đã bắt đầu cuộc trò chuyện mới!',
        ]);
    }

    /**
     * Truy vấn DB và xây dựng chuỗi context cho AI.
     * Cấu trúc: Khoa > Ngành > Học phần (+ số tài liệu đã duyệt).
     */
    private function buildDatabaseContext(): string
    {
        $lines = [];
        $lines[] = "=== DỮ LIỆU THỰC TẾ TỪ HỆ THỐNG EDUSHARE ===";
        $lines[] = "(Ngày cập nhật: " . now()->format('d/m/Y H:i') . ")";
        $lines[] = "";

        // Tổng quan
        $totalTaiLieu = TaiLieu::where('TrangThaiDuyet', 'DaDuyet')->count();
        $lines[] = "📊 TỔNG QUAN: Có $totalTaiLieu tài liệu đã được phê duyệt trên hệ thống.";
        $lines[] = "";

        // Duyệt qua từng Khoa
        $khoas = Khoa::with(['Nganhs' => function ($q) {
            $q->with(['HocPhans' => function ($q2) {
                $q2->withCount(['TaiLieus' => function ($q3) {
                    $q3->where('TrangThaiDuyet', 'DaDuyet');
                }]);
            }]);
        }])->get();

        foreach ($khoas as $khoa) {
            $lines[] = "🏫 KHOA: {$khoa->TenKhoa}";

            foreach ($khoa->Nganhs as $nganh) {
                $lines[] = "  📚 Ngành: {$nganh->TenNganh}";

                foreach ($nganh->HocPhans as $hp) {
                    $soTL = $hp->tai_lieus_count ?? 0;
                    $moTa = $hp->MoTa ? " – {$hp->MoTa}" : '';
                    $lines[] = "    📖 Học phần: {$hp->TenHocPhan}{$moTa} [{$soTL} tài liệu]";
                }
            }

            $lines[] = "";
        }

        // Top 10 tài liệu được tải nhiều nhất
        $topTaiLieu = TaiLieu::with(['HocPhan.Nganh'])
            ->where('TrangThaiDuyet', 'DaDuyet')
            ->orderByDesc('LuotTai')
            ->limit(10)
            ->get();

        if ($topTaiLieu->isNotEmpty()) {
            $lines[] = "🔥 TOP 10 TÀI LIỆU ĐƯỢC TẢI NHIỀU NHẤT:";
            foreach ($topTaiLieu as $tl) {
                $monHoc  = optional(optional($tl->HocPhan)->Nganh)->TenNganh ?? 'Không rõ';
                $hocPhan = optional($tl->HocPhan)->TenHocPhan ?? 'Không rõ';
                $lines[] = "  - \"{$tl->TenTaiLieu}\" | Môn: {$hocPhan} | Ngành: {$monHoc} | Lượt tải: {$tl->LuotTai}";
            }
        }

        $lines[] = "";
        $lines[] = "=== HẾT DỮ LIỆU HỆ THỐNG ===";

        return implode("\n", $lines);
    }
}

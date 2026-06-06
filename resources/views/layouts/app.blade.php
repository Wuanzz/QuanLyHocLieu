<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Trang Chủ') - EduShare</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/zephyr/bootstrap.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/site.css') }}" />
</head>

<body class="d-flex flex-column min-vh-100">
    <header>
        <nav class="navbar navbar-expand-sm navbar-toggleable-sm navbar-light bg-white border-bottom box-shadow mb-3">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold text-primary fs-4" href="{{ route('home') }}">
                    🎓 EduShare
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".navbar-collapse" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-collapse collapse d-sm-inline-flex justify-content-between">
                    <ul class="navbar-nav flex-grow-1 align-items-center">

                        <li class="nav-item">
                            <a class="nav-link text-dark fw-bold me-2" href="{{ route('home') }}">Trang Chủ</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-info fw-bold" href="{{ route('tailieu.index') }}">Kho Tài Liệu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-warning fw-bold" href="{{ route('review.index') }}">Cộng đồng Review</a>
                        </li>
                        
                        @if (Auth::check() && in_array(Auth::user()->VaiTro, ['Admin', 'GiangVien']))
                            <a href="{{ Auth::user()->VaiTro === 'Admin' ? route('admin.dashboard') : route('giangvien.kiemduyet.index') }}" class="btn btn-outline-primary fw-bold ms-2 rounded-pill shadow-sm">
                                <i class="fa-solid fa-shield-halved me-2"></i>Sang Trang Quản Trị
                            </a>
                        @endif
                    </ul>

                    <ul class="navbar-nav align-items-center">
                        @if (Auth::check())
                            <li class="nav-item d-flex align-items-center">
                                <a class="nav-link text-primary" href="{{ route('hoso.index') }}">
                                    Xin chào, <strong class="text-decoration-underline">{{ Auth::user()->HoTen }}</strong>!
                                </a>
                            </li>
                            <li class="nav-item">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                <a href="#" class="btn btn-outline-danger ms-2 btn-sm fw-bold" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="btn btn-primary fw-bold" href="{{ route('login') }}">Đăng nhập</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container flex-grow-1">
        <main role="main" class="pb-3">
            @yield('content')
        </main>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <footer class="custom-footer mt-auto py-5 position-static">
        <hr class="mb-5" style="border-top: 1px solid #dee2e6; opacity: 0.5;">

        <div class="container">
            <div class="row">
                <div class="col-md-4 pe-md-4 footer-border-right mb-4 mb-md-0">
                    <h4 class="fw-bold mb-4 d-flex align-items-center">
                        <span class="me-2" style="color: #fbbc04; font-size: 1.8rem;">🎓</span> EduShare
                    </h4>
                    <p class="mb-2 fw-semibold">Cam kết tài liệu chuẩn xác 100%</p>
                    <p class="mb-4 small">Trao tri thức - Nhận niềm tin</p>

                    <div class="d-flex gap-3">
                        <a href="#" class="social-btn text-decoration-none"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-btn text-decoration-none"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-btn text-decoration-none"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="col-md-4 px-md-4 footer-border-right mb-4 mb-md-0">
                    <h5 class="fw-bold mb-4">Liên Hệ Hỗ Trợ</h5>
                    <div class="d-flex mb-3 align-items-start">
                        <span class="footer-icon-yellow me-2">📍</span>
                        <span class="small pt-1">280 An Dương Vương, Q.5, TP. Hồ Chí Minh</span>
                    </div>
                    <div class="d-flex mb-3 align-items-center">
                        <span class="footer-icon-yellow me-2">📞</span>
                        <span class="small">0909 123 456 ( 8:00 - 22:00 )</span>
                    </div>
                    <div class="d-flex mb-3 align-items-center">
                        <span class="footer-icon-yellow me-2">✉️</span>
                        <span class="small">edushare.support@gmail.com</span>
                    </div>
                </div>

                <div class="col-md-4 ps-md-4">
                    <h5 class="fw-bold mb-4">Chứng Nhận & Bản Quyền</h5>
                    <p class="small mb-3">Tuân thủ bản quyền nội dung số</p>
                    <div class="d-flex gap-2 mb-4">
                        <div class="payment-icon">💳</div>
                        <div class="payment-icon">🅿️</div>
                        <div class="payment-icon">🏦</div>
                    </div>
                    <hr style="border-color: #2d3342;" class="mb-3" />
                    <p class="mb-1 small text-darker">&copy; 2026 EduShare. All rights reserved</p>
                    <p class="mb-0 small">Designed by <strong>Nhóm 4</strong></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Khung hiển thị thông báo Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-4" style="z-index: 1055">
        <div id="liveToast" class="toast align-items-center text-white bg-primary border-0 shadow-lg rounded-4" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex p-1">
                <div class="toast-body fw-bold fs-6" id="toastMessage">
                </div>
                <button type="button" class="btn-close btn-close-white me-3 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="{{ asset('js/site.js') }}"></script>

    <!-- Thư viện Pusher JS thay thế cho SignalR -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Khởi tạo kết nối Pusher từ các biến môi trường
            var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
                cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
                encrypted: true
            });

            // Lắng nghe vào kênh phát sóng chung của hệ thống (phải khớp tên channel trong Event)
            var channel = pusher.subscribe('thong-bao-chung');

            // Bắt sự kiện có tên 'ThongBaoHeThong' được bắn ra từ Backend
            channel.bind('App\\Events\\ThongBaoHeThong', function(data) {
                // Đổ dữ liệu text vào Toast
                document.getElementById("toastMessage").innerText = "🔔 " + data.message;

                // Kích hoạt Toast Bootstrap
                const toastLiveExample = document.getElementById('liveToast');
                const toast = new bootstrap.Toast(toastLiveExample, { delay: 6000 });
                toast.show();
            });
        });
    </script>

    @stack('scripts')

    {{-- ============================================================
         AI CHATBOT WIDGET – EduBot
         ============================================================ --}}

    {{-- Floating Toggle Button --}}
    <button id="chatbot-toggle" title="Trò chuyện với EduBot" aria-label="Mở trợ lý EduBot">
        <span class="cb-icon-open">💬</span>
        <span class="cb-icon-close">✕</span>
        <span id="chatbot-badge"></span>
    </button>

    {{-- Chat Window --}}
    <div id="chatbot-window" role="dialog" aria-label="EduBot – Trợ lý AI" aria-hidden="true">

        {{-- Header --}}
        <div id="chatbot-header">
            <div class="cb-avatar">🤖</div>
            <div class="cb-header-info">
                <div class="cb-header-name">EduBot</div>
                <div class="cb-header-status">
                    <span class="cb-status-dot"></span>
                    Trực tuyến · Sẵn sàng hỗ trợ
                </div>
            </div>
            <button class="cb-btn-reset" id="chatbot-reset" title="Bắt đầu cuộc trò chuyện mới">
                🔄 Mới
            </button>
        </div>

        {{-- Messages --}}
        <div id="chatbot-messages">
            <div class="cb-bubble bot">
                👋 Xin chào! Mình là <strong>EduBot</strong> – trợ lý AI của EduShare.<br><br>
                Mình có thể giúp bạn:<br>
                📚 Tìm tài liệu học tập<br>
                💡 Giải đáp thắc mắc môn học<br>
                🛠️ Hướng dẫn sử dụng EduShare<br><br>
                Bạn cần hỗ trợ gì không?
            </div>
            {{-- Typing indicator --}}
            <div id="cb-typing">
                <div class="cb-dot"></div>
                <div class="cb-dot"></div>
                <div class="cb-dot"></div>
            </div>
        </div>

        {{-- Input Footer --}}
        <div id="chatbot-footer">
            <form id="chatbot-form" autocomplete="off">
                @csrf
                <textarea
                    id="chatbot-input"
                    rows="1"
                    placeholder="Nhập câu hỏi của bạn..."
                    aria-label="Nhập tin nhắn"
                    maxlength="2000"
                ></textarea>
                <button type="submit" id="chatbot-send" title="Gửi" aria-label="Gửi tin nhắn">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </form>
            <p class="cb-footer-hint">Powered by Google Gemini · EduShare AI</p>
        </div>
    </div>

    <script>
    (function () {
        'use strict';

        const toggle   = document.getElementById('chatbot-toggle');
        const window_  = document.getElementById('chatbot-window');
        const messages = document.getElementById('chatbot-messages');
        const form     = document.getElementById('chatbot-form');
        const input    = document.getElementById('chatbot-input');
        const sendBtn  = document.getElementById('chatbot-send');
        const typing   = document.getElementById('cb-typing');
        const resetBtn = document.getElementById('chatbot-reset');
        const badge    = document.getElementById('chatbot-badge');

        let isOpen = false;
        let unreadCount = 0;

        // ── Toggle open/close ──
        toggle.addEventListener('click', function () {
            isOpen = !isOpen;
            toggle.classList.toggle('is-open', isOpen);
            window_.classList.toggle('is-visible', isOpen);
            window_.setAttribute('aria-hidden', String(!isOpen));

            if (isOpen) {
                unreadCount = 0;
                badge.style.display = 'none';
                badge.textContent = '';
                setTimeout(() => input.focus(), 350);
            }
        });

        // ── Auto-resize textarea ──
        input.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // ── Enter to send (Shift+Enter = newline) ──
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }
        });

        // ── Append message bubble ──
        function appendBubble(text, role) {
            // Đặt typing indicator luôn ở cuối
            const bubble = document.createElement('div');
            bubble.className = 'cb-bubble ' + role;

            // Chuyển đổi markdown cơ bản sang HTML
            const html = text
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/`(.*?)`/g, '<code>$1</code>')
                .replace(/\n/g, '<br>');

            bubble.innerHTML = html;
            messages.insertBefore(bubble, typing);
            scrollBottom();

            // Unread badge nếu cửa sổ đóng
            if (!isOpen && role === 'bot') {
                unreadCount++;
                badge.textContent = unreadCount;
                badge.style.display = 'flex';
            }
        }

        function scrollBottom() {
            messages.scrollTop = messages.scrollHeight;
        }

        function setLoading(loading) {
            sendBtn.disabled = loading;
            input.disabled   = loading;
            typing.classList.toggle('show', loading);
            if (loading) scrollBottom();
        }

        // ── Send message ──
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const text = input.value.trim();
            if (!text) return;

            appendBubble(text, 'user');
            input.value = '';
            input.style.height = 'auto';
            setLoading(true);

            try {
                const csrfToken = document.querySelector('#chatbot-form input[name="_token"]').value;
                const response  = await fetch('{{ route("chatbot.send") }}', {
                    method:  'POST',
                    headers: {
                        'Content-Type':     'application/json',
                        'X-CSRF-TOKEN':     csrfToken,
                        'Accept':           'application/json',
                    },
                    body: JSON.stringify({ message: text }),
                });

                const data = await response.json();

                if (data.success) {
                    appendBubble(data.reply, 'bot');
                } else {
                    appendBubble('❌ Xin lỗi, đã xảy ra lỗi. Vui lòng thử lại.', 'bot');
                }
            } catch (err) {
                appendBubble('❌ Không thể kết nối EduBot. Kiểm tra kết nối mạng nhé!', 'bot');
                console.error('ChatBot error:', err);
            } finally {
                setLoading(false);
                input.focus();
            }
        });

        // ── Reset session ──
        resetBtn.addEventListener('click', async function () {
            try {
                const csrfToken = document.querySelector('#chatbot-form input[name="_token"]').value;
                await fetch('{{ route("chatbot.reset") }}', {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept':       'application/json',
                    },
                });

                // Xoá tất cả bubble trừ typing indicator
                const bubbles = messages.querySelectorAll('.cb-bubble');
                bubbles.forEach(b => b.remove());

                appendBubble('🔄 Đã bắt đầu cuộc trò chuyện mới! Mình có thể giúp gì cho bạn?', 'bot');
            } catch (err) {
                console.error('Reset error:', err);
            }
        });
    })();
    </script>
</body>
</html>
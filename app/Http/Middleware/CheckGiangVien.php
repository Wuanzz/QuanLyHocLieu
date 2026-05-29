<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckGiangVien
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->VaiTro === 'GiangVien') {
            return $next($request);
        }

        // Nếu không phải Giảng viên, lập tức đẩy về trang chủ hoặc trang đăng nhập
        return redirect('/')->with('error', 'Khu vực này chỉ dành cho Giảng viên kiểm duyệt.');
    }
}

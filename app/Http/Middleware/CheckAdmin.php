<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->VaiTro === 'Admin') {
            return $next($request);
        }
        // Nếu không phải Admin, lập tức đẩy về trang chủ hoặc trang đăng nhập
        return redirect('/')->with('error', 'Bạn không có đặc quyền truy cập khu vực Quản trị.');
    }
}

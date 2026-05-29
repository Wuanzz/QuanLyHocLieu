@if ($errors->any())
    <div style="color: red;">
        {{ $errors->first() }}
    </div>
@endif

<form action="{{ route('login') }}" method="POST">
    @csrf
    <div>
        <label>Email:</label>
        <input type="email" name="Email" required>
    </div>
    <br>
    <div>
        <label>Mật khẩu:</label>
        <input type="password" name="MatKhau" required>
    </div>
    <br>
    <button type="submit">Đăng nhập</button>
</form>
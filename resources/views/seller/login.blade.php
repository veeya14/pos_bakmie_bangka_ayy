<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bakmie Bangka AY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="login-container d-flex align-items-center justify-content-center vh-100 px-5">

    {{-- Logo --}}
    <div class="me-5 p-5">
        <img src="{{ asset('image/logo.jpg') }}" class="rounded" alt="logo usaha">
    </div>

    {{-- Form Login --}}
    <div class="col-12 col-md-6 border-form form-login p-4">
        <h3 class="mb-4 fw-bold">Login</h3>

        @if($errors->has('login'))
            <div class="alert alert-danger">{{ $errors->first('login') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('seller.login.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control text-field" placeholder="Masukkan email anda" required>
            </div>
            <div class="mb-1">
                <label>Password</label>
                <input type="password" name="password" class="form-control text-field" placeholder="Masukkan password anda" required>
            </div>

            {{-- Link Forgot Password (dummy, tidak memanggil route) --}}
            <div class="d-flex justify-content-end">
                <a href="#" class="text-decoration-none text-muted small">
                    Forgot password?
                </a>
            </div>
            <br>

            <button type="submit" class="btn button-style w-100">Login</button>
        </form>

        <p class="mt-3 text-center small">
            Don't have an account? <a href="{{ route('seller.register') }}" class="text-decoration-none">Register here</a>
        </p>
    </div>
</div>
</body>
</html>

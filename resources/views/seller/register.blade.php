<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Seller</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="login-container d-flex align-items-center justify-content-center vh-100 px-5">

    {{-- Logo --}}
    <div class="me-5 p-5">
        <img src="{{ asset('image/logo.jpg') }}" class="rounded" alt="Bakmie Bangka AY logo">
    </div>

    {{-- Register Form --}}
    <div class="col-12 col-md-6 border-form form-login p-4">
        <h3 class="mb-4 fw-bold">Register</h3>

        {{-- Notifikasi error --}}
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form id="registerForm" action="{{ route('seller.register.submit') }}" method="POST" novalidate>
            @csrf
            <div class="mb-3">
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control text-field @error('email') is-invalid @enderror" 
                       id="Email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="Password" class="form-label">Password</label>
                <input type="password" class="form-control text-field @error('password') is-invalid @enderror" 
                       id="Password" name="password" placeholder="Enter at least 6 characters" minlength="6" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="ConfirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control text-field" 
                       id="ConfirmPassword" name="password_confirmation" placeholder="Re-enter password" required>
            </div>

            <button type="submit" class="btn button-style mt-2 w-100">Sign Up</button>
        </form>

        <p class="mt-3 text-center small">
            Already have an account? <a href="{{ route('seller.login') }}" class="text-decoration-none">Login here</a>
        </p>
    </div>

</div>

<script>
    const form = document.getElementById('registerForm');
    form.addEventListener('submit', function(event) {
        const password = document.getElementById('Password');
        const confirm = document.getElementById('ConfirmPassword');

        if(password.value !== confirm.value) {
            confirm.classList.add('is-invalid');
            confirm.nextElementSibling?.remove(); // hapus invalid-feedback lama
            const div = document.createElement('div');
            div.className = 'invalid-feedback';
            div.innerText = 'Passwords do not match.';
            confirm.parentNode.appendChild(div);
            event.preventDefault();
        }
    });
</script>

</body>
</html>

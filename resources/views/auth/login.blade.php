@extends('layouts.guest')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: url('/images/bg.jpg') no-repeat center center / cover;">
    <div class="p-4 rounded-4 shadow-sm" style="max-width: 360px; width: 100%; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(6px);">
        <h4 class="text-center mb-3">Login</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required autofocus placeholder="Enter email">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required placeholder="Enter password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember + Forgot -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label small" for="remember">Remember me</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small">Forgot?</a>
                @endif
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>

        <!-- Signup -->
        <div class="text-center mt-3">
            <small>Don't have an account?</small>
            <a href="#" class="ms-1">Sign Up</a>
        </div>
    </div>
</div>
@endsection

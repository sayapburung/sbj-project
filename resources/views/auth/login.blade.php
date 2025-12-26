@extends('layouts.app')

@section('content')
<div class="login-page">
    <div class="login-container">
        <!-- Left Panel -->
        <div class="left-panel">
            <div class="brand-content">
                <div class="logo-circle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h1>WorkFlow</h1>
                <p>Management System</p>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <div class="form-box">
                <div class="form-header">
                    <h2>Welcome Back</h2>
                    <p>Please enter your credentials to sign in</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="input-box">
                        <label for="email">Email Address</label>
                        <input 
                            type="email" 
                            id="email"
                            name="email"
                            class="input-field @error('email') input-error @enderror"
                            placeholder="your@email.com"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            autofocus
                        >
                        @error('email')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-box">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            id="password"
                            name="password"
                            class="input-field @error('password') input-error @enderror"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        >
                        @error('password')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <label class="remember-check">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link">Forgot Password?</a>
                        @endif
                    </div>

                    <button type="submit" class="submit-btn">
                        Sign In
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>

                    @if (Route::has('register'))
                        <div class="signup-text">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="link">Sign Up</a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="footer-text">
                © {{ date('Y') }} SBJ Production. All rights reserved.
            </div>
        </div>
    </div>
</div>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.login-page {
    min-height: 100vh;
    width: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.login-page::before {
    content: '';
    position: absolute;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    top: -250px;
    right: -250px;
    border-radius: 50%;
    animation: move 20s infinite alternate;
}

.login-page::after {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
    bottom: -200px;
    left: -200px;
    border-radius: 50%;
    animation: move 15s infinite alternate-reverse;
}

@keyframes move {
    to {
        transform: translate(100px, 100px);
    }
}

.login-container {
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1fr 1fr;
    position: relative;
    z-index: 1;
}

/* Left Panel */
.left-panel {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px;
    position: relative;
    overflow: hidden;
}

.brand-content {
    text-align: center;
    color: white;
    margin-bottom: 80px;
}

.logo-circle {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.logo-circle svg {
    width: 40px;
    height: 40px;
    color: white;
}

.brand-content h1 {
    font-size: 42px;
    font-weight: 700;
    margin-bottom: 8px;
    letter-spacing: -1px;
}

.brand-content p {
    font-size: 18px;
    opacity: 0.9;
}

.illustration {
    position: relative;
    width: 100%;
    max-width: 400px;
    height: 300px;
}

.float-card {
    position: absolute;
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 16px;
    animation: floating 3s ease-in-out infinite;
}

.float-card.card-1 {
    top: 0;
    left: 0;
    animation-delay: 0s;
}

.float-card.card-2 {
    top: 50%;
    right: 0;
    transform: translateY(-50%);
    animation-delay: 1s;
}

.float-card.card-3 {
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    animation-delay: 2s;
}

@keyframes floating {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}

.card-icon {
    font-size: 32px;
}

.card-text {
    font-size: 16px;
    font-weight: 600;
    color: #2d3748;
}

/* Right Panel */
.right-panel {
    background: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px;
}

.form-box {
    width: 100%;
    max-width: 440px;
}

.form-header {
    margin-bottom: 40px;
}

.form-header h2 {
    font-size: 32px;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 8px;
}

.form-header p {
    font-size: 16px;
    color: #718096;
}

.input-box {
    margin-bottom: 24px;
}

.input-box label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}

.input-field {
    width: 100%;
    padding: 14px 18px;
    font-size: 15px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    background: #f7fafc;
    transition: all 0.3s ease;
    outline: none;
}

.input-field:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.input-field::placeholder {
    color: #a0aec0;
}

.input-field.input-error {
    border-color: #fc8181;
}

.error-msg {
    display: block;
    font-size: 13px;
    color: #fc8181;
    margin-top: 6px;
}

.form-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
}

.remember-check {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    user-select: none;
}

.remember-check input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #667eea;
}

.remember-check span {
    font-size: 14px;
    color: #4a5568;
}

.link {
    font-size: 14px;
    font-weight: 600;
    color: #667eea;
    text-decoration: none;
    transition: color 0.2s ease;
}

.link:hover {
    color: #764ba2;
}

.submit-btn {
    width: 100%;
    padding: 16px;
    font-size: 16px;
    font-weight: 600;
    color: white;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.submit-btn svg {
    width: 20px;
    height: 20px;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.submit-btn:active {
    transform: translateY(0);
}

.signup-text {
    text-align: center;
    font-size: 14px;
    color: #718096;
    margin-top: 24px;
}

.footer-text {
    margin-top: 40px;
    font-size: 13px;
    color: #a0aec0;
    text-align: center;
}

/* Responsive */
@media (max-width: 1024px) {
    .left-panel {
        padding: 40px;
    }

    .right-panel {
        padding: 40px;
    }

    .illustration {
        max-width: 300px;
        height: 250px;
    }

    .float-card {
        padding: 20px;
    }
}

@media (max-width: 768px) {
    .login-container {
        grid-template-columns: 1fr;
    }

    .left-panel {
        display: none;
    }

    .right-panel {
        padding: 40px 30px;
    }

    .form-box {
        max-width: 100%;
    }
}

@media (max-width: 480px) {
    .right-panel {
        padding: 32px 24px;
    }

    .form-header h2 {
        font-size: 28px;
    }

    .form-header p {
        font-size: 15px;
    }

    .input-field {
        font-size: 16px;
        padding: 12px 16px;
    }

    .form-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .submit-btn {
        padding: 14px;
        font-size: 15px;
    }
}
</style>
@endsection
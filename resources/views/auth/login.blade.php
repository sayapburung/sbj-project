<link rel="icon" href="{{ asset('images/page1.png') }}" type="image/png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<div class="login-page">
    <div class="login-box">
        <!-- Logo -->
        <div class="logo-section">
            <img src="{{ asset('images/logo.png') }}" width="120" height="60">
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="input-group">
                <input 
                    type="email" 
                    name="email"
                    class="input-field @error('email') error @enderror"
                    placeholder="Email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    autofocus
                >
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-group">
                <input 
                    type="password" 
                    name="password"
                    class="input-field @error('password') error @enderror"
                    placeholder="Password"
                    required
                    autocomplete="current-password"
                >
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-row">
                        <label class="remember-check">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Remember me</span>
                        </label>
                        <!-- @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link">Forgot Password?</a>
                        @endif -->
                    </div>
                    
            <button type="submit" class="login-btn">Login</button>
            <div class="footer-text">
                © {{ date('Y') }} SBJ Production. All rights reserved.
            </div>
        </form>
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Sora', sans-serif;
}

    .footer-text {
    margin-top: 40px;
    font-size: 13px;
    color: #a0aec0;
    text-align: center;
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

.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    padding: 20px;
}

.login-box {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 24px;
    padding: 50px 45px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

/* Logo Section */
.logo-section {
    text-align: center;
    margin-bottom: 48px;
}

.logo-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.35);
}

.logo-icon svg {
    width: 40px;
    height: 40px;
    color: white;
}

.logo-section h1 {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a2e;
    letter-spacing: -0.5px;
}

/* Form Styles */
.input-group {
    margin-bottom: 20px;
}

.input-field {
    width: 100%;
    padding: 16px 20px;
    font-size: 15px;
    font-weight: 500;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: #f9fafb;
    color: #1f2937;
    transition: all 0.3s ease;
    outline: none;
    font-family: 'Sora', sans-serif;
}

.input-field::placeholder {
    color: #9ca3af;
}

.input-field:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.input-field.error {
    border-color: #ef4444;
    background: #fef2f2;
}

.error-text {
    display: block;
    font-size: 13px;
    color: #ef4444;
    margin-top: 6px;
    font-weight: 500;
}

/* Login Button */
.login-btn {
    width: 100%;
    padding: 16px;
    font-size: 16px;
    font-weight: 600;
    color: white;
    background: linear-gradient(135deg, #21306f 0%, #18016e 100%);
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 12px;
    font-family: 'Sora', sans-serif;
    box-shadow: 0 4px 14px rgba(102, 126, 234, 0.4);
}

.login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.5);
}

.login-btn:active {
    transform: translateY(0);
}

/* Responsive */
@media (max-width: 480px) {
    .login-box {
        padding: 40px 30px;
    }

    .logo-icon {
        width: 70px;
        height: 70px;
    }

    .logo-icon svg {
        width: 35px;
        height: 35px;
    }

    .logo-section h1 {
        font-size: 28px;
    }

    .input-field {
        padding: 14px 18px;
        font-size: 15px;
    }

    .login-btn {
        padding: 15px;
        font-size: 15px;
    }
}
</style>
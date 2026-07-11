<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login — Putra Dev</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0a0a0a;
            color: #f5f5f5;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Background glows */
        .glow-1 {
            position: fixed; top: -200px; left: -200px;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(16,185,129,0.10) 0%, transparent 70%);
            pointer-events: none;
        }
        .glow-2 {
            position: fixed; bottom: -200px; right: -150px;
            width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(52,211,153,0.06) 0%, transparent 70%);
            pointer-events: none;
        }
        .grid-bg {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            -webkit-mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 100%);
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 100%);
        }

        /* Card */
        .login-wrap {
            position: relative; z-index: 10;
            width: 100%; max-width: 420px; padding: 1.5rem;
        }

        /* Brand */
        .brand {
            text-align: center; margin-bottom: 2rem;
        }
        .brand-logo {
            display: inline-flex; align-items: center; justify-content: center;
            width: 48px; height: 48px; border-radius: 0.875rem;
            background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2);
            margin-bottom: 1rem;
        }
        .brand-logo svg { width: 22px; height: 22px; color: #34d399; }
        .brand h1 {
            font-size: 1.5rem; font-weight: 800; color: #fff;
            letter-spacing: -0.03em; margin: 0 0 0.3rem;
        }
        .brand h1 span {
            background: linear-gradient(135deg, #34d399, #10b981);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .brand p { font-size: 0.78rem; color: rgba(255,255,255,0.3); margin: 0; }

        /* Card box */
        .login-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 1.5rem;
            padding: 2rem;
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        }

        /* Error alert */
        .alert-error {
            display: flex; align-items: flex-start; gap: 0.6rem;
            padding: 0.875rem 1rem; border-radius: 0.75rem;
            background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);
            color: #f87171; font-size: 0.78rem; margin-bottom: 1.25rem; line-height: 1.5;
        }
        .alert-error svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: 1px; }

        /* Form groups */
        .fg { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1rem; }
        .fg label {
            font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.12em; color: rgba(255,255,255,0.3);
        }
        .fi-wrap { position: relative; }
        .fi-icon {
            position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%);
            pointer-events: none;
        }
        .fi-icon svg { width: 15px; height: 15px; color: rgba(255,255,255,0.2); }
        .fi {
            width: 100%; background: rgba(0,0,0,0.25);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.75rem; padding: 0.8rem 1rem 0.8rem 2.6rem;
            color: #f0f0f0; font-size: 0.84rem; outline: none;
            transition: all 0.25s ease; font-family: inherit;
        }
        .fi:focus {
            border-color: rgba(16,185,129,0.45);
            background: rgba(16,185,129,0.03);
            box-shadow: 0 0 0 3px rgba(16,185,129,0.07);
        }
        .fi::placeholder { color: rgba(255,255,255,0.15); }
        .fi-error-msg { font-size: 0.7rem; color: #f87171; margin-top: 0.25rem; }

        /* Password toggle */
        .pass-toggle {
            position: absolute; right: 0.875rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 0;
            color: rgba(255,255,255,0.2); transition: color 0.2s;
        }
        .pass-toggle:hover { color: rgba(255,255,255,0.5); }
        .pass-toggle svg { width: 15px; height: 15px; display: block; }

        /* Remember + forgot row */
        .form-extras {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .remember-label {
            display: flex; align-items: center; gap: 0.5rem; cursor: pointer;
        }
        .remember-label input { accent-color: #10b981; width: 14px; height: 14px; cursor: pointer; }
        .remember-label span { font-size: 0.78rem; color: rgba(255,255,255,0.35); }

        /* Submit button */
        .btn-login {
            width: 100%; background: linear-gradient(135deg, #10b981, #34d399);
            color: #0a0a0a; font-weight: 800; font-size: 0.875rem;
            padding: 0.875rem; border-radius: 0.75rem; border: none;
            cursor: pointer; font-family: inherit; letter-spacing: 0.01em;
            box-shadow: 0 0 25px rgba(16,185,129,0.3);
            transition: all 0.3s ease; display: flex; align-items: center;
            justify-content: center; gap: 0.5rem;
        }
        .btn-login:hover {
            box-shadow: 0 0 35px rgba(16,185,129,0.45);
            transform: translateY(-1px);
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login svg { width: 15px; height: 15px; }

        /* Divider */
        .card-divider {
            height: 1px; background: rgba(255,255,255,0.05);
            margin: 1.5rem 0;
        }

        /* Footer */
        .login-footer {
            text-align: center; margin-top: 1.5rem;
        }
        .login-footer p { font-size: 0.7rem; color: rgba(255,255,255,0.15); }
        .login-footer a { color: rgba(255,255,255,0.25); text-decoration: none; }
        .login-footer a:hover { color: #34d399; }

        /* Dot pulse animation */
        .status-dot {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.65rem; color: rgba(255,255,255,0.2);
            font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase;
            margin-bottom: 0.75rem;
        }
        .status-dot::before {
            content: ''; width: 5px; height: 5px; border-radius: 50%;
            background: #34d399; display: inline-block;
            box-shadow: 0 0 6px #34d399;
            animation: blink 2s ease-in-out infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; } 50% { opacity: 0.3; }
        }

        /* Fade in animation */
        .login-wrap {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="glow-1"></div>
    <div class="glow-2"></div>
    <div class="grid-bg"></div>

    <div class="login-wrap">

        {{-- Brand --}}
        <div class="brand">
            <div class="status-dot">Admin Panel</div>
            <p>Masuk untuk mengelola portofoliomu</p>
        </div>

        {{-- Card --}}
        <div class="login-card">

            {{-- Error --}}
            @if($errors->any())
            <div class="alert-error">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf

                {{-- Email --}}
                <div class="fg">
                    <label>Email</label>
                    <div class="fi-wrap">
                        <span class="fi-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="email" name="email" class="fi"
                               value="{{ old('email') }}"
                               placeholder="admin@email.com"
                               autocomplete="email" required />
                    </div>
                    @error('email') <p class="fi-error-msg">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div class="fg">
                    <label>Password</label>
                    <div class="fi-wrap">
                        <span class="fi-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" name="password" id="passwordInput" class="fi"
                               placeholder="••••••••"
                               autocomplete="current-password" required />
                        <button type="button" class="pass-toggle" onclick="togglePassword()" id="passToggleBtn">
                            <svg id="eyeIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="fi-error-msg">{{ $message }}</p> @enderror
                </div>

                {{-- Remember --}}
                <div class="form-extras">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" />
                        <span>Ingat saya</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Masuk ke Admin Panel
                </button>
            </form>

        </div>

        {{-- Footer --}}
        <div class="login-footer">
            <p>
                <a href="{{ url('/') }}">← Kembali ke Portfolio</a>
            </p>
            <p style="margin-top:0.5rem">&copy; {{ date('Y') }} PutraDev. All rights reserved.</p>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }
    </script>

</body>
</html>
<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            min-height: 100vh;
            width: 100vw;
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 50%, #FFFFFF 100%);
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background:
                radial-gradient(circle at 20% 20%, rgba(249, 115, 22, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(234, 88, 12, 0.06) 0%, transparent 50%);
        }

        .login-card {
            position: relative;
            z-index: 10;
            width: 440px;
            padding: 48px 50px;
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(20px);
            border-radius: 28px;
            box-shadow:
                0 20px 60px rgba(234, 88, 12, 0.12),
                0 8px 30px rgba(249, 115, 22, 0.08),
                0 0 0 1px rgba(249, 115, 22, 0.1) inset;
            border: 1px solid rgba(249, 115, 22, 0.12);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-decoration {
            position: absolute;
            top: -3px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 6px;
            background: linear-gradient(90deg, #FB923C, #EA580C);
            border-radius: 0 0 10px 10px;
        }

        .login-header { text-align: center; margin-bottom: 36px; }

        .login-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 16px;
            border-radius: 16px;
            background: linear-gradient(135deg, #FB923C, #EA580C);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 24px;
            box-shadow: 0 10px 25px rgba(234, 88, 12, 0.35);
        }

        .login-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
        }

        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-wrapper { position: relative; display: flex; align-items: center; }

        .form-input {
            width: 100%;
            padding: 13px 16px;
            font-size: 14px;
            color: #1e293b;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-input:focus {
            outline: none;
            border-color: #EA580C;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.12);
        }

        .password-toggle-icon {
            position: absolute;
            right: 12px;
            cursor: pointer;
            color: #94a3b8;
            user-select: none;
        }

        .error-message {
            font-size: 12px;
            color: #ef4444;
            margin-top: 6px;
            display: block;
        }

        .alert-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            font-size: 13px;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .submit-button {
            width: 100%;
            padding: 15px;
            font-size: 15px;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, #FB923C 0%, #EA580C 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(234, 88, 12, 0.3);
            font-family: 'Poppins', sans-serif;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(234, 88, 12, 0.4);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: #94a3b8;
            text-decoration: none;
        }

        .back-link:hover { color: #EA580C; }

        @media (max-width: 640px) {
            .login-card { width: 90%; padding: 36px 26px; margin: 20px; }
        }
    </style>

    <div class="login-container">
        <div class="login-card">
            <div class="card-decoration"></div>

            <div class="login-header">
                <div class="login-icon"><i class="fas fa-id-badge"></i></div>
                <h1 class="login-title">Portal Karyawan</h1>
                <p class="login-subtitle">Masuk dengan NIK dan password Anda</p>
            </div>

            @if (session('status'))
                <div class="alert-box">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('karyawan.login.store') }}" novalidate>
                @csrf

                <div class="form-group">
                    <label for="nik" class="form-label">NIK</label>
                    <div class="input-wrapper">
                        <input id="nik" type="text" name="nik" value="{{ old('nik') }}"
                               class="form-input" placeholder="Masukkan NIK" autofocus>
                    </div>
                    @error('nik')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input id="password" type="password" name="password"
                               class="form-input" placeholder="••••••••" style="padding-right: 40px;">
                        <span id="togglePassword" class="password-toggle-icon">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="submit-button">Masuk</button>
            </form>

            <a href="{{ route('portal.index') }}" class="back-link">&larr; Kembali ke Portal</a>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>
</x-guest-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - eFarmar</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('https://images.unsplash.com/photo-1574943320219-553eb213f72d?q=80&w=2070&auto=format&fit=crop') center/cover no-repeat fixed;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(16, 42, 18, 0.85) 0%, rgba(33, 75, 36, 0.7) 100%);
            z-index: 1;
        }

        .auth-wrapper {
            position: relative;
            z-index: 2;
            display: flex;
            width: 100%;
            max-width: 1100px;
            margin: 40px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            max-height: 90vh;
        }

        .auth-left {
            flex: 1.1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #fff;
            position: relative;
        }

        .auth-left::after {
            content: '';
            position: absolute;
            right: 0;
            top: 10%;
            bottom: 10%;
            width: 1px;
            background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.3), transparent);
        }

        .auth-left .brand { font-size: 38px; font-weight: 800; margin-bottom: 16px; letter-spacing: -1px; }
        .auth-left .brand span { color: #81C784; }
        .auth-left h2 { font-size: 42px; font-weight: 700; margin-bottom: 16px; line-height: 1.2; }
        .auth-left p { font-size: 16px; opacity: 0.9; line-height: 1.6; max-width: 420px; font-weight: 300; }

        .auth-right {
            width: 560px;
            padding: 48px;
            background: rgba(255, 255, 255, 0.97);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        
        /* Custom Scrollbar */
        .auth-right::-webkit-scrollbar { width: 6px; }
        .auth-right::-webkit-scrollbar-track { background: transparent; }
        .auth-right::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
        .auth-right::-webkit-scrollbar-thumb:hover { background: rgba(0,0,0,0.2); }

        .auth-right h1 { font-size: 32px; font-weight: 800; color: #1B5E20; margin-bottom: 8px; letter-spacing: -0.5px; }
        .auth-right .sub { font-size: 15px; color: #666; margin-bottom: 32px; }
        .auth-right .sub a { color: #2E7D32; font-weight: 600; text-decoration: none; transition: 0.2s; }
        .auth-right .sub a:hover { color: #1B5E20; text-decoration: underline; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { margin-bottom: 0; }
        .form-group.full { grid-column: 1 / -1; }
        .form-label { display: block; font-size: 14px; font-weight: 600; color: #333; margin-bottom: 8px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #9E9E9E; font-size: 18px; transition: 0.3s; }
        .form-control {
            width: 100%; padding: 12px 16px 12px 46px;
            border: 2px solid #EEEEEE; border-radius: 12px;
            font-family: 'Outfit', sans-serif; font-size: 15px; color: #333;
            background: #FAFAFA; outline: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .form-control:focus { border-color: #4CAF50; background: #fff; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.15); }
        .input-wrap:focus-within i { color: #4CAF50; }
        select.form-control { appearance: none; cursor: pointer; }

        .btn-submit {
            width: 100%; background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
            color: #fff; border: none; border-radius: 12px; padding: 16px;
            font-family: 'Outfit', sans-serif; font-size: 16px; font-weight: 700;
            cursor: pointer; transition: all 0.3s ease; margin: 28px 0 24px;
            box-shadow: 0 8px 16px rgba(46, 125, 50, 0.2);
            position: relative; overflow: hidden;
        }
        .btn-submit::after {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        .btn-submit:hover::after { left: 100%; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 12px 20px rgba(46, 125, 50, 0.3); background: linear-gradient(135deg, #388E3C 0%, #2E7D32 100%); }

        .divider { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
        .divider hr { flex: 1; border: none; border-top: 1px solid #E0E0E0; }
        .divider span { font-size: 13px; color: #9E9E9E; font-weight: 500; }

        .btn-google {
            width: 100%; background: #fff; border: 2px solid #EEEEEE;
            border-radius: 12px; padding: 14px;
            font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 600; color: #444;
            cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 12px;
            text-decoration: none;
        }
        .btn-google:hover { background: #FAFAFA; border-color: #E0E0E0; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .btn-google svg { width: 22px; height: 22px; }

        .alert-error { background: #FFEBEE; color: #C62828; border-left: 4px solid #C62828; border-radius: 10px; padding: 14px 16px; font-size: 14px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; font-weight: 500; }

        @media(max-width: 900px) {
            .auth-wrapper { flex-direction: column; margin: 20px; border-radius: 20px; max-height: none; }
            .auth-left { display: none; }
            .auth-right { width: 100%; padding: 40px 24px; border-radius: 20px; overflow-y: visible; }
        }
        @media(max-width: 480px) {
            .form-grid { grid-template-columns: 1fr; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Dark Mode Styles */
        body.dark-mode::before { background: linear-gradient(135deg, rgba(8, 15, 10, 0.95) 0%, rgba(15, 30, 18, 0.95) 100%); }
        body.dark-mode .auth-wrapper { background: rgba(0, 0, 0, 0.4); border-color: rgba(255, 255, 255, 0.05); }
        body.dark-mode .auth-right { background: rgba(22, 22, 22, 0.95); }
        body.dark-mode .auth-right h1 { color: #A5D6A7; }
        body.dark-mode .auth-right .sub { color: #aaa; }
        body.dark-mode .auth-right .sub a { color: #66BB6A; }
        body.dark-mode .form-label { color: #ddd; }
        body.dark-mode .form-control { background: #1A1A1A; border-color: #333; color: #eee; }
        body.dark-mode .form-control:focus { background: #222; border-color: #4CAF50; }
        body.dark-mode .check-label { color: #bbb; }
        body.dark-mode .forgot { color: #66BB6A; }
        body.dark-mode .btn-google { background: #1A1A1A; border-color: #333; color: #ddd; }
        body.dark-mode .btn-google:hover { background: #252525; border-color: #444; }
        body.dark-mode .divider hr { border-top-color: #333; }
    </style>
</head>
<body>
    <button id="theme-toggle" style="position:fixed; top:20px; right:20px; z-index:100; width:44px; height:44px; border-radius:50%; border:none; background:rgba(255,255,255,0.15); backdrop-filter:blur(10px); color:#fff; font-size:18px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:0.3s; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        <i class="bi bi-moon-fill"></i>
    </button>

    <div class="auth-wrapper">
        <div class="auth-left">
            <div class="brand">e<span>Farmar</span></div>
            <h2>Join India's Largest<br>Farmer Community</h2>
            <p>Register today and get access to real-time market prices, government schemes, crop management tools, and much more — completely free.</p>
        </div>

        <div class="auth-right">
            <h1>Create Your Account 🌱</h1>
            <p class="sub">Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>

            @if($errors->any())
                <div class="alert-error"><i class="bi bi-x-circle-fill"></i> {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group full">
                        <label class="form-label">Account Type *</label>
                        <div class="input-wrap">
                            <i class="bi bi-person-badge"></i>
                            <select name="role" class="form-control" required>
                                <option value="farmer" {{ old('role', 'farmer') === 'farmer' ? 'selected' : '' }}>Farmer</option>
                                <option value="buyer" {{ old('role') === 'buyer' ? 'selected' : '' }}>Buyer</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Full Name *</label>
                        <div class="input-wrap">
                            <i class="bi bi-person"></i>
                            <input type="text" name="name" class="form-control" placeholder="Rajesh Kumar" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Email Address *</label>
                        <div class="input-wrap">
                            <i class="bi bi-envelope"></i>
                            <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <div class="input-wrap">
                            <i class="bi bi-shield-lock"></i>
                            <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password *</label>
                        <div class="input-wrap">
                            <i class="bi bi-shield-check"></i>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <div class="input-wrap">
                            <i class="bi bi-phone"></i>
                            <input type="tel" name="phone" class="form-control" placeholder="9876543210" value="{{ old('phone') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Farm Size (acres)</label>
                        <div class="input-wrap">
                            <i class="bi bi-map"></i>
                            <input type="number" name="farm_size" class="form-control" placeholder="e.g. 5.5" step="0.1" value="{{ old('farm_size') }}">
                        </div>
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Location / Village</label>
                        <div class="input-wrap">
                            <i class="bi bi-geo-alt"></i>
                            <input type="text" name="location" class="form-control" placeholder="e.g. Ludhiana, Punjab" value="{{ old('location') }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Create My Free Account</button>
            </form>

            <div class="divider"><hr><span>or</span><hr></div>
            <a href="{{ route('auth.google') }}" class="btn-google">
                <svg viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Sign up with Google
            </a>
        </div>
    </div>

    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const body = document.body;
        
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-mode');
            themeToggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
        }

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                themeToggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
            } else {
                localStorage.setItem('theme', 'light');
                themeToggle.innerHTML = '<i class="bi bi-moon-fill"></i>';
            }
        });
    </script>
</body>
</html>

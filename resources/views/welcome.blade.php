<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eFarmar - Empowering Farmers, Enriching Lives</title>
    <meta name="description" content="eFarmar is India's most advanced digital agriculture platform for crop management, real-time market prices, and government schemes.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary: #2D6A4F;
            --primary-light: #40916C;
            --primary-dark: #1B4332;
            --accent: #D4A373;
            --accent-light: #FAEDCD;
            --success: #52B788;
            --bg-light: #F8F9FA;
            --text-main: #1A1A1A;
            --text-muted: #555555;
            --glass: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
            --shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-main);
            background-color: var(--bg-light);
            line-height: 1.6;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* --- NAVIGATION --- */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 20px 8%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        nav.scrolled {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            padding: 12px 8%;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid var(--glass-border);
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            text-decoration: none;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo i { color: var(--success); font-size: 32px; }
        .logo span { color: var(--success); }
        nav.scrolled .logo { color: var(--primary); }

        .nav-links { display: flex; gap: 40px; align-items: center; }
        .nav-links a {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            font-size: 15px;
            transition: 0.3s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--success);
            transition: 0.3s;
        }

        .nav-links a:hover::after { width: 100%; }
        .nav-links a:hover { color: #fff; }
        
        nav.scrolled .nav-links a { color: var(--text-muted); }
        nav.scrolled .nav-links a:hover { color: var(--primary); }

        .nav-btns { display: flex; gap: 15px; align-items: center; }
        
        .btn-login {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 24px;
            transition: 0.3s;
        }
        nav.scrolled .btn-login { color: var(--primary); }

        .btn-register {
            background: var(--success);
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 12px;
            transition: 0.4s;
            box-shadow: 0 10px 20px -5px rgba(82, 183, 136, 0.4);
        }
        .btn-register:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(82, 183, 136, 0.5);
        }

        /* --- HERO SECTION --- */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 120px 8% 80px;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                        url('/images/hero-bg.png') center/cover no-repeat;
            color: #fff;
            text-align: center;
        }

        .hero-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.6) 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: 100px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 30px;
            animation: fadeInDown 1s ease;
        }

        .hero h1 {
            font-size: clamp(40px, 6vw, 84px);
            line-height: 1.1;
            font-weight: 900;
            margin-bottom: 25px;
            letter-spacing: -2px;
            animation: fadeInUp 1s ease 0.2s both;
        }

        .hero h1 span {
            background: linear-gradient(to right, #B7E4C7, #D8F3DC);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 20px;
            opacity: 0.9;
            max-width: 650px;
            margin: 0 auto 45px;
            animation: fadeInUp 1s ease 0.4s both;
        }

        .hero-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            animation: fadeInUp 1s ease 0.6s both;
        }

        .btn-hero {
            padding: 18px 40px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero-primary {
            background: var(--success);
            color: #fff;
            box-shadow: 0 20px 40px -10px rgba(82, 183, 136, 0.5);
        }

        .btn-hero-primary:hover {
            transform: scale(1.05) translateY(-5px);
            background: #fff;
            color: var(--primary-dark);
        }

        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: #fff;
            transform: translateY(-3px);
        }

        /* --- STATS SECTION --- */
        .stats-container {
            position: relative;
            z-index: 10;
            margin-top: -60px;
            padding: 0 8%;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            background: #fff;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 20px 60px -10px rgba(0,0,0,0.1);
        }

        .stat-item {
            text-align: center;
            padding: 10px;
        }

        .stat-item h3 {
            font-size: 36px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .stat-item p {
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* --- FEATURES SECTION --- */
        section { padding: 120px 8%; }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 70px;
        }

        .section-tag {
            color: var(--success);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 13px;
            margin-bottom: 15px;
            display: block;
        }

        .section-title {
            font-size: 44px;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: #fff;
            padding: 45px;
            border-radius: 24px;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #f0f0f0;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, var(--success), var(--primary));
            opacity: 0;
            transition: 0.5s;
            z-index: 0;
        }

        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 30px 60px -15px rgba(0,0,0,0.1);
        }

        .feature-card:hover::before { opacity: 0.03; }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: var(--accent-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            border-radius: 20px;
            margin-bottom: 30px;
            transition: 0.4s;
        }

        .feature-card:hover .feature-icon {
            background: var(--primary);
            color: #fff;
            transform: rotateY(180deg);
        }

        .feature-card h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary-dark);
        }

        .feature-card p {
            color: var(--text-muted);
            font-size: 16px;
            line-height: 1.7;
        }

        /* --- HOW IT WORKS --- */
        .how-it-works { background: #fff; }
        
        .steps-container {
            display: flex;
            justify-content: space-between;
            gap: 40px;
            flex-wrap: wrap;
            margin-top: 50px;
        }

        .step {
            flex: 1;
            min-width: 250px;
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: var(--primary-dark);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 800;
            border-radius: 50%;
            margin: 0 auto 25px;
            box-shadow: 0 10px 25px rgba(27, 67, 50, 0.3);
        }

        .step h3 { font-size: 20px; margin-bottom: 12px; }
        .step p { color: var(--text-muted); font-size: 15px; }

        /* --- MARKET TABLE --- */
        .market-section { background: var(--bg-light); }

        .glass-card {
            background: #fff;
            border-radius: 30px;
            padding: 50px;
            box-shadow: var(--shadow);
            border: 1px solid var(--glass-border);
        }

        .table-responsive { overflow-x: auto; }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        th {
            text-align: left;
            padding: 20px;
            color: var(--text-muted);
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tbody tr {
            background: #fbfbfb;
            transition: 0.3s;
        }

        tbody tr:hover {
            transform: scale(1.01);
            background: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        td {
            padding: 20px;
            font-weight: 600;
            color: var(--text-main);
        }

        td:first-child { border-radius: 15px 0 0 15px; color: var(--primary); font-weight: 800; }
        td:last-child { border-radius: 0 15px 15px 0; }

        .trend-badge {
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
        }

        .trend-up { background: #E8F5E9; color: #2E7D32; }
        .trend-down { background: #FFEBEE; color: #C62828; }

        /* --- CTA BANNER --- */
        .cta-banner {
            margin: 80px 8%;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            border-radius: 40px;
            padding: 80px;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .cta-banner::before {
            content: '';
            position: absolute;
            top: -50%; right: -20%; width: 600px; height: 600px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .cta-banner h2 { font-size: 48px; margin-bottom: 25px; }
        .cta-banner p { font-size: 18px; opacity: 0.8; max-width: 600px; margin: 0 auto 40px; }

        /* --- FOOTER --- */
        footer {
            background: #121212;
            color: #fff;
            padding: 100px 8% 50px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 60px;
            margin-bottom: 60px;
        }

        .footer-logo { font-size: 32px; font-weight: 900; color: #fff; margin-bottom: 25px; display: block; text-decoration: none; }
        .footer-logo span { color: var(--success); }

        .footer-desc { opacity: 0.6; line-height: 1.8; margin-bottom: 30px; font-size: 15px; }

        .footer-links h4 { font-size: 18px; margin-bottom: 25px; color: var(--success); }
        .footer-links ul { list-style: none; }
        .footer-links li { margin-bottom: 15px; }
        .footer-links a { color: rgba(255,255,255,0.6); text-decoration: none; transition: 0.3s; }
        .footer-links a:hover { color: #fff; padding-left: 5px; }

        .social-links { display: flex; gap: 15px; }
        .social-links a {
            width: 45px; height: 45px;
            background: rgba(255,255,255,0.05);
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px; transition: 0.3s; color: #fff; text-decoration: none;
        }
        .social-links a:hover { background: var(--success); transform: translateY(-5px); }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.05);
            padding-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            opacity: 0.5;
        }

        /* --- ANIMATIONS --- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 992px) {
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .hero h1 { font-size: 60px; }
            nav { padding: 20px 4%; }
            .nav-links { display: none; }
        }

        @media (max-width: 600px) {
            .footer-grid { grid-template-columns: 1fr; }
            .cta-banner { padding: 40px 20px; }
            .hero-actions { flex-direction: column; }
            .section-title { font-size: 32px; }
            .cta-banner h2 { font-size: 32px; }
        }
    </style>
</head>
<body>

    <nav id="navbar">
        <a href="/" class="logo">
            <img src="/images/logo.png" alt="eFarmar" style="height: 65px; mix-blend-mode: multiply;">
        </a>
        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#how">How It Works</a>
            <a href="#market">Market Prices</a>
            <a href="{{ route('schemes.index') }}">Govt Schemes</a>
        </div>
        <div class="nav-btns">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-register">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-login">Login</a>
                <a href="{{ route('register') }}" class="btn-register">Join Now</a>
            @endauth
        </div>
    </nav>

    <header class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-badge">
                <i class="bi bi-shield-check"></i> India's #1 Trusted Farming Ecosystem
            </div>
            <h1>Empowering Farmers,<br><span>Cultivating Futures</span></h1>
            <p>Seamlessly manage crops, track real-time market trends, and access premium government benefits all in one intelligent workspace.</p>
            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">
                    Start Your Journey <i class="bi bi-arrow-right"></i>
                </a>
                <a href="#features" class="btn-hero btn-hero-secondary">
                    Learn More
                </a>
            </div>
        </div>
    </header>

    <div class="stats-container">
        <div class="stats-grid">
            <div class="stat-item">
                <h3>50K+</h3>
                <p>Global Farmers</p>
            </div>
            <div class="stat-item">
                <h3>₹20M+</h3>
                <p>Transaction Value</p>
            </div>
            <div class="stat-item">
                <h3>1.2K+</h3>
                <p>Markets Linked</p>
            </div>
            <div class="stat-item">
                <h3>24/7</h3>
                <p>Expert Support</p>
            </div>
        </div>
    </div>

    <section id="features">
        <div class="section-header reveal">
            <span class="section-tag">Core Ecosystem</span>
            <h2 class="section-title">Everything You Need to Scale Your Farm</h2>
            <p>A sophisticated digital toolkit designed to eliminate middlemen and maximize your agricultural potential.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card reveal">
                <div class="feature-icon"><i class="bi bi-boxes"></i></div>
                <h3>Smart Management</h3>
                <p>Intelligent inventory tracking and harvest planning tools that grow with your business.</p>
            </div>
            <div class="feature-card reveal" style="transition-delay: 0.1s;">
                <div class="feature-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <h3>Market Intelligence</h3>
                <p>Real-time data from 1,200+ markets with predictive pricing to help you sell at the peak.</p>
            </div>
            <div class="feature-card reveal" style="transition-delay: 0.2s;">
                <div class="feature-icon"><i class="bi bi-cash-stack"></i></div>
                <h3>Direct Commerce</h3>
                <p>Bypass intermediaries and connect directly with bulk buyers for maximum profit margins.</p>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon"><i class="bi bi-bank"></i></div>
                <h3>Scheme Gateway</h3>
                <p>Auto-match your profile with high-impact government subsidies and loan programs.</p>
            </div>
            <div class="feature-card reveal" style="transition-delay: 0.1s;">
                <div class="feature-icon"><i class="bi bi-cloud-sun"></i></div>
                <h3>Weather Precision</h3>
                <p>Hyper-local forecasts and risk alerts to protect your yields from unpredictable weather.</p>
            </div>
            <div class="feature-card reveal" style="transition-delay: 0.2s;">
                <div class="feature-icon"><i class="bi bi-people"></i></div>
                <h3>Community Hub</h3>
                <p>Share insights and trade knowledge with a vast network of progressive Indian farmers.</p>
            </div>
        </div>
    </section>

    <section class="how-it-works" id="how">
        <div class="section-header reveal">
            <span class="section-tag">Simple Flow</span>
            <h2 class="section-title">How It Works</h2>
        </div>
        <div class="steps-container reveal">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Register</h3>
                <p>Setup your digital farm profile in under 60 seconds.</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Update</h3>
                <p>List your current crops and expected harvest dates.</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Analyze</h3>
                <p>Monitor market trends and government schemes.</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h3>Sell</h3>
                <p>Close deals with trusted buyers at premium rates.</p>
            </div>
        </div>
    </section>

    <section class="market-section" id="market">
        <div class="section-header reveal">
            <span class="section-tag">Live Market</span>
            <h2 class="section-title">Real-Time Market Prices</h2>
            <p>Direct feed from APMC markets across the country.</p>
        </div>
        <div class="glass-card reveal">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Commodity</th>
                            <th>Market Location</th>
                            <th>Min / Max Price</th>
                            <th>Modal Price</th>
                            <th>Daily Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Premium Wheat</td>
                            <td>Karnal, Haryana</td>
                            <td>₹2,150 / ₹2,400</td>
                            <td>₹2,275</td>
                            <td><span class="trend-badge trend-up"><i class="bi bi-caret-up-fill"></i> +1.2%</span></td>
                        </tr>
                        <tr>
                            <td>Basmati Rice</td>
                            <td>Patna, Bihar</td>
                            <td>₹2,800 / ₹3,200</td>
                            <td>₹3,050</td>
                            <td><span class="trend-badge trend-up"><i class="bi bi-caret-up-fill"></i> +0.8%</span></td>
                        </tr>
                        <tr>
                            <td>Sweet Maize</td>
                            <td>Indore, MP</td>
                            <td>₹1,750 / ₹1,950</td>
                            <td>₹1,850</td>
                            <td><span class="trend-badge trend-down"><i class="bi bi-caret-down-fill"></i> -0.4%</span></td>
                        </tr>
                        <tr>
                            <td>Organic Cotton</td>
                            <td>Rajkot, Gujarat</td>
                            <td>₹6,200 / ₹6,800</td>
                            <td>₹6,500</td>
                            <td><span class="trend-badge trend-up"><i class="bi bi-caret-up-fill"></i> +2.1%</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="cta-banner reveal">
        <h2>Ready to Modernize Your Farm?</h2>
        <p>Join over 50,000 progressive farmers who are already scaling their profits with eFarmar's intelligent ecosystem.</p>
        <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">Create Your Free Account</a>
    </div>

    <footer>
        <div class="footer-grid">
            <div>
                <a href="/" class="footer-logo">
                    <img src="/images/logo.png" alt="eFarmar" style="height: 60px; mix-blend-mode: lighten; filter: brightness(1.2);">
                </a>
                <p class="footer-desc">
                    India's leading agricultural intelligence platform. We empower the backbone of our nation with modern technology, real-time data, and direct market access.
                </p>
                <div class="social-links">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <div class="footer-links">
                <h4>Platform</h4>
                <ul>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#market">Market Data</a></li>
                    <li><a href="#how">How it Works</a></li>
                    <li><a href="{{ route('login') }}">Pricing</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Farm Guide</a></li>
                    <li><a href="#">Market FAQ</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Newsletter</h4>
                <p class="footer-desc" style="margin-bottom: 20px;">Get weekly agriculture insights and market trends.</p>
                <div style="display: flex; gap: 10px;">
                    <input type="text" placeholder="Email" style="padding: 12px; border-radius: 8px; border: none; flex: 1; background: rgba(255,255,255,0.05); color: #fff;">
                    <button style="background: var(--success); border: none; padding: 12px; border-radius: 8px; color: #fff;"><i class="bi bi-send"></i></button>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ now()->year }} eFarmar. Empowering Bharat, One Farm at a Time.</p>
            <p>Privacy Policy • Terms of Service</p>
        </div>
    </footer>

    <script>
        // Navbar effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Reveal animations
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>
</body>
</html>


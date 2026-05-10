<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jukez Tool Library - Premium P2P Sharing</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --bg: #0f172a;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --glass: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .gradient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.15) 0%, rgba(15, 23, 42, 0) 50%);
            z-index: -1;
        }

        nav {
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }

        .nav-links a {
            color: var(--text);
            text-decoration: none;
            margin-left: 2rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem;
            text-align: center;
        }

        h1 {
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            max-width: 900px;
        }

        h1 span {
            display: block;
            background: linear-gradient(to right, #818cf8, #e879f9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            font-size: 1.25rem;
            color: var(--text-muted);
            max-width: 600px;
            margin-bottom: 3rem;
            line-height: 1.6;
        }

        .hero-btns {
            display: flex;
            gap: 1rem;
        }

        .btn-outline {
            border: 1px solid var(--glass-border);
            color: var(--text);
            background: var(--glass);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary);
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            width: 100%;
            max-width: 1200px;
            margin-top: 6rem;
        }

        .feature-card {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            padding: 2.5rem;
            border-radius: 24px;
            text-align: left;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.05);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--primary);
            font-size: 1.5rem;
        }

        .feature-card h3 {
            margin-bottom: 0.75rem;
            font-size: 1.25rem;
        }

        .feature-card p {
            color: var(--text-muted);
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="gradient-bg"></div>
    
    <nav>
        <div class="logo">JUKEZ LIBRARY</div>
        <div class="nav-links">
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Join Community</a>
        </div>
    </nav>

    <main>
        <h1>Share Tools. <span>Build Community.</span></h1>
        <p class="subtitle">The premium peer-to-peer library for specialized tools. Rent what you need, lend what you have, and grow together.</p>
        
        <div class="hero-btns">
            <a href="{{ route('register') }}" class="btn btn-primary">Get Started Now</a>
            <a href="#features" class="btn btn-outline">Explore Tools</a>
        </div>

        <div id="features" class="features">
            <div class="feature-card">
                <div class="feature-icon">🛠️</div>
                <h3>Quality Tools</h3>
                <p>Access high-end specialized equipment without the high-end price tag.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3>Trusted Sharing</h3>
                <p>Built-in trust scores and verification system to keep the community safe.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Quick Rental</h3>
                <p>Seamless booking process with instant availability checks.</p>
            </div>
        </div>
    </main>
</body>
</html>

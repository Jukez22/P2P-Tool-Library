<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Jukez Library') }}</title>
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
            min-height: 100vh;
        }

        .gradient-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.1) 0%, rgba(15, 23, 42, 0) 50%);
            z-index: -1;
        }

        nav {
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
            background: rgba(15, 23, 42, 0.8);
        }

        .logo a {
            font-size: 1.25rem;
            font-weight: 800;
            text-decoration: none;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .card {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2.5rem;
            backdrop-filter: blur(10px);
        }

        input, select, textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: white;
            margin-top: 0.5rem;
            margin-bottom: 1.5rem;
            transition: border-color 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.08);
        }

        label {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            width: 100%;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }
    </style>
</head>
<body>
    <div class="gradient-bg"></div>
    
    <nav>
        <div class="logo"><a href="/">JUKEZ</a></div>
        <div>
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="background:none; border:none; color:white; cursor:pointer;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" style="color:white; text-decoration:none; margin-right:1.5rem;">Login</a>
                <a href="{{ route('register') }}" style="color:white; text-decoration:none;">Register</a>
            @endauth
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>
</body>
</html>

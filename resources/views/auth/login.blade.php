<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/login.css">  
    <title>New Century Books</title>
</head>
<body>
    <div class="container">
        <div class="catchphrase">
            <h1>Welcome Back to Your Bookstore</h1>
            <p>Access your textbooks, references, and academic resources anytime. Log in and continue your journey to smarter learning and greater success.</p>  
        </div>
        <div class="login-form">
            <div class="logo-wrapper">
                <img src="/img/logo.png" alt="logo">
            </div>
            <h2>Sign in to NCB</h2>

            @if (session('success'))
                <div class="success">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn">Sign In</button>
            </form>
            <p class="already">
                Don't have an account? <a href="{{ route('register') }}">Sign up</a>
            </p>
        </div>
    </div>
    <div class="copyright">
        <p> © 2025 New Century Books. All rights reserved.</p>
        <p><a href="">Privacy Policy&nbsp;</a><a href="">Terms of Service&nbsp;</a></p>
    </div>
</body>
</html>
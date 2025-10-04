<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../resources/css/login.css">  
    <style>
        html, body {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins'; 
    height: 100%;
    overflow-x: hidden; 
    scroll-behavior: smooth;
}

body {
    background: url('/resources/img/background.jpg') no-repeat center center;
    background-size: cover;   
}

.container {
    display: flex;
    justify-content: center;  
    align-items: center;      
    gap: 136px;
    min-height: 94vh;        
}

.catchphrase{
    width: 700px;
    color: #FFFFFF;
    font-family: 'Poppins', sans-serif;
}

.catchphrase h1{
    font-weight: 750;
    font-size: 32px;
    white-space: nowrap;
}

.catchphrase p{
    font-weight: 750;
    font-size: 24px;

}


.login-form{
    display: flex;
    align-items: center;
    flex-direction: column;
    width: 540px;
    height: 618px;
    background-color: #FFFFFF;
    border-radius: 40px;
    font-family: 'Poppins', sans-serif;
}

.logo-wrapper {
    margin-bottom: 0; 
}

.logo-wrapper img { 
    height: auto;
    display: block;
    margin: 0 auto; 
}

.login-form h2{
    font-weight: 700;
    font-size: 40px;
    margin-bottom: 44px;
    margin-top: 0;
}

.form-group {
    width: 395px;
    margin-bottom: 19px;
    display: flex;
    flex-direction: column;
}

.form-group input{
    
    padding: 13px 10px 13px 11px;
    font-size: 24px;
    border-radius: 10px;
    border: 0.4px solid #ccc;
}

.login-form button{
    width: 395px;
    height: 55px;
    background-color: #FCAE42;
    color: black;
    font-size: 24px;
    font-weight: 400;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    margin-top: 20px;
    margin-bottom: 7px;
}

.already{
    width: 395px;
    font-family: 'poppins', sans-serif;
    text-align: left;
}


.copyright{
    margin-left: 50px;
    font-family: 'Poppins', sans-serif;
    margin-top: 0;
    font-size: 20px;
    color: #FFFFFF;
}

.copyright p{
    margin-top: 0;
    margin-bottom: 0;
}

.copyright a{
    color: #FFFFFF;
    text-decoration: none;
    margin-right: 39px;
}

.login-form a {
  color: #ED1B24; 
  text-decoration: none;
}


    </style>
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
                <img src="/resources/img/logo.png" alt="logo">
            </div>
            <h2>Sign in to NCB</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn">Sign In</button>
            </form>
            <p class="already">
                Don’t have an account? <a href="{{ route('register') }}">Register here</a>
            </p>
        </div>
    </div>
     <div class="copyright">
        <p> © 2025 New Century Books. All rights reserved.</p>
        <p><a href="">Privacy Policy&nbsp;</a><a href="">Terms of Service&nbsp;</a></p>
    </div>
</body>
</html>
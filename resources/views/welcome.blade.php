<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            background-color: #f3f4f6;
            color: #374151;
            font-family: Chalkboard, serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .nav-link {
            display: inline-block;
            padding: 10px 20px;
            border: 1px solid transparent;
            border-radius: 5px;
            background-color: #6875f5;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }
        .nav-link:hover {
            background-color: #4c51bf;
        }
        .nav-link + .nav-link {
            margin-left: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 style="text-align: center; margin-bottom: 30px;">Welcome Inbo</h1>
    @if (Route::has('login'))
        <nav style="text-align: center;">
            @auth
                <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="nav-link">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                @endif
            @endauth
        </nav>
    @endif
</div>
</body>
</html>

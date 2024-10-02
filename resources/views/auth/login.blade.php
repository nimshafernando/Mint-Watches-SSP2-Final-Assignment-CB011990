<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MintWatches.lk</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Fredoka One', cursive;
            background-color: #ff6600;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Fullscreen video background */
        video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            filter: brightness(0.5);
        }

        /* Authentication Card */
        .authentication-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            z-index: 2;
            animation: fadeInUp 1s ease-out;
        }

        .authentication-card h1 {
            font-family: 'Fredoka One', cursive;
            font-size: 2.5rem;
            color: #ff6600;
            margin-bottom: 1.5rem;
        }

        .authentication-card p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 0.5rem;
        }

        .authentication-card .admin-message {
            color: #ff3333;
            font-size: 0.9rem;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .input {
            border: 1px solid #cbd5e0;
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        .input:focus {
            border-color: #ff6600;
            box-shadow: 0 0 0 2px rgba(255, 102, 0, 0.3);
            outline: none;
        }

        .button {
            background-color: #ff6600;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            width: 100%;
        }

        .button:hover {
            background-color: #ff3300;
            transform: translateY(-3px);
        }

        .footer {
            margin-top: 2rem;
            font-size: 0.85rem;
            color: #666;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .authentication-card {
                max-width: 90%;
                padding: 20px;
            }

            .authentication-card h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Fullscreen background video -->
    <video autoplay muted loop>
        <source src="/adminvideo.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Authentication Card -->
    <div class="authentication-card">
        <h1>Admin</h1>
        <p>Welcome back!</p>
        <p class="admin-message">Only administrators can log in. Other users will not have access.</p>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Input -->
            <input type="email" class="input" id="email" name="email" placeholder="Email Address" required autofocus autocomplete="username">

            <!-- Password Input -->
            <input type="password" class="input" id="password" name="password" placeholder="Password" required autocomplete="current-password">

            <!-- Submit Button -->
            <button type="submit" class="button">Log In</button>
        </form>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} MintWatches.lk | Admin Panel</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MintWatches.lk Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css2?family=Fredoka+One&display=swap" rel="stylesheet" />

    <!-- Font Awesome (for watch icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            background-color: #000;
        }

        /* Fullscreen video background */
        video {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
            object-fit: cover;
        }

        /* Container for login box */
        .container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 450px;
            text-align: center;
            z-index: 2;
            animation: fadeInUp 1s ease-out;
        }

        /* Main heading using Fredoka One font */
        .container h1 {
            font-family: 'Fredoka One', cursive;
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .container p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #333;
        }

        .btn-login {
            background-color: #ff6a00;
            color: #fff;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Cool hover effect for the button */
        .btn-login:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(255, 106, 0, 0.5);
        }

        /* Watch icon */
        .watch-icon {
            font-size: 60px;
            color: #ff6a00;
            margin-bottom: 20px;
        }

        .footer {
            font-size: 0.85rem;
            color: #666;
            margin-top: 30px;
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

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                max-width: 90%;
                padding: 20px;
            }

            .container h1 {
                font-size: 2rem;
            }

            .container p {
                font-size: 1rem;
            }

            .btn-login {
                padding: 12px 25px;
                font-size: 0.9rem;
            }

            .watch-icon {
                font-size: 50px;
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

    <!-- Login Box -->
    <div class="container">
        <!-- Watch Icon -->
        <i class="fas fa-stopwatch watch-icon"></i>

        <!-- Main Heading -->
        <h1>MintWatches.lk</h1>
        <p>Welcome to the Admin Page. Please log in to manage your collections.</p>

        <!-- Login Button -->
        <a href="{{ route('login') }}">
            <button class="btn-login">Admin Login</button>
        </a>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} MintWatches.lk | Admin Panel</p>
        </div>
    </div>
</body>
</html>

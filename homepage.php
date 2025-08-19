<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HeartHealth - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8;
            color: #1f2937;
        }
        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #3b82f6;
            padding: 15px 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .navbar .logo {
            color: #fff;
            font-size: 26px;
            font-weight: 700;
            text-decoration: none;
        }
        .navbar ul {
            list-style: none;
            display: flex;
            align-items: center;
        }
        .navbar ul li {
            margin-left: 25px;
        }
        .navbar ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: 600;
            position: relative;
            transition: 0.3s;
        }
        .navbar ul li a::after {
            content: "";
            position: absolute;
            width: 0%;
            height: 2px;
            bottom: -3px;
            left: 0;
            background-color: #fff;
            transition: 0.3s;
        }
        .navbar ul li a:hover::after {
            width: 100%;
        }
        .menu-toggle {
            display: none;
            font-size: 24px;
            color: #fff;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .navbar ul {
                display: none;
                flex-direction: column;
                background: #3b82f6;
                position: absolute;
                top: 60px;
                right: 0;
                width: 200px;
                border-radius: 0 0 10px 10px;
            }
            .navbar ul.show {
                display: flex;
            }
            .navbar ul li {
                margin: 15px 0;
                text-align: right;
                margin-right: 20px;
            }
            .menu-toggle {
                display: block;
            }
        }

        /* Hero Section */
        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 100px 20px;
            background: linear-gradient(to right, #3b82f6, #60a5fa);
            color: #fff;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
        }
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: 700;
        }
        .hero p {
            font-size: 20px;
            margin-bottom: 40px;
            max-width: 700px;
            line-height: 1.6;
        }
        .hero button {
            padding: 15px 30px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            background-color: #fff;
            color: #3b82f6;
            cursor: pointer;
            transition: 0.3s;
        }
        .hero button:hover {
            background-color: #e0f2fe;
        }

        /* Features Section */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            padding: 80px 20px;
            max-width: 1200px;
            margin: auto;
        }
        .feature-card {
            background: #fff;
            padding: 30px 20px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            text-align: center;
            transition: 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
        }
        .feature-card h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #1e3a8a;
        }
        .feature-card p {
            font-size: 16px;
            color: #334155;
            line-height: 1.5;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 40px 20px;
            background-color: #3b82f6;
            color: #fff;
            font-weight: 500;
        }

        /* Kullanıcı dropdown */
.user-dropdown {
    position: relative;
    margin-left: 15px;
    font-weight: bold;
    color: #fff;
}

.user-dropdown a {
    color: #fff;
    text-decoration: none;
    cursor: pointer;
}

.user-dropdown .dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #f63b3bff;
    min-width: 120px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    z-index: 10;
}

.user-dropdown .dropdown-content li {
    text-align: center;
}

.user-dropdown .dropdown-content li a {
    display: block;
    padding: 10px;
    color: #fff;
    text-decoration: none;
}

.user-dropdown .dropdown-content li a:hover {
    background-color: #eb2525ff;
}

.user-dropdown:hover .dropdown-content {
    display: block;
}

    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="#" class="logo">HeartHealth</a>
        <span class="menu-toggle">&#9776;</span>
        <ul>
            <li><a href="homepage.php">Home</a></li>
            <li><a href="disease.php">Could I Have Heart Disease?</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="communication.php">Contact</a></li>
            <li><a href="listpatient.php">List Patient Results</a></li>
            <li class="user-dropdown">
        <a href="javascript:void(0)">Hello, <?= htmlspecialchars($username) ?></a>
        <ul class="dropdown-content">
            <li><a href="logout.php">Çıkış Yap</a></li>
        </ul>
    </li>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Monitor Your Heart Health</h1>
        <p>HeartHealth helps you understand your cardiovascular risks in an easy, safe, and reliable way. Take control of your heart health today with our advanced predictor and clear insights.</p>
        <button onclick="window.location.href='disease.php'">Check My Risk</button>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="feature-card">
            <h3>Easy to Use</h3>
            <p>Enter your basic health information and get instant insights about your heart risk without any medical background.</p>
        </div>
        <div class="feature-card">
            <h3>Professional Insights</h3>
            <p>Our predictor uses data-driven methods trusted by medical professionals to provide accurate heart health guidance.</p>
        </div>
        <div class="feature-card">
            <h3>Secure & Private</h3>
            <p>Your personal data is fully secure and never shared. Privacy is our top priority while helping you monitor your heart.</p>
        </div>
        <div class="feature-card">
            <h3>Guided Recommendations</h3>
            <p>Receive clear, easy-to-understand suggestions based on your results, helping you take proactive steps for a healthier heart.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        &copy; 2025 HeartHealth. All rights reserved.
    </footer>

    <script>
        // Navbar toggle
        const toggle = document.querySelector('.menu-toggle');
        const menu = document.querySelector('.navbar ul');
        toggle.addEventListener('click', () => { menu.classList.toggle('show'); });
    </script>

</body>
</html>

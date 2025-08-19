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
    <title>About Us - HeartHealth</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background: #e6f0fa; /* Açık mavi arka plan */
            color: #1e293b;
        }

        /* NAVBAR */
        header {
            background: linear-gradient(90deg, #2563eb, #1d4ed8); /* Mavi tonlu navbar */
            padding: 20px 40px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        header .logo { 
            font-size: 24px;
            font-weight: 700;
            color:#fff;
            text-decoration:none;
        }
        nav ul {
            list-style: none;
            display: flex;
            gap: 25px;
            margin: 0;
            padding: 0;
        }
        nav ul li a {
            text-decoration: none;
            font-weight: 600;
            color:#fff;
            transition: 0.3s;
        }
        nav ul li a:hover {
            color:#bfdbfe;
        }

        /* HERO SECTION */
        .hero {
            position: relative;
            background: url('https://images.unsplash.com/photo-1588776814546-1fbc1b7e0f94?auto=format&fit=crop&w=1470&q=80') center/cover no-repeat;
            padding: 120px 40px 80px;
            text-align: center;
            color: #fff;
        }
        .hero::before{
            content:"";
            position:absolute; inset:0;
            background: linear-gradient(180deg, rgba(37,99,235,0.75), rgba(29,78,216,0.55));
        }
        .hero h1, .hero p {
            position: relative;
            margin: 0;
        }
        .hero h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .hero p {
            font-size: 20px;
            max-width: 700px;
            margin: 0 auto;
        }

        /* MAIN CONTENT */
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 0 20px;
        }
        .card {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            border: 1px solid #dbeafe;
        }
        h2 {
            font-size: 32px;
            text-align: center;
            margin-bottom: 25px;
            color: #1e40af;
        }
        p {
            font-size: 18px;
            line-height: 1.8;
            margin-bottom: 20px;
            color: #334155;
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            color: #fff;
            font-size: 14px;
        }

        @media (max-width: 768px){
            nav ul {
                flex-direction: column;
                gap: 15px;
            }
            .hero h1 {
                font-size: 36px;
            }
            .hero p {
                font-size: 16px;
            }
        }

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
    <header>
        <a href="index.html" class="logo">HeartHealth</a>
        <nav>
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
        </nav>
    </header>

    <section class="hero">
        <h1>About HeartHealth</h1>
        <p>Your trusted companion for heart health insights. Our team combines cutting-edge technology with medical expertise to provide personalized guidance for maintaining a healthy heart.</p>
    </section>

    <div class="container">
        <div class="card">
            <h2>Our Mission</h2>
            <p>HeartHealth empowers individuals with easy-to-understand heart health assessments. We provide data-driven guidance that helps you make informed lifestyle and medical decisions.</p>
        </div>

        <div class="card">
            <h2>Professional Insights</h2>
            <p>Our predictor uses data-driven methods trusted by medical professionals to provide accurate heart health guidance. We ensure our recommendations are based on reliable research and real-world clinical data.</p>
        </div>

        <div class="card">
            <h2>Why Choose Us?</h2>
            <p>We bridge the gap between complex medical information and patients’ understanding. Our tools are designed for anyone, regardless of medical knowledge, to easily interpret their heart health status.</p>
        </div>

        <div class="card">
            <h2>Meet the Team</h2>
            <p>Our multidisciplinary team includes cardiologists, data scientists, and healthcare professionals who collaborate to ensure the accuracy and usability of our heart health platform. Your heart’s well-being is our top priority.</p>
        </div>
    </div>

    <footer>
        &copy; 2025 HeartHealth. All Rights Reserved.
    </footer>
</body>
</html>

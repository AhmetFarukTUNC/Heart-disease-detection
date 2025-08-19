<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Veritabanı bağlantısı
$host = "localhost";
$dbname = "heart_health";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// Tahminleri çek
$stmt = $pdo->prepare("SELECT * FROM predictions WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$predictions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Predictions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #1e3a8a;
            font-size: 36px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        thead {
            background: linear-gradient(90deg, #3b82f6, #2563eb);
            color: #fff;
        }
        thead th {
            padding: 15px;
            font-weight: 700;
            text-align: left;
        }
        tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background 0.3s;
        }
        tbody tr:hover {
            background: #f0f9ff;
        }
        tbody td {
            padding: 12px 15px;
            color: #1f2937;
        }
        .prediction-var {
            background-color: #fee2e2;
            color: #b91c1c;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 8px;
            display: inline-block;
        }
        .prediction-yok {
            background-color: #d1fae5;
            color: #065f46;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 8px;
            display: inline-block;
        }
        @media (max-width: 1024px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tbody tr {
                margin-bottom: 20px;
                border-bottom: 2px solid #e5e7eb;
            }
            tbody td {
                position: relative;
                padding-left: 50%;
                text-align: left;
            }
            tbody td::before {
                position: absolute;
                left: 15px;
                width: 45%;
                white-space: nowrap;
                font-weight: 600;
                content: attr(data-label);
                color: #374151;
            }
        }


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

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #2563eb; /* Resimdeki koyu mavi */
    padding: 15px 30px;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
    position: relative;
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
        background: #2563eb;
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

/* Kullanıcı dropdown */
.user-dropdown .dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: #f63b3b;
    min-width: 120px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    z-index: 10;
}
.user-dropdown:hover .dropdown-content {
    display: block;
}
.user-dropdown .dropdown-content li a {
    display: block;
    padding: 10px;
    color: #fff;
    text-decoration: none;
}
.user-dropdown .dropdown-content li a:hover {
    background-color: #eb2525;
}




    </style>
</head>
<body>
    <header>
    <nav class="navbar">
        <a href="homepage.php" class="logo">HeartHealth</a>
        <span class="menu-toggle" onclick="toggleMenu()">&#9776;</span>
        <ul id="nav-links">
            <li><a href="homepage.php">Home</a></li>
            <li><a href="disease.php">Could I Have Heart Disease?</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="communication.php">Contact</a></li>
            <li><a href="listpatient.php">List Patient Results</a></li>
            <?php

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$username2 = $_SESSION['username'];


?>
            <li class="user-dropdown">
                <a href="javascript:void(0)">Hello, <?= htmlspecialchars($username2) ?></a>
                <ul class="dropdown-content">
                    <li><a href="logout.php">Çıkış Yap</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>

<script>
function toggleMenu() {
    const navLinks = document.getElementById('nav-links');
    navLinks.classList.toggle('show');
}
</script>

    <div class="container">
        <h1>My Heart Health Predictions</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Chest Pain</th>
                    <th>Resting BP</th>
                    <th>Cholesterol</th>
                    <th>Fasting BS</th>
                    <th>Resting ECG</th>
                    <th>Max HR</th>
                    <th>Exercise Angina</th>
                    <th>Oldpeak</th>
                    <th>ST Slope</th>
                    <th>Prediction</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($predictions as $row): ?>
                    <tr>
                        <td data-label="ID"><?= htmlspecialchars($row['id']) ?></td>
                        <td data-label="Age"><?= htmlspecialchars($row['age']) ?></td>
                        <td data-label="Sex"><?= htmlspecialchars($row['sex']) ?></td>
                        <td data-label="Chest Pain"><?= htmlspecialchars($row['chestpain']) ?></td>
                        <td data-label="Resting BP"><?= htmlspecialchars($row['restingbp']) ?></td>
                        <td data-label="Cholesterol"><?= htmlspecialchars($row['cholesterol']) ?></td>
                        <td data-label="Fasting BS"><?= htmlspecialchars($row['fastingbs']) ?></td>
                        <td data-label="Resting ECG"><?= htmlspecialchars($row['restingecg']) ?></td>
                        <td data-label="Max HR"><?= htmlspecialchars($row['maxhr']) ?></td>
                        <td data-label="Exercise Angina"><?= htmlspecialchars($row['exerciseangina']) ?></td>
                        <td data-label="Oldpeak"><?= htmlspecialchars($row['oldpeak']) ?></td>
                        <td data-label="ST Slope"><?= htmlspecialchars($row['st_slope']) ?></td>
                        <td data-label="Prediction">
                            <?php if($row['prediction'] === 'var'): ?>
                                <span class="prediction-var">Var</span>
                            <?php else: ?>
                                <span class="prediction-yok">Yok</span>
                            <?php endif; ?>
                        </td>
                        <td data-label="Created At"><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

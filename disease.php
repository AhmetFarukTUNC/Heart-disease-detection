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
    <title>Heart Health Predictor</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 0;
        }
        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #3b82f6;
            padding: 15px 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar .logo {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
        }
        .navbar ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }
        .navbar ul li {
            margin-left: 25px;
        }
        .navbar ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: 600;
            transition: 0.3s;
            position: relative;
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

        /* Container & Cards */
        .container {
            max-width: 750px;
            width: 100%;
            margin: 40px auto;
            padding: 0 20px;
        }
        .card, .explanation-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            margin-bottom: 30px;
        }
        h1 {
            text-align: center;
            color: #1f2937;
            margin-bottom: 30px;
            font-size: 28px;
        }
        label {
            display: block;
            margin-bottom: 15px;
            font-weight: 600;
            color: #334155;
        }
        input, select {
            width: 100%;
            padding: 12px 15px;
            margin-top: 5px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 16px;
        }
        input:focus, select:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 8px rgba(59,130,246,0.3);
        }
        button {
            width: 100%;
            padding: 15px;
            background: #3b82f6;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            margin-top: 25px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #2563eb;
        }

        /* Result Box */
        #result {
            margin-top: 25px;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            font-size: 18px;
            font-weight: 600;
        }
        #result.safe {
            background-color: #dcfce7;
            color: #166534;
        }
        #result.warning {
            background-color: #fef3c7;
            color: #78350f;
        }

        .hint {
            font-size: 13px;
            color: #64748b;
        }
        .explanation-card h2 {
            font-size: 22px;
            margin-bottom: 20px;
            text-align: center;
            color: #1e40af;
        }
        .explanation-card ul {
            list-style: none;
            padding: 0;
        }
        .explanation-card li {
            margin-bottom: 15px;
            font-size: 16px;
            color: #334155;
            line-height: 1.5;
        }
        .explanation-card span {
            font-weight: 600;
            color: #1f2937;
        }
        .lang-label {
            font-size: 14px;
            font-style: italic;
            color: #64748b;
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

    <!-- Container -->
    <div class="container">
        <div class="card">
            <h1>Heart Health Predictor</h1>
            <form id="predictionForm">
                <label>Age: <input type="number" name="Age" required></label>
                <label>Gender:
                    <select name="Sex" required>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </label>
                <label>Chest Pain Type:
                    <select name="ChestPainType" required>
                        <option value="TA">Mild / Activity related</option>
                        <option value="ATA">Moderate / At rest</option>
                        <option value="NAP">Frequent / Discomfort</option>
                        <option value="ASY">No symptoms / Silent</option>
                    </select>
                    <div class="hint">Select the type of chest discomfort you experience.</div>
                </label>
                <label>Resting Blood Pressure (mmHg): <input type="number" name="RestingBP" required></label>
                <label>Cholesterol (mg/dL): <input type="number" name="Cholesterol" required></label>
                <label>Fasting Blood Sugar:
                    <select name="FastingBS" required>
                        <option value="0">Normal</option>
                        <option value="1">High</option>
                    </select>
                    <div class="hint">Blood sugar measured after fasting.</div>
                </label>
                <label>Resting ECG Result:
                    <select name="RestingECG" required>
                        <option value="Normal">Normal</option>
                        <option value="ST">ST change</option>
                        <option value="LVH">Left ventricular hypertrophy</option>
                    </select>
                    <div class="hint">Choose according to your ECG report.</div>
                </label>
                <label>Maximum Heart Rate: <input type="number" name="MaxHR" required></label>
                <label>Chest Pain During Exercise:
                    <select name="ExerciseAngina" required>
                        <option value="N">No</option>
                        <option value="Y">Yes</option>
                    </select>
                    <div class="hint">Did you experience chest discomfort during activity?</div>
                </label>
                <label>ST Depression (Oldpeak): <input type="number" step="0.1" name="Oldpeak" required></label>
                <label>ST Slope:
                    <select name="ST_Slope" required>
                        <option value="Up">Upward</option>
                        <option value="Flat">Flat</option>
                        <option value="Down">Downward</option>
                    </select>
                    <div class="hint">ST segment slope from your ECG report.</div>
                </label>
                <button type="submit">Predict</button>
            </form>
            <div id="result"></div>
        </div>

        <!-- Explanations -->
        <div class="explanation-card">
            <h2>Input Explanations / Girdi Açıklamaları</h2>
            <ul>
                <li><span>Age / Yaş:</span> Your age in years. / Yaşınız (yıl).</li>
                <li><span>Gender / Cinsiyet:</span> Male or Female. / Erkek veya Kadın.</li>
                <li><span>Chest Pain Type / Göğüs Ağrısı Türü:</span> 
                    TA - Mild / Activity related / Hafif, genellikle aktivite sırasında<br>
                    ATA - Moderate / At rest / Orta şiddette, bazen dinlenirken<br>
                    NAP - Frequent / Discomfort / Daha sık ve rahatsız edici<br>
                    ASY - No symptoms / Silent / Göğüs ağrısı yok, kalp sorunları sessiz olabilir
                </li>
                <li><span>Resting Blood Pressure / Dinlenme Kan Basıncı:</span> Measured in mmHg. / mmHg cinsinden ölçülür.</li>
                <li><span>Cholesterol / Kolesterol:</span> Blood cholesterol level in mg/dL. / Kan kolesterol seviyesi mg/dL.</li>
                <li><span>Fasting Blood Sugar / Açlık Kan Şekeri:</span> 0 = Normal, 1 = High / 0 = Normal, 1 = Yüksek.</li>
                <li><span>Resting ECG Result / Dinlenme EKG Sonucu:</span> Normal, ST change or LVH / Normal, ST değişimi veya LVH.</li>
                <li><span>Maximum Heart Rate / Maksimum Kalp Hızı:</span> Highest heart rate measured. / Ölçülen en yüksek kalp hızı.</li>
                <li><span>Chest Pain During Exercise / Egzersiz Sırasında Göğüs Ağrısı:</span> Yes or No. / Evet veya Hayır.</li>
                <li><span>ST Depression (Oldpeak) / ST Depresyonu:</span> Numeric value from ECG. / EKG’den alınan sayısal değer.</li>
                <li><span>ST Slope / ST Eğimi:</span> Upward, Flat, Downward / Yukarı, Düz, Aşağı.</li>
            </ul>
        </div>
    </div>

    <script>
        // Navbar toggle
        const toggle = document.querySelector('.menu-toggle');
        const menu = document.querySelector('.navbar ul');
        toggle.addEventListener('click', () => { menu.classList.toggle('show'); });

        // Prediction
        const form = document.getElementById('predictionForm');
        const resultDiv = document.getElementById('result');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const features = [
                Number(formData.get('Age')),
                formData.get('Sex'),
                formData.get('ChestPainType'),
                Number(formData.get('RestingBP')),
                Number(formData.get('Cholesterol')),
                Number(formData.get('FastingBS')),
                formData.get('RestingECG'),
                Number(formData.get('MaxHR')),
                formData.get('ExerciseAngina'),
                Number(formData.get('Oldpeak')),
                formData.get('ST_Slope')
            ];
            try {
                const response = await fetch('prediction.php', { // eski http://127.0.0.1:5000/predict yerine
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ features })
});

                const data = await response.json();
                if (data.prediction !== undefined) {
                    if(data.prediction === "yok"){
                        resultDiv.textContent = "Your heart health looks good. Keep regular check-ups.";
                        resultDiv.className = "safe";
                    } else if(data.prediction === "var"){
                        resultDiv.textContent = "Some risks detected. Please consult your doctor and follow medical advice.";
                        resultDiv.className = "warning";
                    }
                } else {
                    resultDiv.textContent = "Error: " + data.error;
                    resultDiv.className = "";
                }
            } catch (err) {
                resultDiv.textContent = "Error: " + err;
                resultDiv.className = "";
            }
        });
    </script>
</body>
</html>

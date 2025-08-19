<?php

require "veritabani_baglanti.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if (!$username || !$password || !$password2) {
        $message = "Tüm alanları doldurunuz.";
    } elseif ($password !== $password2) {
        $message = "Parolalar eşleşmiyor.";
    } else {
        // Kullanıcı adı var mı kontrol et
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username=?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $message = "Bu kullanıcı adı zaten kullanılıyor.";
        } else {
            // Şifreyi hashle ve kaydet
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (username,password) VALUES (?,?)");
            $insert->execute([$username,$hashed]);
            $message = "Kayıt başarılı! Giriş yapabilirsiniz.";
            header("Refresh:2; url=index.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Kayıt Ol - Kalp Hastalığı Tahmini</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #76b852, #8DC26F);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}
.container {
    background: #fff;
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    width: 350px;
    text-align: center;
}
h2 {
    color: #76b852;
    margin-bottom: 25px;
}
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 16px;
}
button {
    width: 100%;
    padding: 12px;
    margin-top: 15px;
    border: none;
    border-radius: 10px;
    background: #76b852;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}
button:hover { background: #5a9a3f; }
.message { color: red; font-weight: bold; margin-bottom: 15px; }

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
    overflow: hidden;
}

.container {
    position: relative;
    width: 400px;
    padding: 50px 40px;
    border-radius: 30px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(25px);
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    text-align: center;
    overflow: hidden;
    z-index: 1;
}

.container::before, .container::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    background: linear-gradient(45deg, #ff6a00, #ee0979, #8e2de2, #4a00e0);
    animation: rotate 20s linear infinite;
    opacity: 0.6;
}

.container::before {
    width: 350px;
    height: 350px;
    top: -120px;
    left: -120px;
}

.container::after {
    width: 250px;
    height: 250px;
    bottom: -100px;
    right: -80px;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

h2 {
    color: #fff;
    font-size: 34px;
    margin-bottom: 35px;
    font-weight: 700;
    text-shadow: 0 0 20px rgba(255,255,255,0.3);
}

input {
    width: 100%;
    padding: 16px;
    margin: 14px 0;
    border-radius: 20px;
    border: none;
    background: rgba(255,255,255,0.1);
    color: #fff;
    font-size: 16px;
    outline: none;
    box-shadow: inset 0 0 15px rgba(255,255,255,0.2);
    transition: all 0.4s ease;
}

input::placeholder {
    color: rgba(255,255,255,0.7);
}

input:focus {
    background: rgba(255,255,255,0.2);
    box-shadow: inset 0 0 18px rgba(255,255,255,0.5);
}

button {
    width: 100%;
    padding: 18px;
    margin-top: 25px;
    border: none;
    border-radius: 25px;
    background: linear-gradient(135deg, #ff6a00, #ee0979);
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3), 0 0 15px rgba(255,105,180,0.6);
    transition: all 0.3s ease;
}

button:hover {
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 15px 40px rgba(0,0,0,0.4), 0 0 25px rgba(255,105,180,0.9);
}

.message {
    color: #ff3860;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 0 0 10px rgba(255,56,96,0.8);
    animation: fadeIn 1s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-15px); }
    to { opacity: 1; transform: translateY(0); }
}

a {
    color: #ff6a00;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

a:hover { 
    color: #ee0979;
    text-decoration: underline;
}

</style>
</head>
<body>
<div class="container">
<h2>Kayıt Ol</h2>
<?php if($message): ?>
<div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<form method="post">
    <input type="text" name="username" placeholder="Kullanıcı Adı" required>
    <input type="password" name="password" placeholder="Parola" required>
    <input type="password" name="password2" placeholder="Parolayı Tekrar" required>
    <button type="submit">Kayıt Ol</button>
</form>
<p style="margin-top:10px;">Zaten hesabınız var mı? <a href="login.php">Giriş Yap</a></p>
</div>
</body>
</html>

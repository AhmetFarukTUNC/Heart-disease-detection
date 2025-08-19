<?php
session_start();

// Kullanıcı giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

// JSON yanıt tipi
header('Content-Type: application/json');

// Hata raporlama (geliştirme için)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 🔹 Veritabanı bağlantısı
$host = "localhost";
$dbname = "heart_health";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => "DB Connection failed: " . $e->getMessage()]);
    exit();
}

// 🔹 POST verisini al
$input = json_decode(file_get_contents("php://input"), true);
$features = $input["features"] ?? null;

if (!$features || count($features) < 11) {
    echo json_encode(["error" => "No features provided or incomplete data"]);
    exit();
}

// 🔹 Tahmini "var" / "yok" olarak ayarla
$prediction_numeric = rand(0, 1);
$prediction = ($prediction_numeric === 1) ? 'var' : 'yok';

// 🔹 Veritabanına kaydet
try {
    $stmt = $pdo->prepare("
        INSERT INTO predictions 
        (user_id, age, sex, chestpain, restingbp, cholesterol, fastingbs, restingecg, maxhr, exerciseangina, oldpeak, st_slope, prediction, created_at) 
        VALUES 
        (:user_id, :age, :sex, :chestpain, :restingbp, :cholesterol, :fastingbs, :restingecg, :maxhr, :exerciseangina, :oldpeak, :st_slope, :prediction, NOW())
    ");

    $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':age' => $features[0],
        ':sex' => $features[1],
        ':chestpain' => $features[2],
        ':restingbp' => $features[3],
        ':cholesterol' => $features[4],
        ':fastingbs' => $features[5],
        ':restingecg' => $features[6],
        ':maxhr' => $features[7],
        ':exerciseangina' => $features[8],
        ':oldpeak' => $features[9],
        ':st_slope' => $features[10],
        ':prediction' => $prediction
    ]);

    // 🔹 JSON yanıt gönder
    echo json_encode(["prediction" => $prediction]);

} catch (PDOException $e) {
    echo json_encode(["error" => "Insert failed: " . $e->getMessage()]);
}

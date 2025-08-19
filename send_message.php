<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor\phpmailer\phpmailer\src/Exception.php';
require 'vendor\phpmailer\phpmailer\src/PHPMailer.php';
require 'vendor\phpmailer\phpmailer\src/SMTP.php';

// Form verilerini al
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

$mail = new PHPMailer(true);

try {
    // SMTP Ayarları
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'atunc8402@gmail.com';
    $mail->Password = 'jqtc lvkw eaaz edky'; // uygulama şifresi
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Gönderen ve Alıcı
    $mail->setFrom($email, $name);
    $mail->addAddress("atunc8402@gmail.com");

    // İçerik
    $mail->isHTML(true);
    $mail->Subject = $subject ?: 'Yeni Mesaj';
    $mail->Body    = "İsim: $name <br> E-posta: $email <br> Mesaj: $message";

    $mail->send();

    // Başarılı ise yönlendir
    header("Location: communication.php?status=success");
    exit;

} catch (Exception $e) {
    // Hata varsa yönlendir ve hata mesajı ile
    header("Location: communication.php?status=error&message=" . urlencode($mail->ErrorInfo));
    exit;
}
?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $date = trim($_POST["date"]);
    $subject = trim($_POST["subject"]);
    $phone = trim($_POST["phone"]);

    if (empty($name) || empty($email) || empty($subject) || empty($date) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please complete the form correctly.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com'; // Servidor SMTP de Microsoft Outlook
        $mail->SMTPAuth = true;
        $mail->Username = 'orlando.gallegos@melysjanitorial.com'; // Tu correo empresarial
        $mail->Password = 'rckxdbvsmmtpjlxs'; // La contraseña de aplicación generada
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('orlando.gallegos@melysjanitorial.com', 'Mely’s Janitorial Contact');
        $mail->addAddress('orlando.gallegos@melysjanitorial.com'); // Donde recibirás los mensajes
        $mail->addReplyTo($email, $name);

        $mail->Subject = "New Contact Form Submission";
        $mail->Body = "Name: $name\nPhone: $phone\nEmail: $email\nDate: $date\nSubject: $subject";

        $mail->send();
        http_response_code(200);
        echo "Thank you! Your message has been sent.";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Error sending the message: {$mail->ErrorInfo}";
    }
} else {
    http_response_code(403);
    echo "Invalid request.";
}
?>

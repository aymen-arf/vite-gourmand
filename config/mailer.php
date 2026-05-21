<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function envoyerMail($destinataire, $nom, $sujet, $html, $texte = '') {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'aymenarfaoui87@gmail.com';
        $mail->Password = 'myyo zhtr bvyu lwnl';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('aymenarfaoui87@gmail.com', 'Vite & Gourmand');
        $mail->addAddress($destinataire, $nom);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $sujet;
        $mail->Body = $html;
        $mail->AltBody = $texte ?: strip_tags($html);

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
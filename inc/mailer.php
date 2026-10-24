<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Adjust paths based on your current folder structure.
// Using the downloaded ZIP folder found at: PHPMailer-master/src/
require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';

function send_mail($toEmail, $toName, $subject, $htmlBody, $altBody = '', $embeds = [])
{
  $cfg = require __DIR__ . '/../mail/mail_config.php';

  if ($cfg['user'] === '' || $cfg['pass'] === '') {
    error_log('Mail not sent: SMTP_USERNAME and SMTP_PASSWORD must be configured.');
    return false;
  }

  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = $cfg['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $cfg['user'];
    $mail->Password = $cfg['pass'];
    $mail->SMTPSecure = $cfg['secure']; // 'tls' or 'ssl'
    $mail->Port = $cfg['port'];
    $mail->CharSet = 'UTF-8';

    $mail->setFrom($cfg['from_email'], $cfg['from_name']);
    $mail->addAddress($toEmail, $toName);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $htmlBody;
    $mail->AltBody = $altBody ?: strip_tags($htmlBody);

    // Embed inline images if provided: each item ['path' => full_path, 'cid' => 'cidname']
    if (is_array($embeds)) {
      foreach ($embeds as $em) {
        if (!empty($em['path']) && !empty($em['cid']) && file_exists($em['path'])) {
          $mail->addEmbeddedImage($em['path'], $em['cid']);
        }
      }
    }

    $mail->send();
    return true;
  } catch (Exception $e) {
    error_log('Mail error: ' . $mail->ErrorInfo);
    return false;
  }
}

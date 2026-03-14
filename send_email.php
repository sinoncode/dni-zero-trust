<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

header('Content-Type: application/json');

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$company = $_POST['company'] ?? '';
$phone = $_POST['phone'] ?? '';

$mail = new PHPMailer(true);

try {

    /* SMTP DEBUGGING */
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
    $mail->Debugoutput = function($str, $level) {
        echo "SMTP DEBUG: $str<br>";
    };

    $mail->isSMTP();
    $mail->Host       = 'smtp.office365.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'support@networsys.com';
    $mail->Password   = 'jdnwsknsdmyhynww';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('support@networsys.com', 'Networsys Website');
    $mail->addAddress('dwarren@dniservicesllc.com');

    /* Reply to user */
    if($email){
        $mail->addReplyTo($email, $name);
    }

    $mail->isHTML(true);
    $mail->Subject = 'New Zero Trust Assessment Submission';

   $mail->Body = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8;padding:30px 0;">
<tr>
<td align="center">

<table width="520" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #e5e5e5;">

<!-- Logo -->
<tr>
<td align="center" style="padding:25px 20px;background:#fff8e0;">
<img src="https://www.dniservicesllc.com/uploads/logo/1750871616_logo.png" alt="DNI Services" style="height:70px;">
</td>
</tr>

<!-- Header -->
<tr>
<td style="padding:25px 30px 10px 30px;">
<h2 style="margin:0;color:#1a2a33;font-size:22px;">New Assessment Lead</h2>
<p style="margin:5px 0 0 0;color:#6b8a9b;font-size:14px;">A new lead has been submitted through the website.</p>
</td>
</tr>

<!-- Divider -->
<tr>
<td style="padding:0 30px;">
<hr style="border:none;border-top:1px solid #eee;">
</td>
</tr>

<!-- Lead Details -->
<tr>
<td style="padding:20px 30px 30px 30px;">

<table width="100%" cellpadding="8" cellspacing="0" style="font-size:15px;color:#333;">

<tr>
<td style="font-weight:bold;width:120px;color:#555;">Name</td>
<td style="background:#f9fbfd;border-radius:5px;">'.$name.'</td>
</tr>

<tr>
<td style="font-weight:bold;color:#555;">Email</td>
<td style="background:#f9fbfd;border-radius:5px;">'.$email.'</td>
</tr>

<tr>
<td style="font-weight:bold;color:#555;">Company</td>
<td style="background:#f9fbfd;border-radius:5px;">'.$company.'</td>
</tr>

<tr>
<td style="font-weight:bold;color:#555;">Phone</td>
<td style="background:#f9fbfd;border-radius:5px;">'.$phone.'</td>
</tr>

</table>

</td>
</tr>

<!-- Footer -->
<tr>
<td align="center" style="padding:15px 20px;background:#fafafa;font-size:12px;color:#999;">
This message was generated from the website assessment form.
</td>
</tr>

</table>

</td>
</tr>
</table>

</body>
</html>
';

    $mail->send();

    echo json_encode([
        "success" => true,
        "message" => "Email sent successfully"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "error" => $mail->ErrorInfo,
        "exception" => $e->getMessage()
    ]);
}
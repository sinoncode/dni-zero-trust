<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'config.php';

header('Content-Type: application/json');
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$company = $_POST['company'] ?? '';
$phone = $_POST['phone'] ?? '';
$message = $_POST['message'] ?? '';

$mail = new PHPMailer(true);

try {

    // Log SMTP debug to file instead of outputting to response (avoids breaking JSON)
    $mail->SMTPDebug  = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = function($str, $level) {
        file_put_contents('/tmp/smtp_debug.log', date('[Y-m-d H:i:s] ') . $str . "\n", FILE_APPEND);
    };

    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USERNAME;
    $mail->Password   = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_ENCRYPTION;
    $mail->Port       = SMTP_PORT;

    $mail->setFrom(FROM_EMAIL, FROM_NAME);
    $mail->addAddress(TO_EMAIL);

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
<img src="cid:networsyslogo" alt="Networsys" style="height:70px;">
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

<tr style="margin-top:1rem;">
<td style="font-weight:bold;width:120px;color:#555;">Name</td>
<td style="background:#f9fbfd;border-radius:5px;">'.htmlspecialchars($name).'</td>
</tr>

<tr style="margin-top:1rem;">
<td style="font-weight:bold;color:#555;">Email</td>
<td style="background:#f9fbfd;border-radius:5px;">'.htmlspecialchars($email).'</td>
</tr>

<tr style="margin-top:1rem;">
<td style="font-weight:bold;color:#555;">Company</td>
<td style="background:#f9fbfd;border-radius:5px;">'.htmlspecialchars($company).'</td>
</tr>

<tr style="margin-top:1rem;">
<td style="font-weight:bold;color:#555;">Phone</td>
<td style="background:#f9fbfd;border-radius:5px;">'.htmlspecialchars($phone).'</td>
</tr>

<tr style="margin-top:1rem;">
<td style="font-weight:bold;color:#555;">Message</td>
<td style="background:#f9fbfd;border-radius:5px;">'.htmlspecialchars($message).'</td>
</tr>

</table>

</td>
</tr>

<!-- CTA Button (Optional Improvement) -->
<tr>
<td align="center" style="padding:10px 30px 25px 30px;">
<a href="mailto:'.htmlspecialchars($email).'" style="background:#f7931e;color:#ffffff;padding:10px 20px;border-radius:6px;text-decoration:none;font-size:14px;">Reply to Lead</a>
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

    // Handle PDF Attachment
    // if (isset($_POST['pdf']) && !empty($_POST['pdf'])) {
    //     // The PDF is sent as a base64 string (data URI)
    //     $pdfData = $_POST['pdf'];
        
    //     // Remove the data URI scheme prefix if present
    //     if (strpos($pdfData, 'data:application/pdf;base64,') === 0) {
    //         $pdfData = substr($pdfData, strpos($pdfData, ',') + 1);
    //     }
        
    //     // Decode the base64 string
    //     $decodedPdf = base64_decode($pdfData);
        
    //     if ($decodedPdf !== false) {
    //         // Attach the decoded PDF string directly
    //         $mail->addStringAttachment($decodedPdf, 'Zero_Trust_Assessment_Report.pdf', 'base64', 'application/pdf');
    //     }
    // }

    // Embed the local logo image
    if (file_exists('networsys-logo.png')) {
        $mail->addEmbeddedImage('networsys-logo.png', 'networsyslogo');
    }

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
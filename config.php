<?php
use PHPMailer\PHPMailer\PHPMailer;
// Email configuration
define('SMTP_HOST', 'smtp.office365.com');
define('SMTP_USERNAME', 'support@networsys.com');
define('SMTP_PASSWORD', 'jdnwsknsdmyhynww');
define('SMTP_PORT', 587);
define('SMTP_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS);

define('FROM_EMAIL', 'support@networsys.com');
define('FROM_NAME', 'Networsys Website');
define('TO_EMAIL', 'dwarren@dniservicesllc.com');
?>
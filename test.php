<?php
$to = "tawhid8995@gmail.com";
$subject = "Test Email";
$message = "This is a test email sent using PHP's mail() function.";
$headers = "From: no-reply@khanapina.bdhscanada.com";

// Send email
if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully.";
} else {
    echo "Email sending failed.";
}
?>

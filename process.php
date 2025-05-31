<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'config.php';

function sanitize_input($data) {
    return htmlspecialchars(trim(stripslashes($data)));
}

// Verify reCAPTCHA
$secret = RECAPTCHA_SECRET_KEY;
$response = $_POST['g-recaptcha-response'];

$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = [
    'secret' => $secret,
    'response' => $response
];

$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    ]
];

$context = stream_context_create($options);
$result = json_decode(file_get_contents($url, false, $context));

if ($result->success) {
    // Process form data
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $message = sanitize_input($_POST['message']);

    // Email configurationj.robinsonelectrician93@gmail.com
    $to = 'willrichardson182@gmail.com, j.robinsonelectrician93@gmail.com'; 
    $subject = 'New Message Received for Alnwick Care';
    
    $headers = "From: Alnwick Care <william@alnwickcare.co.uk>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $body = "
        <html>
        <body>
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone:</strong> $phone</p>
            <p><strong>Message:</strong> $message</p>
        </body>
        </html>
    ";

    if (mail($to, $subject, $body, $headers)) {
        $alert = ['type' => 'success', 'message' => 'Form submitted successfully!'];
    } else {
        $alert = ['type' => 'danger', 'message' => 'Error sending email. Please call us!'];
    }
} else {
    $alert = ['type' => 'danger', 'message' => 'reCAPTCHA verification failed. Please try again.'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Form Submission</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-<?= $alert['type'] ?>">
            <?= $alert['message'] ?>
        </div>
        <a href="index.html" class="btn btn-primary">Return Home</a>
    </div>
</body>
</html>
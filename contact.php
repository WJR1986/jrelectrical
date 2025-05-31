<?php
// contact.php

// 1. Load secret key (outside web‑root)
$dotenv = parse_ini_file(__DIR__ . '/../.env');
define('RECAPTCHA_SECRET_KEY', $dotenv['RECAPTCHA_SECRET_KEY']);

// 2. Helper to POST to Google
function verify_recaptcha($token) {
  $url = 'https://www.google.com/recaptcha/api/siteverify';
  $data = http_build_query([
    'secret'   => RECAPTCHA_SECRET_KEY,
    'response' => $token,
    'remoteip' => $_SERVER['REMOTE_ADDR']
  ]);
  $opts = ['http' => [
    'method'  => 'POST',
    'header'  => 'Content-type: application/x-www-form-urlencoded',
    'content' => $data
  ]];
  $context = stream_context_create($opts);
  $result  = file_get_contents($url, false, $context);
  return json_decode($result, true);
}

// Always return JSON format
function json_response($success, $message, $http_status = 200) {
    http_response_code($http_status);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 3. Check reCAPTCHA
  $recaptcha = verify_recaptcha($_POST['g-recaptcha-response'] ?? '');
  if (!($recaptcha['success'] ?? false) || ($recaptcha['score'] ?? 1) < 0.5) {
    json_response(false, 'reCAPTCHA verification failed. Please try again.', 400);
  }

  // 4. Sanitize user inputs
  $name    = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  $email   = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
  $message = htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8');

  if (!$name || !$email || !$message) {
    json_response(false, 'Please complete all fields correctly.', 400);
  }

  // 5. Send email
  $to      = 'willrichardson182@gmail.com, william@alnwickcare.co.uk'; // <-- Update to your real email
  $subject = "New message from $name";
  $body    = "Name: $name\nEmail: $email\n\nMessage:\n$message\n";
  $headers = "From: william@alnwickcare.co.uk\r\n"
           . "Reply-To: $email\r\n";

  if (mail($to, $subject, $body, $headers)) {
    error_log("Email attempted to send to: $to | Subject: $subject");
    json_response(true, 'Thank you! Your message has been sent.');
  } else {
    error_log("Email failed to send. Details: " . print_r(error_get_last(), true));
    json_response(false, 'Sorry, something went wrong sending your message.', 500);
  }
} else {
  json_response(false, 'Method Not Allowed', 405);
}

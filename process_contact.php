<?php
// Set headers for JSON response
header('Content-Type: application/json');

// Initialize response array
$response = array(
    'success' => false,
    'message' => ''
);

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Validate form data
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $response['message'] = 'All fields are required.';
        echo json_encode($response);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address.';
        echo json_encode($response);
        exit;
    }
    
    // Set recipient email (your email)
    $to = 'abredagn.kebede@example.com';
    
    // Set email headers
    $headers = "From: $name <$email>" . "\r\n";
    $headers .= "Reply-To: $email" . "\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    
    // Build email content
    $email_content = "
    <html>
    <head>
        <title>New Contact Form Submission</title>
    </head>
    <body>
        <h2>New Contact Form Submission</h2>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Subject:</strong> $subject</p>
        <p><strong>Message:</strong></p>
        <p>" . nl2br($message) . "</p>
    </body>
    </html>
    ";
    
    // Send email
    $mail_sent = mail($to, "Contact Form: $subject", $email_content, $headers);
    
    if ($mail_sent) {
        // Email sent successfully
        $response['success'] = true;
        $response['message'] = 'Your message has been sent successfully!';
    } else {
        // Email sending failed
        $response['message'] = 'Sorry, there was an error sending your message. Please try again later.';
        
        // Alternative: Save to file if mail function fails
        $log_file = 'contact_submissions.txt';
        $log_entry = date('Y-m-d H:i:s') . " - Name: $name, Email: $email, Subject: $subject, Message: $message\n\n";
        file_put_contents($log_file, $log_entry, FILE_APPEND);
        
        $response['success'] = true; // Still return success to user
        $response['message'] = 'Your message has been received. I\'ll get back to you soon!';
    }
} else {
    // Not a POST request
    $response['message'] = 'Invalid request method.';
}

// Return JSON response
echo json_encode($response);
?>
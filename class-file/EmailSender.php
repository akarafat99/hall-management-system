<?php
// Include the PHPMailer classes.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Dynamically get the document root.
$root = $_SERVER['DOCUMENT_ROOT'];

// Construct the paths from the document root.
require_once $root . '/php-mail/PHPMailer.php';
require_once $root . '/php-mail/Exception.php';
require_once $root . '/php-mail/SMTP.php';

class EmailSender
{
    private $mail;

    /**
     * Constructor initializes PHPMailer with SMTP settings.
     */
    public function __construct()
    {
        // Create a new PHPMailer instance with exceptions enabled.
        $this->mail = new PHPMailer(true);

        // Use SMTP to send email.
        $this->mail->isSMTP();

        // SMTP server configuration.
        $this->mail->Host       = 'smtp.gmail.com';   // Specify main and backup SMTP servers.
        $this->mail->SMTPAuth   = true;               // Enable SMTP authentication.
        $this->mail->Username   = '190122.cse@student.just.edu.bd'; // SMTP username.
        $this->mail->Password   = 'mubs fjpo zrvg gadb';            // SMTP password.
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    // Enable TLS encryption.
        $this->mail->Port       = 587;                // TCP port to connect to.

        // Optionally, set a default sender (the From address).
        $this->mail->setFrom('190122.cse@student.just.edu.bd', 'JUST MM Hall'); // Sender's email and name.
    }

    /**
     * Sends an email using the configured SMTP settings.
     *
     * @param string $receiver The recipient's email address.
     * @param string $subject  The subject of the email.
     * @param string $body     The HTML body of the email.
     * @return bool Returns true if the email was sent successfully, false otherwise.
     */
    public function sendMail($receiver, $subject, $body)
    {
        try {
            // Clear previous recipients and attachments if the function is called multiple times.
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();

            // Add the recipient's email address.
            $this->mail->addAddress($receiver);

            // Set email format to HTML.
            $this->mail->isHTML(true);

            // Set the email subject and body.
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;

            // Send the email.
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // You can log the error message using $this->mail->ErrorInfo for debugging.
            return false;
        }
    }
}



// $emailSender = new EmailSender();

// // Set up email parameters.
// $receiver = 'tasnim.arisha1823@gmail.com';
// $subject  = 'Test Email Subject';
// $body     = '<p>This is a <strong>test email</strong> sent from our PHP application.</p>';

// // Call the sendMail function.
// if ($emailSender->sendMail($receiver, $subject, $body)) {
//     echo 'Email has been sent successfully!';
// } else {
//     echo 'Failed to send the email.';
// }

?>

<!-- end -->
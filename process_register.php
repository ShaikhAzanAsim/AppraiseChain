<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="log_reg.css">
</head>
<body>

<?php
include 'connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $cnic = $_POST["cnic"];
    $add = $_POST["address"];
    $organization = $_POST["organization_name"];

    // Password generation
    function generateRandomPassword($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
        $charLength = strlen($characters);
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charLength - 1)];
        }
        return $password;
    }
    $p = generateRandomPassword(10);
    $passwordHash = password_hash($p, PASSWORD_BCRYPT);

    // Gender detection
    $lastCharacter = substr($cnic, -1);
    $gender = ($lastCharacter % 2 == 0) ? 'F' : 'M';


    // Check if the email already exists
    $stmt = $conn->prepare("SELECT cnic FROM hod WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();



    if ($stmt->num_rows > 0) {
        ?>
        <div class="container">
            <h1> EMAIL ALREADY EXISTS </h1>
            <p>Email already exists. Please choose a different one. <a href='log_reg.php'>Back</a></p>
        </div>
        <?php
    } else {


        // Insert the user data into the database
        $stmt = $conn->prepare("INSERT INTO hod (CNIC,organization_id, fname, lname,address, email, password, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssss", $cnic, $organization, $fname,$lname, $add, $email,$passwordHash,$gender);

        

        require 'PHPMailer-master\src\Exception.php';
        require 'PHPMailer-master\src\PHPMailer.php';
        require 'PHPMailer-master\src\SMTP.php';

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

    // Try to execute the statement
    if ($stmt->execute()) {
        // Email sending
        $fname = htmlspecialchars($fname);
        $lname = htmlspecialchars($lname);
        $email = htmlspecialchars($email);
        $p = htmlspecialchars($p);
        $cnic= htmlspecialchars($cnic);

        $message = "Dear $fname $lname,\n\n";
        $message .= "BELOW ARE YOUR LOGIN DETAILS:\n";
        $message .= "Your email address is: $email\n";
        $message .= "Your CNIC is: $cnic\n";
        $message .= "Your password is: $p\n";
        $message .= "\n";
        $message .= "VISIT OUR WEBSITE FOR MORE DETAILS\n www.appraisechain.pk \n";
        $message .= "Thank you for registering.\n";
        $message .= "THIS EMAIL WAS GENERATED AUTOMATICALLY, PLEASE DO NOT REPLY TO THIS EMAIL.";

        $subject = "AppraiseChain CONFIDENTIAL";

        // Sender's email address
        $from = "azanasim1@gmail.com";

        try {
            //Server settings
            $mail->isSMTP();                        // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';   // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;               // Enable SMTP authentication
            $mail->Username   = 'azanasim1@gmail.com'; // SMTP username
            $mail->Password   = 'abcd'; // SMTP password
            $mail->SMTPSecure = 'tls';              // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                // TCP port to connect to

            //Recipients
            $mail->setFrom($from, 'Your Name');
            $mail->addAddress($email); // Add a recipient

            // Content
            $mail->isHTML(false); // Set email format to plain text
            $mail->Subject = $subject;
            $mail->Body    = $message;

            // Send email
            $mail->send();
            echo "<div class='container'><h1> REGISTRATION SUCCESSFUL </h1><p>Registration successful! An email has been sent to you with login details.</p></div>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            echo "<div class='container'><h1> REGISTRATION SUCCESSFUL </h1><p>Registration successful! Failed to send email. Please contact support.</p></div>";
        }
    } else {
        ?>
        <div class="container">
            <h1> REGISTRATION ERROR </h1>
            <p>Error during registration. Please try again. <a href='log_reg.php'>Back</a></p>
        </div>
        <?php
    }

           
        
    }

    $stmt->close();
    $conn->close();
}

?>


</body>
</html>

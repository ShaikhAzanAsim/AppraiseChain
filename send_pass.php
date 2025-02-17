<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Paswsword</title>
    <link rel="stylesheet" href="log_reg.css">
</head>
<body>

<?php
include 'connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
        require 'PHPMailer-master\src\Exception.php';
        require 'PHPMailer-master\src\PHPMailer.php';
        require 'PHPMailer-master\src\SMTP.php';

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $email = $_POST["email"];

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

    $sqlHod = "SELECT * FROM hod WHERE email = ?";
    $sqlEmployee = "SELECT * FROM employee WHERE email = ?";
    

    // First, check in the HOD table
    $stmtHod = $conn->prepare($sqlHod);
    $stmtHod->bind_param('s', $email);
    $stmtHod->execute();
    $resultHod = $stmtHod->get_result();


    // If no match in HOD, check in the Employee table
    $stmtEmployee = $conn->prepare($sqlEmployee);
    $stmtEmployee->bind_param('s', $email);
    $stmtEmployee->execute();
    $resultEmployee = $stmtEmployee->get_result();


    if ($resultHod->num_rows == 1) {
        // Fetch the row
        $rowHod = $resultHod->fetch_assoc();
        $fname= $rowHod['fname'];
        $lname= $rowHod['lname'];

        // Update password for HOD based on identifier type
        $updateHod = "UPDATE hod SET password = ? WHERE email = ?";
        $stmtUpdateHod = $conn->prepare($updateHod);
        $stmtUpdateHod->bind_param('ss', $passwordHash, $email);

            
            
            
            if ($stmtUpdateHod->execute()) {

                // Email sending
        $fname = htmlspecialchars($fname);
        $lname = htmlspecialchars($lname);
        $email = htmlspecialchars($email);
        $p = htmlspecialchars($p);

        $message = "Dear $fname $lname,\n\n";
        $message .= "BELOW ARE YOUR LOGIN DETAILS:\n";
        $message .= "Your email address is: $email\n";
        $message .= "Your password is: $p\n";
        $message .= "\n";
        $message .= "VISIT OUR WEBSITE FOR MORE DETAILS\n www.appraisechain.pk \n";
        $message .= "THIS IS YOUR NEW PASSWORD.\n";
        $message .= "THIS EMAIL WAS GENERATED AUTOMATICALLY, PLEASE DO NOT REPLY TO THIS EMAIL.";

        $subject = "AppraiseChain NEW LOGIN INFO";

        // Sender's email address
        $from = "azanasim1@gmail.com";

        try {
            //Server settings
            $mail->isSMTP();                        // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';   // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;               // Enable SMTP authentication
            $mail->Username   = 'azanasim1@gmail.com'; // SMTP username
            $mail->Password   = 'gczw dtca njen oxjp'; // SMTP password
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






                echo "Password updated successfully for HOD. <a href='log_reg.php'>Click here to log in</a>";
            } else {
                echo "Error updating password for HOD.";
            }

    } elseif($resultEmployee->num_rows == 1) {
        
            // Fetch the row
            $rowEmployee = $resultEmployee->fetch_assoc();

            $fname= $rowEmployee['fname'];
            $lname= $rowEmployee['lname'];

            // Update password for Employee based on identifier type
            $updateEmployee = "UPDATE employee SET password = ? WHERE email = ?";
            $stmtUpdateEmployee = $conn->prepare($updateEmployee);
            $stmtUpdateEmployee->bind_param('ss', $passwordHash, $email);    
                
                if ($stmtUpdateEmployee->execute()) {


                            // Email sending
        $fname = htmlspecialchars($fname);
        $lname = htmlspecialchars($lname);
        $email = htmlspecialchars($email);
        $p = htmlspecialchars($p);

        $message = "Dear $fname $lname,\n\n";
        $message .= "BELOW ARE YOUR LOGIN DETAILS:\n";
        $message .= "Your email address is: $email\n";
        $message .= "Your password is: $p\n";
        $message .= "\n";
        $message .= "VISIT OUR WEBSITE FOR MORE DETAILS\n www.appraisechain.pk \n";
        $message .= "THIS IS YOUR NEW PASSWORD.\n";
        $message .= "THIS EMAIL WAS GENERATED AUTOMATICALLY, PLEASE DO NOT REPLY TO THIS EMAIL.";

        $subject = "AppraiseChain NEW LOGIN INFO";

        // Sender's email address
        $from = "azanasim1@gmail.com";

        try {
            //Server settings
            $mail->isSMTP();                        // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';   // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;               // Enable SMTP authentication
            $mail->Username   = 'azanasim1@gmail.com'; // SMTP username
            $mail->Password   = 'fiwz ixnk snzs laof'; // SMTP password
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
        // Email sending
        $fname = htmlspecialchars($fname);
        $lname = htmlspecialchars($lname);
        $email = htmlspecialchars($email);
        $p = htmlspecialchars($p);

        $message = "Dear $fname $lname,\n\n";
        $message .= "BELOW ARE YOUR LOGIN DETAILS:\n";
        $message .= "Your email address is: $email\n";
        $message .= "Your password is: $p\n";
        $message .= "\n";
        $message .= "VISIT OUR WEBSITE FOR MORE DETAILS\n www.appraisechain.pk \n";
        $message .= "THIS IS YOUR NEW PASSWORD.\n";
        $message .= "THIS EMAIL WAS GENERATED AUTOMATICALLY, PLEASE DO NOT REPLY TO THIS EMAIL.";

        $subject = "AppraiseChain NEW LOGIN INFO";

        // Sender's email address
        $from = "azanasim1@gmail.com";

        try {
            //Server settings
            $mail->isSMTP();                        // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';   // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;               // Enable SMTP authentication
            $mail->Username   = 'azanasim1@gmail.com'; // SMTP username
            $mail->Password   = 'fiwz ixnk snzs laof'; // SMTP password
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
            echo "<div class='container'><h1> NEW PASSWORD SENT SUCCESSFULLY </h1><p>An email has been sent to you with login details.</p></div>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            echo "<div class='container'><h1> NEW PASSWORD SENT SUCCESSFULLY </h1><p> Failed to send email. Please Try Again.</p></div>";
        }
        // Email sending
        $fname = htmlspecialchars($fname);
        $lname = htmlspecialchars($lname);
        $email = htmlspecialchars($email);
        $p = htmlspecialchars($p);

        $message = "Dear $fname $lname,\n\n";
        $message .= "BELOW ARE YOUR LOGIN DETAILS:\n";
        $message .= "Your email address is: $email\n";
        $message .= "Your password is: $p\n";
        $message .= "\n";
        $message .= "VISIT OUR WEBSITE FOR MORE DETAILS\n www.appraisechain.pk \n";
        $message .= "THIS IS YOUR NEW PASSWORD.\n";
        $message .= "THIS EMAIL WAS GENERATED AUTOMATICALLY, PLEASE DO NOT REPLY TO THIS EMAIL.";

        $subject = "AppraiseChain NEW LOGIN INFO";

        // Sender's email address
        $from = "azanasim1@gmail.com";

        try {
            //Server settings
            $mail->isSMTP();                        // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';   // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;               // Enable SMTP authentication
            $mail->Username   = 'azanasim1@gmail.com'; // SMTP username
            $mail->Password   = 'fiwz ixnk snzs laof'; // SMTP password
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
            echo "<div class='container'><h1> NEW PASSWORD SENT SUCCESSFULLY </h1><p>An email has been sent to you with login details.</p></div>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            echo "<div class='container'><h1> NEW PASSWORD SENT FAILED </h1><p> Failed to send email. Please Try Again.</p></div>";
        }

                    

                    echo "Password updated successfully for Employee. <a href='log_reg.php'>Click here to log in</a>";
                } else {
                    echo "Error updating password for Employee.";
                }
        
    } else {
            echo "Invalid email. <a href='log_reg.php'>Click here to go back</a>";
        }


        //$stmt->close();
        $conn->close();

}

?>


</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Employee</title>
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
    $dob = $_POST["dob"];
    $job_id = $_POST["job_id"];
    $qualification=$_POST["qualification"];
    $ai=null;
    $add=null;
    $pic=null;
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

    $filename = 'hod_id.txt';
    $content = file_get_contents($filename);
    $hod_id = substr($content, 0, 15);


    $stmt_org = $conn->prepare("SELECT organization_id FROM hod WHERE cnic = ?");
    $stmt_org->bind_param("s", $hod_id);
    $stmt_org->execute();
    $resultHod = $stmt_org->get_result();

    $rowHod = $resultHod->fetch_assoc();

    $organization = $rowHod['organization_id'];

    $stmt_org->close();


    // Check if the email already exists
    $stmt = $conn->prepare("SELECT cnic FROM employee WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    $stmt_h = $conn->prepare("SELECT cnic FROM hod WHERE email = ?");
    $stmt_h->bind_param("s", $email);
    $stmt_h->execute();
    $stmt_h->store_result();



    if ($stmt->num_rows > 0 && $stmt_h->num_rows > 0 ) {
        ?>
        <div class="container">
            <h1> EMAIL ALREADY EXISTS </h1>
            <p>An Employee with this email already exists. <a href='insert_emp.php'>Back</a></p>
        </div>
        <?php
    } else {

        

        // Insert the user data into the database
        $stmt = $conn->prepare("INSERT INTO employee (CNIC,organization_id,job_id ,fname, lname,address, email, password, gender,dob,pic) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?,?)");
        $stmt->bind_param("siissssssss", $cnic, $organization,$job_id ,$fname,$lname, $add, $email,$passwordHash,$gender,$dob,$pic);

        

        require 'PHPMailer-master\src\Exception.php';
        require 'PHPMailer-master\src\PHPMailer.php';
        require 'PHPMailer-master\src\SMTP.php';

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

    // Try to execute the statement
    if ($stmt->execute()) {
        // Prepare and bind
        $stmt_p = $conn->prepare("INSERT INTO performance (emp_id, qualification, ai_score) VALUES (?, ?, ?)");
        $stmt_p->bind_param("sss", $cnic, $qualification, $ai); // 'sss' means three string parameters
        $stmt_p->execute();
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
        $message .= "Your Manager Just Got You Registered.\n";
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
            echo "<div class='container'><h1> EMPLOYEE REGISTRATION SUCCESSFUL </h1><p>Registration successful! An email has been sent to your Employee with their login details.</p></div>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            echo "<div class='container'><h1> EMPLOYEE REGISTRATION SUCCESSFUL </h1><p>Registration successful! Failed to send email. Please contact support.</p></div>";
        }


    } else {
        ?>
        <div class="container">
            <h1>ERROR </h1>
            <p>Error during employee registration. Please try again. <a href='insert_emp.php'>Back</a></p>
        </div>
        <?php
    }

           
        
    }

    $stmt->close();
    $stmt_h->close();
    $stmt_p->close();
    $conn->close();
}

?>


</body>
</html>

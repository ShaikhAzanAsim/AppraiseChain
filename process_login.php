<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Process</title>
    <link rel="stylesheet" href="log_reg.css">
</head>
<body>

<?php
// Include your database connection file
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the login identifier (either email or CNIC) and password from the form
    $loginIdentifier = $_POST['loginIdentifier'];  // This can be email or CNIC
    $password = $_POST['logpass'];

    // Check if the input is an email or CNIC
    if (filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL)) {
        // It's an email
        $sqlHod = "SELECT * FROM hod WHERE email = ?";
        $sqlEmployee = "SELECT * FROM employee WHERE email = ?";
    } elseif (preg_match("/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/", $loginIdentifier)) {
        // It's a CNIC
        $sqlHod = "SELECT * FROM hod WHERE cnic = ?";
        $sqlEmployee = "SELECT * FROM employee WHERE cnic = ?";
    } else {
        // Invalid email or CNIC format
        echo "Please enter a valid email or CNIC. <a href='log_reg.php'>Click here to go back</a>";;
        exit();
    }

    // First, check in the HOD table
    $stmtHod = $conn->prepare($sqlHod);
    $stmtHod->bind_param('s', $loginIdentifier);
    $stmtHod->execute();
    $resultHod = $stmtHod->get_result();

    if ($resultHod->num_rows == 1) {
        // Fetch the row
        $rowHod = $resultHod->fetch_assoc();
        $email = $rowHod['email'];
        $storedCNIC = $rowHod['cnic'];
        
        // Verify the password
        if (password_verify($password, $rowHod['password'])) {
            // Redirect to HOD page
            $filename = 'hod_id.txt';
            file_put_contents($filename, $storedCNIC);
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['cnic'] = $storedCNIC;
           
            header("Location: FYP Project Work/FYP Project Work/FrontEnd/HOD Portal/home_hod.php");
            

            exit();
        } else {
            echo "Invalid password for HOD. <a href='log_reg.php'>Click here to go back</a>";;
        }
    } else {
        // If no match in HOD, check in the Employee table
        $stmtEmployee = $conn->prepare($sqlEmployee);
        $stmtEmployee->bind_param('s', $loginIdentifier);
        $stmtEmployee->execute();
        $resultEmployee = $stmtEmployee->get_result();

        if ($resultEmployee->num_rows == 1) {
            // Fetch the row
            $rowEmployee = $resultEmployee->fetch_assoc();
            $email = $rowEmployee['email'];
            $storedCNIC = $rowEmployee['cnic'];

            // Verify the password
            if (password_verify($password, $rowEmployee['password'])) {
                // Redirect to Employee page
                $filename = 'emp_id.txt';
                file_put_contents($filename, $storedCNIC);
                session_start();
                $_SESSION['email'] = $email;
                $_SESSION['cnic'] = $storedCNIC;

                //attendance automated
                // Get today's date in Y-m-d format
                $today = date('Y-m-d');

                // Check if an entry already exists for today and the given emp_id
                $stmt_check = $conn->prepare("SELECT id FROM attendance WHERE emp_id = ? AND date = ?");
                $stmt_check->bind_param("ss", $storedCNIC, $today);
                $stmt_check->execute();
                $stmt_check->store_result();

                if ($stmt_check->num_rows == 0) {
                    // No entry exists, insert a new record
                    $stmt_insert = $conn->prepare("INSERT INTO attendance (emp_id, status, date) VALUES (?, 1, ?)");
                    $stmt_insert->bind_param("ss", $storedCNIC, $today);
                    
                    if ($stmt_insert->execute()) {
                        echo "Attendance for employee $storedCNIC on $today has been recorded.";
                    } else {
                        echo "Error: " . $stmt_insert->error;
                    }

                    $stmt_insert->close();
                } else {
                    // Entry already exists for today, no need to insert
                    echo "Attendance for employee $cnic on $today has already been recorded.";
                }

                $stmt_check->close();

                

                header("Location: FYP Project Work/FYP Project Work/FrontEnd/Employee Portal/employee-portal.php");
                exit();
            } else {
                echo "Invalid password for Employee. <a href='log_reg.php'>Click here to go back</a>";;
            }
        } else {
            echo "Invalid email or CNIC. <a href='log_reg.php'>Click here to go back</a>";;
        }
    }
}
?>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>

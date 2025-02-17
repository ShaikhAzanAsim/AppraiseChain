<?php
// Include your database connection file
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the login identifier (either email or CNIC) and password from the form
    $loginIdentifier = $_POST['loginIdentifier'];  // This can be email or CNIC
    $password_old = $_POST['old'];
    $password_new = $_POST['new'];

    // Determine if the identifier is an email or a CNIC
    if (filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL)) {
        // It's an email
        $sqlHod = "SELECT * FROM hod WHERE email = ?";
        $sqlEmployee = "SELECT * FROM employee WHERE email = ?";
        $identifierType = 'email';
    } elseif (preg_match("/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/", $loginIdentifier)) {
        // It's a CNIC
        $sqlHod = "SELECT * FROM hod WHERE cnic = ?";
        $sqlEmployee = "SELECT * FROM employee WHERE cnic = ?";
        $identifierType = 'cnic';
    } else {
        // Invalid email or CNIC format
        echo "Please enter a valid email or CNIC. <a href='log_reg.php'>Click here to go back</a>";
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
        
        
        // Verify the password
        if (password_verify($password_old, $rowHod['password'])) {
            // Hash the new password
            $newPasswordHash = password_hash($password_new, PASSWORD_BCRYPT);
            
            // Update password for HOD based on identifier type
            $updateHod = "UPDATE hod SET password = ? WHERE $identifierType = ?";
            $stmtUpdateHod = $conn->prepare($updateHod);
            $stmtUpdateHod->bind_param('ss', $newPasswordHash, $loginIdentifier);
            
            if ($stmtUpdateHod->execute()) {
                echo "Password updated successfully for HOD. <a href='log_reg.php'>Click here to log in</a>";
            } else {
                echo "Error updating password for HOD.";
            }
        } else {
            echo "Invalid current password for HOD. <a href='log_reg.php'>Click here to go back</a>";
            echo "<a href='log_reg.php'>Forgot Your Password?</a>";
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

            // Verify the password
            if (password_verify($password_old, $rowEmployee['password'])) {
                // Hash the new password
                $newPasswordHash = password_hash($password_new, PASSWORD_BCRYPT);

                // Update password for Employee based on identifier type
                $updateEmployee = "UPDATE employee SET password = ? WHERE $identifierType = ?";
                $stmtUpdateEmployee = $conn->prepare($updateEmployee);
                $stmtUpdateEmployee->bind_param('ss', $newPasswordHash, $loginIdentifier);
                
                if ($stmtUpdateEmployee->execute()) {
                    echo "Password updated successfully for Employee. <a href='log_reg.php'>Click here to log in</a>";
                } else {
                    echo "Error updating password for Employee.";
                }
            } else {
                echo "Invalid current password for Employee. <a href='log_reg.php'>Click here to go back</a>";
                echo "<a href='log_reg.php'>Forgot Your Password?</a>";
            }
        } else {
            echo "Invalid email or CNIC. <a href='log_reg.php'>Click here to go back</a>";
        }
    }
}

// Close database connection
$conn->close();
?>

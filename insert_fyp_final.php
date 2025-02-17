<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert FYP</title>
    
</head>
<body>

<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $duedate = $_POST["dueDate"];
   

    $filename = 'emp_id.txt';
    $content = file_get_contents($filename);
    $emp_id = substr($content, 0, 15);
    $status = null;

        

        // Insert the user data into the database
    $stmt = $conn->prepare("INSERT INTO fyp (emp_id,name,status ,due_date) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $emp_id, $name,$status ,$duedate);
    if ($stmt->execute()) {
        echo "<p>FYP inserted successfully.</p>";
    } else {
        throw new Exception("Error updating status: " . $stmt->error);
    }
        

    $stmt->close();
    $conn->close();
}

?>


</body>
</html>

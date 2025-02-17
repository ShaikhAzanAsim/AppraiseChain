<?php
// Include database connection
include 'connection.php';

// Read the first 15 characters from emp_id.txt
$emp_id_file = 'emp_id.txt';
if (file_exists($emp_id_file)) {
    $emp_id = substr(file_get_contents($emp_id_file), 0, 15);
} else {
    die("Error: emp_id.txt file not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Validate file type (allow only image files)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            die("Invalid file type. Only JPG, PNG, and GIF files are allowed.");
        }

        // Generate a unique filename to prevent overwriting
        $newFileName = uniqid() . '.' . $fileExtension;
        $uploadPath = 'media/' . $newFileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($fileTmpPath, $uploadPath)) {
            // Update the file name in the employee table
            $sql = "UPDATE employee SET pic = ? WHERE cnic = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $newFileName, $emp_id);

            if ($stmt->execute()) {
                echo "Image uploaded and updated successfully!";
            } else {
                echo "Error updating image in database: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "There was an error moving the uploaded file.";
        }
    } else {
        echo "No file uploaded or an error occurred during the upload.";
    }
}

$conn->close();
?>

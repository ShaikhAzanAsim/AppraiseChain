<?php
// Include the database connection file
include 'connection.php';

try {
    // Read emp_id from the file
    $filename = 'emp_id.txt';
    if (!file_exists($filename)) {
        throw new Exception("Employee ID file not found.");
    }

    $content = file_get_contents($filename);
    $emp_id = substr($content, 0, 15);

    if (empty($emp_id)) {
        throw new Exception("Employee ID is missing. Please log in.");
    }

    // Get the submitted form data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fyp_id = $_POST['name']; // FYP ID from the select box
        $status = $_POST['status']; // Status (0 or 1)

        // Determine the new value for the `status` column
        $new_status = ($status == "1") ? 1 : null;

        // Update the status in the database
        $stmt = $conn->prepare("UPDATE fyp SET status = ? WHERE id = ? AND emp_id = ?");
        $stmt->bind_param("iis", $new_status, $fyp_id, $emp_id);

        if ($stmt->execute()) {
            echo "<p>Status updated successfully.</p>";
        } else {
            throw new Exception("Error updating status: " . $stmt->error);
        }

        $stmt->close();
    } else {
        throw new Exception("Invalid request method.");
    }
} catch (Exception $e) {
    // Handle errors and display a user-friendly message
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Close the database connection
$conn->close();
?>

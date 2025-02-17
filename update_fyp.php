<?php

$filename = 'emp_id.txt';
$content = file_get_contents($filename);
$emp_id = substr($content, 0, 15);

// Check if emp_id is null or empty
if (empty($emp_id)) {
    echo "You have to log in to view your page. <a href='log_reg.php'>Click here to log in to your Employee Portal</a>";
} else {
    include 'connection.php'; // Include the database connection file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FYP</title>
    <link rel="stylesheet" href="research.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="media/logo.jpg" alt="Logo" />
            <h1>Update FYP Status</h1>
        </div>
        <nav>
            <ul>
                <li>
                    <a href="settings_employee.html">
                        <button>X</button>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <!-- Loader -->
    <div class="loader" id="loader-3"></div>
    <div class="form-container">
        <form class="custom-form" id="research_form" action="update_fyp_final.php" method="POST">
            <!-- Row 1 -->
            <div class="form-row">
                <select id="name" name="name" required>
                    <option disabled selected>FYP Name</option>
                    <?php
                    // Get the current date in the same format as stored in the database
                    $current_date = date('Y-m-d');

                    // Prepare the query to fetch FYP data where the due_date has not passed
                    $stmt = $conn->prepare("SELECT id, name FROM fyp WHERE emp_id = ? AND due_date >= ?");
                    $stmt->bind_param("ss", $emp_id, $current_date);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Generate options for the select box
                    while ($row = $result->fetch_assoc()) { 
                        echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
                    }

                    $stmt->close();
                    ?>
                </select>
                <select id="status" name="status" required>
                    <option disabled selected>FYP Status</option>
                    <option value="0">Not Done</option>
                    <option value="1">Completed/Done</option>
                </select>
            </div>
            <div class="abc">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
    $conn->close(); // Close the database connection
}
?>

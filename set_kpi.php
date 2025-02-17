<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $attendance = isset($_POST['attendance']) ? $_POST['attendance'] : 0;
    $successFYP = isset($_POST['success_fyp']) ? $_POST['success_fyp'] : 0;
    $w = isset($_POST['w']) ? $_POST['w'] : 0;
    $y = isset($_POST['y']) ? $_POST['y'] : 0;
    $x = isset($_POST['x']) ? $_POST['x'] : 0;
    $activeTime = isset($_POST['active_time']) ? $_POST['active_time'] : 0;

    // Define the file path
    $filePath = 'kpi.txt';

    try {
        // Open the file in write mode to clear its contents
        $file = fopen($filePath, 'w');

        if (!$file) {
            throw new Exception('Failed to open the file.');
        }

        // Write each weight to a new line
        fwrite($file, $attendance . PHP_EOL);
        fwrite($file, $successFYP . PHP_EOL);
        fwrite($file, $w . PHP_EOL);
        fwrite($file, $y . PHP_EOL);
        fwrite($file, $x . PHP_EOL);
        fwrite($file, $activeTime . PHP_EOL);

        // Close the file
        fclose($file);

        // Success message
        echo "KPI weights have been successfully saved to $filePath.";
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>

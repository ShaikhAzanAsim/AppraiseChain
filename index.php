<?php
include 'connection.php';
?>


<?php
// File paths
$empFile = 'emp_id.txt';
$hodFile = 'hod_id.txt';

// Function to clear the contents of a file
function clearFile($filePath) {
    if (file_exists($filePath)) {
        $file = fopen($filePath, "w"); // Open the file in write mode
        if ($file) {
            ftruncate($file, 0); // Truncate the file to 0 bytes (clear contents)
            fclose($file); // Close the file
           // echo "Cleared contents of $filePath successfully.<br>";
        } else {
            //echo "Failed to open $filePath.<br>";
        }
    } else {
        //echo "$filePath does not exist.<br>";
    }
}



// Clear both files
clearFile($empFile);
clearFile($hodFile);

$filePath2 = 'kpi.txt';

try {
    // Open the file in write mode to clear its content
    $file1 = fopen($filePath2, 'w');

    if (!$file1) {
        throw new Exception('Failed to open the file.');
    }

    // New values to write
    $newValues = [10, 10, 20, 15, 10, 10];

    // Write each value to the file on a new line
    foreach ($newValues as $value) {
        fwrite($file1, $value . PHP_EOL);
    }

    // Close the file
    fclose($file1);

    //echo "kpi.txt has been successfully updated with new values.";
} catch (Exception $e) {
    //echo "An error occurred: " . $e->getMessage();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AppraiseChain</title>
    <link rel="stylesheet" href="log_reg.css">
</head>
<body>
	<video autoplay muted loop id="background-video">
    	<source src="media\login_vid.mp4" type="video/mp4">
    	Your browser does not support the video tag.
	</video>
	
</body>
</html>

<?php
include 'connection.php';

	$filename = 'hod_id.txt';
    $content = file_get_contents($filename);
    $hod_id = substr($content, 0, 15);


    $stmt_org = $conn->prepare("SELECT organization_id FROM hod WHERE cnic = ?");
    $stmt_org->bind_param("s", $hod_id);
    $stmt_org->execute();
    $resultHod = $stmt_org->get_result();

    $rowHod = $resultHod->fetch_assoc();

    $organization = $rowHod['organization_id'];

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Table</title>
    <link rel="stylesheet" href="evaluate.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="media/logo.jpg" alt="Logo" />
            <h1>Employee Evaluation Table</h1>
        </div>
        <nav>
            <ul>
                <li><a href="FYP Project Work/FYP Project Work/FrontEnd/HOD Portal/home_hod.php"><button>X</button></a></li>
                <li><a href="#">   </a></li>
            </ul>
        </nav>
    </header>
     <!-- Loader -->
     <div class="loader" id="loader-3"></div>
    <!-- White rectangle box -->
    <div class="container">
    <?php
$query = "
SELECT 
    e.cnic, 
    CONCAT(e.fname, ' ', e.lname) AS Name, 
    j.title AS JobTitle,
    p.ai_score as Evaluated_Score
FROM 
    employee e
JOIN 
    job j 
ON 
    e.job_id = j.id
Join performance p
on e.cnic = p.emp_id
WHERE 
    e.organization_id = '$organization'
order by p.ai_score  desc";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Sr No.</th>"; // Explicit header
    echo "<th>Name</th>";   // Explicit header
    echo "<th>Job Title</th>";  // Explicit header
    echo "<th>Evaluated Score</th>";  // Explicit header
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    // Display rows with a serial number
    $srNo = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $srNo++ . "</td>"; // Serial number column
        echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['JobTitle']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Evaluated_Score']) . "</td>"; // Placeholder for Evaluated Score
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>No data found.</p>";
}
?>

    </div>




</body>



</html>

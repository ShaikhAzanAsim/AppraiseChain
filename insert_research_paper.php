<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Paper</title>
</head>
<body>

<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doi = $_POST["doi"];
    $title = $_POST["title"];
    $journal = $_POST["journal"];
    $issn = $_POST["issn"];
    $dop = $_POST["dop"];
    $subject = $_POST["subject"];
    $sub_subject = $_POST["sub_subject"];
    $hrjs = $_POST["hrjs"];
    $impact = $_POST["impact"];
    $volume = $_POST["volume"];
    $country = $_POST["country"];
    $citation = $_POST["citation"];
    
    // Read emp_id from the file
    $filename = 'emp_id.txt';
    $content = file_get_contents($filename);
    $emp_id = substr($content, 0, 15);

    // Fetch organization_id based on emp_id
    $stmt_org = $conn->prepare("SELECT organization_id FROM employee WHERE cnic = ?");
    $stmt_org->bind_param("s", $emp_id);
    $stmt_org->execute();
    $resultEmp = $stmt_org->get_result();

    $rowEmp = $resultEmp->fetch_assoc();
    $org_id = $rowEmp['organization_id'];

    $stmt_org->close();

    // Insert the research paper data into the database
    $stmt = $conn->prepare("INSERT INTO researchpaper (ISSN, DOI, title, employee_id, organization_id, journal, subject, sub_subject, pub_date, hrjs_category, impact_factor, country, citations_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssisssssssi", $issn, $doi, $title, $emp_id, $org_id, $journal, $subject, $sub_subject, $dop, $hrjs, $impact, $country, $citation);

    if ($stmt->execute()) {
        echo "<script>
                alert('Research paper data successfully inserted.');
                window.location.href = 'FYP Project Work/FYP Project Work/FrontEnd/Employee Portal/employee-portal.php';
              </script>";
    } else {
        echo "<script>alert('Error inserting data: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

</body>
</html>

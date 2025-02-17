<?php
if (isset($_GET['action']) && $_GET['action'] == 'run_python') {
    
    // Execute Python script
    $output = shell_exec('"C:\\Program Files (x86)\\Microsoft Visual Studio\\Shared\\Python39_64\\python.exe" "C:\\xampp\\htdocs\\AppraiseChain\\ai_score.py" 2>&1');
    
    if ($output === null) {
        echo json_encode(['message' => 'Evaluation completed successfully.']);
    } else {
        echo json_encode(['message' => 'Error running Python script.', 'output' => $output]);
    }
    exit;

}
?>

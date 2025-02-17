<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert FYP</title>
    <link rel="stylesheet" href="research.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="media/logo.jpg" alt="Logo" />
            <h1>Insert FYP</h1>
        </div>
        <nav>
            <ul>
                <li><a href="settings_employee.html"><button>X</button></a></li>
                
            </ul>
        </nav>
    </header>
<!-- Loader -->
<div class="loader" id="loader-3"></div>
<div class="form-container">
    <form class="custom-form" id="research_form"action="insert_fyp_final.php" method="POST">
        <!-- Row 1 -->
        <div class="form-row">
            <input type="text" name="name" placeholder="FYP Name" id="name" required>
        </div>
        
        <!-- Row 2 -->
        <div class="form-row">
            <input type="date" name="dueDate" placeholder="Due Date" id="dueDate"required>
        </div>

        <div class="abc">
            <button type="submit">Insert</button>
        </div>
        
    </form>
</div>


<script>
   
</script>



</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update KPI's</title>
    <link rel="stylesheet" href="research.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="media/logo.jpg" alt="Logo" />
            <h1>Configure KPI's</h1>
        </div>
        <nav>
            <ul>
                <li><a href="settings_hod.html"><button>X</button></a></li>
            </ul>
        </nav>
    </header>
    <!-- Loader -->
    <div class="loader" id="loader-3"></div>

    <div class="form-container">
        <form class="custom-form" id="research_form" action="set_kpi.php" method="POST">
            <!-- Row 1 -->
            <div class="form-row">
                <input type="number" name="attendance" placeholder="Weight For Attendance" id="attendance" min="0" required>
                <input type="number" name="success_fyp" placeholder="Weight For Successful FYP" id="success_fyp" min="0" required>
            </div>

            <!-- Row 2 -->
            <div class="form-row">
                <input type="number" name="w" placeholder="Weight For W Paper" id="w" min="0" required>
                <input type="number" name="y" placeholder="Weight For Y Paper" id="y" min="0" autocomplete="off">
            </div>

            <!-- Row 3 -->
            <div class="form-row">
                <input type="number" name="x" placeholder="Weight For X Paper" id="x" min="0" required>
                <input type="number" name="active_time" placeholder="Weight For Activity" id="active_time" min="0" required>
            </div>

            <div class="abc">
                <button type="submit">Submit</button>
            </div>

            <span id="weight-warning">Make Sure Sum Of All Weights Is Equal To 75</span>
        </form>
    </div>

    <script>
        document.getElementById('research_form').addEventListener('submit', function (event) {
            // Get all the weights
            const attendance = parseFloat(document.getElementById('attendance').value) || 0;
            const successFYP = parseFloat(document.getElementById('success_fyp').value) || 0;
            const w = parseFloat(document.getElementById('w').value) || 0;
            const y = parseFloat(document.getElementById('y').value) || 0;
            const x = parseFloat(document.getElementById('x').value) || 0;
            const activeTime = parseFloat(document.getElementById('active_time').value) || 0;

            // Ensure no negative numbers
            const inputs = [attendance, successFYP, w, y, x, activeTime];
            if (inputs.some(input => input < 0)) {
                alert("Negative numbers are not allowed. Please enter valid values.");
                event.preventDefault(); // Prevent form submission
                return;
            }

            // Calculate the total weight
            const totalWeight = attendance + successFYP + w + y + x + activeTime;

            // Check if total weight is exactly 75
            if (totalWeight != 75) {
                alert(`The total weight is ${totalWeight}, which is not 75. The form will be cleared. Please re-enter values.`);
                event.preventDefault(); // Prevent form submission

                // Clear form entries
                document.getElementById('research_form').reset();
            }
        });
    </script>
</body>
</html>

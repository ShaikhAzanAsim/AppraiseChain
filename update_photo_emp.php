<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <link rel="stylesheet" href="settings.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="media/logo.jpg" alt="Logo" />
            <h1>Enter Photo</h1>
        </div>
        <nav>
            <ul>
                <li><a href="settings_employee.html"><button>X</button></a></li>
            </ul>
        </nav>
    </header>
    <!-- Loader -->
    <div class="loader" id="loader-3"></div>
    <div class="button-container">
        <form action="upload_image.php" method="POST" enctype="multipart/form-data" class="custom-form">
            <label class="custom-file-input">
                <span>Click or drag to upload your image</span>
                <input type="file" name="image" required>
            </label>
            <button type="submit">Upload</button>
        </form>
    </div>
    <script>
        // Add any additional JavaScript if needed
    </script>
</body>
</html>

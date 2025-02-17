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
        <source src="media/login_vid.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="section">
        <div class="container">
            <div class="row full-height justify-content-center">
                <div class="col-12 text-center align-self-center py-5">
                    <div class="section pb-5 pt-5 pt-sm-2 text-center">
                        <div class="card-3d-wrap mx-auto">
                            <div class="card-3d-wrapper">
                                <div class="card-front">
                                    <div class="center-wrap">
                                        <div class="section text-center">
                                            <h4 class="mb-4 pb-3">Change Password</h4>
                                            <form action="update_pass.php" method="POST" id="passwordForm">
                                                <div class="form-group">
                                                    <input type="text" name="loginIdentifier" class="form-style" placeholder="Your Email or CNIC" id="loginIdentifier" autocomplete="off" required>
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>  
                                                <div class="form-group mt-2">
                                                    <input type="password" name="old" class="form-style" placeholder="Current Password" id="logpass_old" autocomplete="off" required>
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                    <span class="toggle-password" onclick="togglePasswordVisibility('logpass_old')">👁️</span>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="password" name="new" class="form-style" placeholder="New Password" id="logpass_new" autocomplete="off" required>
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                    <span class="toggle-password" onclick="togglePasswordVisibility('logpass_new')">👁️</span>
                                                </div>
                                                <br>
                                                <button type="submit" class="btn mt-4">Change Password</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Check if new and old passwords are different
        document.getElementById('passwordForm').addEventListener('submit', function (e) {
            const oldPassword = document.getElementById('logpass_old').value;
            const newPassword = document.getElementById('logpass_new').value;

            if (oldPassword === newPassword) {
                e.preventDefault(); // Stop form submission
                alert("New password must be different from the old password.");
            }
        });

        // Function to toggle password visibility
        function togglePasswordVisibility(fieldId) {
            const passwordField = document.getElementById(fieldId);
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }

        // Form validation for email or CNIC input
        document.getElementById('loginIdentifier').addEventListener('submit', function () {
            const input = this.value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const cnicPattern = /^[0-9]{5}-[0-9]{7}-[0-9]{1}$/;

            if (!emailPattern.test(input) && !cnicPattern.test(input)) {
                alert("Please enter a valid email or CNIC.");
            }
        });
    </script>
</body>
</html>

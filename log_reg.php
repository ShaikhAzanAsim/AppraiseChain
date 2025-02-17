<?php
include 'connection.php';
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
	<div class="section">
		<div class="container">
			<div class="row full-height justify-content-center">
				<div class="col-12 text-center align-self-center py-5">
					<div class="section pb-5 pt-5 pt-sm-2 text-center">

			          	<input class="checkbox" type="checkbox" id="reg-log" name="reg-log"/>
			          	<label for="reg-log"></label>
						<div class="card-3d-wrap mx-auto">
							<div class="card-3d-wrapper">
								<div class="card-front">
									<div class="center-wrap">
										<div class="section text-center">
											<h4 class="mb-4 pb-3">Log In</h4>
											<form action="process_login.php" method="POST">
											<div class="form-group">
											<input type="text" name="loginIdentifier" class="form-style" placeholder="Your Email or CNIC" id="loginIdentifier" autocomplete="off" required>
												<i class="input-icon uil uil-at"></i>
											</div>	
											<div class="form-group mt-2">
												<input type="password" name="logpass" class="form-style" placeholder="Your Password" id="logpass" autocomplete="off" required>
												<i class="input-icon uil uil-lock-alt"></i>
												<span class="toggle-password" onclick="togglePasswordVisibility('logpass')">👁️</span>
											</div>
											<br>
											<button type="submit" class="btn mt-4">Login</button>
                            				<p class="mb-0 mt-4 text-center"><a href="forgot_pass.php" class="link">Forgot your password?</a></p>
											</form>
				      					</div>
			      					</div>
			      				</div>
								<div class="card-back">
									<div class="center-wrap">
										<div class="section text-center">
											<h4 class="mb-4 pb-3">Sign Up</h4>
											<form id="registration_form" action="process_register.php" method="POST">
											<div class="form-group">
												<input type="text" name="fname" class="form-style" placeholder="Your First Name" id="fname" autocomplete="off" required>
												<i class="input-icon uil uil-user"></i>
											</div>	
											<div class="form-group mt-2">
												<input type="text" name="lname" class="form-style" placeholder="Your Last Name" id="lname" autocomplete="off" required>
												<i class="input-icon uil uil-at"></i>
											</div>	
											<div class="form-group mt-2">
												<input type="email" name="email" class="form-style" placeholder="Your Email" id="email" autocomplete="off" required>
												<i class="input-icon uil uil-lock-alt"></i>
											</div>
                                            <div class="form-group mt-2">
												<input type="text" name="cnic" class="form-style" placeholder="Your CNIC (12345-1234567-8)" id="cnic" autocomplete="off" pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" required>
												<i class="input-icon uil uil-lock-alt"></i>
											</div>
                                            <div class="form-group mt-2">
												<input type="text" name="address" class="form-style" placeholder="Your Address" id="address" autocomplete="off" required>
												<i class="input-icon uil uil-lock-alt"></i>
											</div>
                                            <div class="form-group mt-2">
                                                <select id="oranization_name" name="organization_name" class="form-style-select" required>
                                                    <option disabled selected>Choose Your Department</option>
                                                        <?php 
                                                        $sql = "select id,Name from organization;";
                                                        $result=mysqli_query($conn, $sql);
                                                        while($row = mysqli_fetch_assoc($result)) 
                                                        { ?>
                                                        
                                                        <option value=<?php echo $row["id"]; ?>>
                                                    
                                                        <?php echo $row["Name"];?></option>
                                
                                                        <?php } ?>
                                                </select>
                                            </div>
											<br>
											<button type="submit" class="btn mt-4">Register</button>
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
        document.addEventListener('DOMContentLoaded', function() {
            const toggleCheckbox = document.getElementById('reg-log');
            const cardFront = document.querySelector('.card-front');
            const cardBack = document.querySelector('.card-back');

            function toggleCard() {
                if (toggleCheckbox.checked) {
                    cardFront.style.display = 'none';
                    cardBack.style.display = 'block';
                } else {
                    cardFront.style.display = 'block';
                    cardBack.style.display = 'none';
                }
            }

            toggleCheckbox.addEventListener('change', toggleCard);
            toggleCard();
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

		document.querySelector('form').addEventListener('submit', function (e) {
    		const input = document.getElementById('loginIdentifier').value;
   		 	const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    		const cnicPattern = /^[0-9]{5}-[0-9]{7}-[0-9]{1}$/;

    		if (!emailPattern.test(input) && !cnicPattern.test(input)) {
        		e.preventDefault(); // Stop form submission
        		alert("Please enter a valid email or CNIC.");
    		}
		});

    </script>
</body>
</html>

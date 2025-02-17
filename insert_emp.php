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
    <title>Insert Employee</title>
    <link rel="stylesheet" href="insertform.css">
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
						<div class="card-3d-wrap mx-auto">
							<div class="card-3d-wrapper">
								<div class="card-front">
									<div class="center-wrap">
										<div class="section text-center">
											<h4 class="mb-4 pb-3">Employee's Detail:</h4>
											<form id="registration_form" action="insert_employee.php" method="POST">
											<div class="form-group">
												<input type="text" name="fname" class="form-style" placeholder="Employee's First Name" id="fname" autocomplete="off" required>
												<i class="input-icon uil uil-user"></i>
											</div>	
											<div class="form-group mt-2">
												<input type="text" name="lname" class="form-style" placeholder="Employee's Last Name" id="lname" autocomplete="off" required>
												<i class="input-icon uil uil-at"></i>
											</div>	
											<div class="form-group mt-2">
												<input type="email" name="email" class="form-style" placeholder="Employee's Email" id="email" autocomplete="off" required>
												<i class="input-icon uil uil-lock-alt"></i>
											</div>
                                            <div class="form-group mt-2">
												<input type="text" name="cnic" class="form-style" placeholder="Employee's CNIC (12345-1234567-8)" id="cnic" autocomplete="off" pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" required>
												<i class="input-icon uil uil-lock-alt"></i>
											</div>
											<div class="form-group mt-2">
                                                <input type="date" name="dob" class="form-style" placeholder="Employee's DOB" id="dob" autocomplete="off">
												<i class="input-icon uil uil-lock-alt"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <select id="job_id" name="job_id" class="form-style-select" required>
                                                    <option disabled selected>Employee's Job Title</option>
                                                        <?php 
                                                        $sql = "select id,title from job where organization_id= $organization ;";
                                                        $result=mysqli_query($conn, $sql);
                                                        while($row = mysqli_fetch_assoc($result)) 
                                                        { ?>
                                                        
                                                        <option value=<?php echo $row["id"]; ?>>
                                                    
                                                        <?php echo $row["title"];?></option>
                                
                                                        <?php } ?>
                                                </select>
                                            </div>
											<div class="form-group mt-2">
                                                <select id="qualification_id" name="qualification" class="form-style-select" required>
                                                    <option disabled selected>Employee's Qualification</option>
                                                        <option value="PhD">PhD</option>
														<option value="Masters">Masters</option>
														<option value="Bachelors">Bachelors</option>  
                                                </select>
                                            </div>
                                            

											<br>
											<button type="submit" class="btn mt-4">Insert Employee</button>
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

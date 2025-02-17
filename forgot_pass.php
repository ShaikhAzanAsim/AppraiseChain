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
						<div class="card-3d-wrap mx-auto">
							<div class="card-3d-wrapper">
								<div class="card-front">
									<div class="center-wrap">
										<div class="section text-center">
											<h4 class="mb-4 pb-3">Forgot Password?</h4>
											<form action="send_pass.php" method="POST">
											<div class="form-group">
											<input type="text" name="email" class="form-style" placeholder="Your Email" id="email" autocomplete="off" required>
												<i class="input-icon uil uil-at"></i>
											</div>	
											<br>
											<button type="submit" class="btn mt-4">Send Email</button>
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

		document.querySelector('form').addEventListener('submit', function (e) {
    		const input = document.getElementById('email').value;
   		 	const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    		const cnicPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    		if (!emailPattern.test(input) && !cnicPattern.test(input)) {
        		e.preventDefault(); // Stop form submission
        		alert("Please enter a valid email");
    		}
		});

    </script>
</body>
</html>

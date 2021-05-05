<?php

	session_start();
	  
	
	//LOGIN VENDOR PHP
	require_once "config.php";

	ini_set('display_errors',1);
	error_reporting(E_ALL);

	/*** THIS! ***/
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	/*** ^^^^^ ***/

	$username = $password ="";
	$username_err = $password_err ="";
	$check = "";
	$notvendor = 0;

	if (isset($_POST["login"])){

		if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    	} else{
        $username = trim($_POST["username"]);
    	}

		if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
   		} else{
        $password = trim($_POST["password"]);
    	}

		if(empty($username_err) && empty($password_err)){
			$sql = "SELECT v_id, name, password FROM vendors WHERE name = ?";

			if($stmt = mysqli_prepare($link, $sql)){
		            // Bind variables to the prepared statement as parameters
		            mysqli_stmt_bind_param($stmt, "s", $param_username);

		            // Set parameters
		            $param_username = $username;

		            // Attempt to execute the prepared statement
		            if(mysqli_stmt_execute($stmt)){
		                // Store result
		                mysqli_stmt_store_result($stmt);

		                // Check if username exists, if yes then verify password
		                if(mysqli_stmt_num_rows($stmt) == 1){
		                    // Bind result variables
		                    mysqli_stmt_bind_result($stmt, $v_id, $name, $hashed_password);

		                    if(mysqli_stmt_fetch($stmt)){

		                        if(password_verify( $password , $hashed_password)){
		                            // Password is correct, so start a new session
		                            session_start();

		                            // Store data in session variables
		                            $_SESSION["loggedinv"] = true;
		                            $_SESSION["vendorid"] = $v_id;
		                            $_SESSION["name"] = $name;

		                            // Redirect user to welcome page
		                            header("location: restaurant/index.php");
		                            exit();
		                        } else{
		                            // Display an error message if password is not valid
		                            $password_err = "The password you entered was not valid.";
		                        }
		                    }
		                } else{
		                    // Display an error message if username doesn't exist
		                    $notvendor = 1;
		                }
		            } else{
		                $check = mysqli_stmt_error($stmt);
		            }
		    }
		    mysqli_stmt_close($stmt);
		}
	}

	if ($notvendor == 1) {
		$sql = "SELECT staffid, staffname, work_in, password FROM staff WHERE staffname = ('$username')";

	    $result = mysqli_query($link, $sql);
	    if (mysqli_num_rows($result) == 1) 
	    {
	    	$row = mysqli_fetch_array($result);

	    	$staffid = $row["staffid"];
	    	$staffname = $row["staffname"];
	    	$work_in = $row["work_in"];
	    	$hashed_password = $row['password'];

	    	if(password_verify( $password , $hashed_password))
	    	{
	    		// Password is correct, so start a new session


			    // Store data in session variables
			    $_SESSION["loggedins"] = true;
			    $_SESSION["staffid"] = $staffid;
			    $_SESSION["staffname"] = $staffname;

			    if ($work_in == "Casher"){
			    	$_SESSION["work_in"] = 1;
			    	header("location: casherinterface.php");
			    } else {
			    	$_SESSION["work_in"] = 2;
			    	header("location: kitchenview.php");
				}
	    	} else{
		        // Display an error message if password is not valid
		        $password_err = "The password you entered was not valid.";
		    }

	    } else {
	    	$username_err = "No account with that username";
	    }
	}


	$username = $email = $phonenum = $password = $confirm_pass = "";
		$tempusername = $tempemail = $tempconfirmpass = "";
		$username_err = $email_err = $phonenum_err = $password_err = $confirm_password_err = "";
	    $check ="";
	if (isset($_POST["register"])) {
		//REGISTER VENDOR PHP

		if ($_SERVER["REQUEST_METHOD"] == "POST") {	

			$tempusername = trim($_POST["username"]);
			$tempemail = trim($_POST["email"]);
			$phonenum = trim($_POST["phonenum"]);
			$password = trim($_POST["password"]);
			$tempconfirmpass = trim($_POST["confirmpassword"]);

			//validate username
			if (empty($tempusername)){
	            $username_err = "Please enter a name.";
	        }
	        else {
	        	$sql = "SELECT v_id FROM vendors WHERE name= ?";

	        	if ($stmt = mysqli_prepare($link , $sql)) {

	        		mysqli_stmt_bind_param($stmt , "s",$param_username);

	        		$param_username = $tempusername;

	        		if (mysqli_stmt_execute($stmt)) {
	        			mysqli_stmt_store_result($stmt);

	        			if (mysqli_stmt_num_rows($stmt) == 1) {
	        				$username_err = "This username is already taken.";
	        			}
	        			else {
	        				$username = $tempusername;
	        			}
	        		}
	        		else{
	        			$check = "Oops! Something went  wrong. Please try again later.";
	        		}
	        	}

	        }

	        //validate email
	        if (empty($tempemail)) {
	        	$email_err= "Please enter an email.";
	        }
	        elseif (!filter_var($tempemail, FILTER_VALIDATE_EMAIL)) {
	 			$email_err = "Invalid email format.";
			}
	        else{
	        	$email = $tempemail;
	        }

	        //validate phone number
	        if (empty($phonenum)){
	            $phonenum_err = "Please enter a contact number.";
	        }

	        //validate password
	        if (empty($password)) {
	            $password_err = "Please enter a password.";
	        }
	        elseif (strlen(trim($password)) < 6){
	            $password_err = "Password must have atleast 6 characters.";
	        }

	        //validate confirm password and match password
	        if (empty($tempconfirmpass)) {
	            $confirm_password_err = "Please enter a password.";
	        }
	        elseif ($tempconfirmpass == $password) {
	            $confirm_password = $tempconfirmpass;
	        }
	        else{
	            $confirm_password_err = "The password does not match.";
	        }

	        //validate error value b4 inserting into database
	        if (empty($username_err) && empty($email_err) && empty($confirm_password_err)) {

	        	$sql = "INSERT into vendors ( name , password , email , phonenum ) values ( ? , ? , ? , ?)";

	        	//sql statement inserted to database
	            if ($stmt = mysqli_prepare($link, $sql)){
	                //to transfer the information
	                mysqli_stmt_bind_param($stmt,"ssss",$tran_user,$tran_pass,$tran_email,$tran_phonenum);

	                //set the temp variable to the information
	                $tran_user = $username;
	                $tran_pass = password_hash( $confirm_password , PASSWORD_DEFAULT);
	                $tran_email = $email;
	                $tran_phonenum = $phonenum;

	                //execute the insert information statement
	                if(mysqli_stmt_execute($stmt)){
	                    // Records created successfully. Redirect to landing page
						// Store data in session variables
						$_SESSION["loggedin"] = true;
						$_SESSION["vendorid"] = $v_id;
						$_SESSION["name"] = $name;
	                    header("location: restaurant/index.php");
	                    exit();
	                } else{
	                    $check = mysqli_stmt_error($stmt);
	                }
	            }else{
	                    $check = "Something went wrong2. Please try again later.";
	                }
	            mysqli_stmt_close($stmt);
	        }
	    mysqli_close($link);
	    }
	}
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">
<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="image/Eorder-logo.png">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>UOrder website</title>

	<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet">
		<!--
		CSS
		============================================= -->
		<link rel="stylesheet" href="css/linearicons.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/jquery.DonutWidget.min.css">
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/owl.carousel.css">
		<link rel="stylesheet" href="css/main.css">
		<!-- validation form -->
	</head>
	<body>

		<!-- Start Header Area -->
		<header class="default-header">
			<nav class="navbar navbar-expand-lg  navbar-light">
				<div class="container">
						<a class="navbar-brand" href="index.php">
							<img src="images/icon/linkedin_banner_image_1.png" width="120px" height="40px"alt="">
						</a>
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>

						<div class="collapse navbar-collapse justify-content-end align-items-center" id="navbarSupportedContent">
							<ul class="navbar-nav">
							<li><a href="#home">Home</a></li>
							<li><a href="#about">Service</a></li>
							<li><a href="#project">project</a></li>
							<li><a href="#team">about</a></li>
							<li><a href=""class="login-trigger" data-target="#V_login" data-toggle="modal"><i class="fa fa-user"aria-hidden="true" style="padding:0px 14px 0px 14px;"></i></a></li>
							</ul>
						</div>
				</div>
			</nav>
		</header>
		<!-- start login/register form-->
		<div id="V_login" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body" id="panel_loginV" style="display:block;">
						<button data-dismiss="modal" class="close">&times;</button>
						<h4 class="btn-title">Login</h4>
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  method="post">
							<input type="text" name="username" class="username form-control" placeholder="Username"/ style="background: #b16a0040;">
							<span><?php echo $username_err; ?></span>
							<input type="password" name="password" class="password form-control" placeholder="password"/ style="background: #b16a0040;">
							<span><?php echo $password_err; ?></span>
							<div class="below">
								<p style="margin-top:50px; margin-bottom:2px;color:black;">Don't have an account? <a href="#V_register" onclick="ChangePanel()">Sign up now</a>.</p>
								<p style="margin-top:0;margin-bottom:2px;color:black;">Are you a user? <a href="Customer/index.php">Login here!</a>.</p>
								<span><?php echo $check; ?></span>
							</div>
							<input class="btn login" type="submit" name="login" value="Login" />
						</form>
					</div>
					<div class="modal-body" id="V_register"  style="display:none;">
						<button data-dismiss="modal" class="close">&times;</button>
						<h4 class="btn-title">Register</h4>
						<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  method="post">
							<input type="text" name="username"style="margin:14px 0 0 0 ;" class="username form-control" placeholder="Username.." ><br>
							<span><?php echo $username_err; ?></span>

							<input type="email" name="email" class="username form-control" placeholder="Email.." style="margin:0;" ><br>
							<span><?php echo $email_err; ?></span>

							<input type="tel" name="phonenum" class="username form-control" placeholder="Phone Number.." style="margin:0;"><br>
							<span><?php echo $phonenum_err; ?></span>

							<input type="password" name="password" class="password form-control" placeholder="Password.." style="margin:0;"><br>
							<span><?php echo $password_err; ?></span>

							<input type="password" name="confirmpassword" class="password form-control" placeholder="Confirm Password.."style="margin:0;" ><br>
							<span><?php echo $confirm_password_err; ?></span>
							<div class="below">
								<p style="margin-top:30px;margin-bottom:2px;color:black;">Do have an account? <a href="#panel_loginV" onclick="ChangePanel_2()">Sign in now</a>.</p>
								<p style="margin-top:0px; margin-bottom:2px; color:black;">Are you a user? <a href="customer/loginuser.php">Login here!</a>.</p>
								<span><?php echo $check; ?></span>
							</div>
							<input class="btn login" type="submit" name="register" value="Register"  style="top:80%;left:75%;"/>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- end nav-->
		<!-- End Header Area -->

		<!-- start banner Area -->
		<section class="banner-area relative" id="home" data-parallax="scroll" data-image-src="image/wall2.jpg" width="100%">
			<div class="overlay-bg overlay"></div>
			<div class="container">
				<div class="row fullscreen  d-flex align-items-center justify-content-end">
					<div class="banner-content col-lg-12 " align="center" >
						<h1>
							We Provide  <br>
							<span>Solutions</span> that <br>
							Brings <span>Joy</span>
						</h1>
						<a href="" data-target="#V_login" data-toggle="modal" class="primary-btn2 header-btn text-uppercase">START NOW!</a>
					</div>
				</div>
			</div>
		</section>
		<!-- End banner Area -->


		<!-- Start About Area -->
		<section class="about-area" id="about" style="background: linear-gradient(110deg, white , white,#ff980094 80%); height: 90vh; ">
			<div class="container-fluid">
				<div class="row justify-content-end align-items-center d-flex no-padding">
					<div class="col-lg-6 about-left mt-70" style="padding-top: 50px;">
						<h1 style="color:orange;">Restaurant ordering system website <br>
						<span style="color:black;"> that design to help you thrive</span></h1>
						<p style="color:#200;">
							An exceptional restaurant ordering system with security feature
							that fits your business, EORDER is ideal for managin full-service rautaurants,
							food court and more. No matter what type of Food and Beverage business you own,
							EORDER is the completely to streamline restaurant activities.
						</p>
						<div class="buttons">
							<a href="#service" class="about-btn text-uppercase primary-border circle">What we offer</a>
							<a href="" data-target="#V_login" data-toggle="modal" class="about-btn text-uppercase  primary-border circle">start now</a>
						</div>
					</div>
					<div class="col-lg-6 about-right" style="padding-top: 150px;">
						<img class="img-fluid" src="image/hardware-bundle.png" alt="">
					</div>
				</div>
			</div>
		</section>
		<!-- End About Area -->


		<!-- start service Area-->
		<section class="service-area pt-100 pb-150" id="service">
			<div class="container">
				<div class="row d-flex justify-content-center">
					<div class="menu-content pb-70 col-lg-8">
						<div class="title text-center">
								<h1> Services Of our <span style="color:#ff9800;">Restaurant Management System</span></h1>
							<p>Some of the amazing services to improve the use of the product and introduce new features.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="sigle-service col-lg-3 col-md-6" style="height:450px; padding:20px 40px;">
						<span>
						<img class="bg-1-1"src="image/menu-management.png" width="200px" height="auto"></span>
						<h4>Menu Management</h4>
						<p>
							Easy to use menu management system gives you the flexibility
to manage the menu as per the varied needs of your guests.
						</p>
						<a href="#" class="text-uppercase primary-btn2 primary-border circle">View Details</a>
					</div>
					<div class="sigle-service col-lg-3 col-md-6" style="border-radius:8px; height:450px; padding:20px 40px;">
						<span>
						<img class="bg-1-1"src="image/menu-management.png" width="200px" height="auto">
						</span>
						<h4>Table Management</h4>
						<p>
							Let you mark which tables are served by walk-in or reserved by book.
						</p>
						<br>
						<br>
						<a href="#" class="text-uppercase primary-btn2 primary-border circle" style="margin-top:30px;">View Details</a>
					</div>
					<div class="sigle-service col-lg-3 col-md-6" style="border-radius:8px; height:450px; padding:20px 40px;">
						<span>
						<img class="bg-1-1"src="image/menu-management.png" width="200px" height="auto">
						</span>
						<h4>User Privileges</h4>
						<p>
							easily limit the access of users on your Restaurant Management System.
						</p>
						<br>
						<br>
						<a href="#" class="text-uppercase primary-btn2 primary-border circle">View Details</a>
					</div>
					<div class="sigle-service col-lg-3 col-md-6" style="border-radius:8px; height:450px; padding:20px 40px;">
						<span>
						<img class="bg-1-1"src="image/menu-management.png" width="200px" height="auto">
						</span>
						<h4>Reporting and Analysis</h4>
						<p>
							rack various staff activities to ensure the smooth functioning of
							your restaurant.
						</p>
						<a href="#" class="text-uppercase primary-btn2 primary-border circle">View Details</a>
					</div>

				</div>
			</div>
		</section>
		<!-- end service Area-->


		<!-- Start About Area2 -->
		<section class="about-area" style="background: #ff980094; height: 90vh;">
			<div class="container-fluid">
				<div class="row justify-content-end align-items-center d-flex no-padding">
					<div class="col-lg-6 about-left mt-70" >
						<h1 style="color:black;"> <span style="color:black;font-size:49px;">Don't worry</span> we'll protect you <br>
						<span style="color:gray;"> we secure our system</span></h1>
						<br>
						<div class="buttons">
							<a href="#project" class="about-btn text-uppercase primary-border circle">More features</a>
						</div>
					</div>
					<div class="col-lg-6 about-right" style="padding-top: 50px;">
						<img class="img-fluid" src="image/secure.png" alt="">
					</div>
				</div>
			</div>
		</section>
		<!-- End About Area2 -->


		<!-- Start project Area -->
		<section class="project-area section-gap" id="project">
			<div class="container">
				<div class="row d-flex justify-content-center">
					<div class="menu-content pb-40 col-lg-8">
						<div class="title text-center">
							<h1 class="mb-10">Our feature <span style="color:#ff9800;"> Security </span>provided</h1>
							<p>The information security awareness has been increases. <br> Many organizations have implemented the information security to protect their data.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="active-works-carousel mt-40">
						<div class="item">
							<img class="img-fluid" src="image/encryption end-2-end.png" alt="" style="background:#fceaa2;height:415px;">
							<div class="caption text-center mt-20">
								<h6 class="text-uppercase">Encryption End-to-End</h6>
								<p>These systems can only guarantee the protection of communications between clients and servers <br> It promise that sensitive information are encrypted in a way that allows only clients and us.</p>
							</div>
						</div>
						<div class="item">
							<img class="img-fluid" src="image/secure-database.png" alt="" style="background:#fceaa2;height:415px;">
							<div class="caption text-center mt-20">
								<h6 class="text-uppercase">Secure database</h6>
								<p>SQL Injection attacks are one of the oldest, most prevalent, and most dangerous web application vulnerabilities.</p>
							</div>
						</div>
						<div class="item">
							<img class="img-fluid" src="image/restricteduser.jpg" alt=""style="background:#fceaa2;height:415px;">
							<div class="caption text-center mt-20">
								<h6 class="text-uppercase">Restriction user</h6>
								<p>
								Easily share or restrict
								the access in accordance with your requirements and also
								a well-administered and secure structure.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End project Area -->


		<!-- Start team Area -->
		<section class="team-area section-gap" id="team" style="background: #EBE9E1;">
			<div class="container">
				<div class="row d-flex justify-content-center">
					<div class="menu-content pb-70 col-lg-8">
						<div class="title text-center">
							<h1 class="mb-10">About EORER System Team</h1>
							<p>Who are in extremely developed EORDER system.</p>
						</div>
					</div>
				</div>
				<div class="row justify-content-center d-flex align-items-center">
					<div class="col-md-3 single-team">
							<div class="thumb">
									<img class="img-fluid" src="img/t1.jpg" alt="">
									<div class="align-items-center justify-content-center d-flex">
								<a href="#"><i class="fa fa-facebook"></i></a>
								<a href="#"><i class="fa fa-twitter"></i></a>
								<a href="#"><i class="fa fa-linkedin"></i></a>
									</div>
							</div>
							<div class="meta-text mt-30 text-center">
								<h4>Ethel Davis</h4>
								<p>Managing Director (Sales)</p>
							</div>
					</div>
					<div class="col-md-3 single-team">
							<div class="thumb">
									<img class="img-fluid" src="img/t2.jpg" alt="">
									<div class="align-items-center justify-content-center d-flex">
								<a href="#"><i class="fa fa-facebook"></i></a>
								<a href="#"><i class="fa fa-twitter"></i></a>
								<a href="#"><i class="fa fa-linkedin"></i></a>
									</div>
							</div>
							<div class="meta-text mt-30 text-center">
								<h4>Rodney Cooper</h4>
								<p>Creative Art Director (Project)</p>
							</div>
					</div>
					<div class="col-md-3 single-team">
							<div class="thumb">
									<img class="img-fluid" src="img/t3.jpg" alt="">
									<div class="align-items-center justify-content-center d-flex">
								<a href="#"><i class="fa fa-facebook"></i></a>
								<a href="#"><i class="fa fa-twitter"></i></a>
								<a href="#"><i class="fa fa-linkedin"></i></a>
									</div>
							</div>
							<div class="meta-text mt-30 text-center">
								<h4>Dora Walker</h4>
								<p>Senior Core Developer</p>
							</div>
					</div>

				</div>
			</div>
		</section>
		<!-- End team Area -->


		<!-- start footer Area -->
		<!-- Footer -->
<footer class="page-footer font-small unique-color-dark pt-4" style="background:#141313;">

<!-- Footer Elements -->
<div class="container">

	<!-- Call to action -->
	<ul class="list-unstyled list-inline text-center py-2">
		<li class="list-inline-item">
			<h5 class="mb-1" style="color:white;">Register for free</h5>
		</li>
		<li class="list-inline-item">
			<div class="col-lg-4 col-md-12">
				<button class="nw-btn primary-btn">resgister<span class="lnr lnr-arrow-right"></span></button>
			</div>
		</li>
	</ul>
	<!-- Call to action -->

</div>


		<script type="text/javascript">
			function ChangePanel(){
				document.getElementById("V_register").style.display = "block";
				document.getElementById("panel_loginV").style.display = "none";
			}
			function ChangePanel_2(){
				document.getElementById("V_register").style.display = "none";
				document.getElementById("panel_loginV").style.display = "block";
			}
		</script>
		<script src="js/vendor/jquery-2.2.4.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
		<script src="js/vendor/bootstrap.min.js"></script>
		<script src="js/jquery.ajaxchimp.min.js"></script>
		<script src="js/parallax.min.js"></script>
		<script src="js/owl.carousel.min.js"></script>
		<script src="js/jquery.sticky.js"></script>
		<script src="js/jquery.DonutWidget.min.js"></script>
		<script src="js/jquery.magnific-popup.min.js"></script>
		<script src="js/main.js"></script>
	</body>
</html>

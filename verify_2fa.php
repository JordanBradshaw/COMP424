<?php
include('include/conn.php');
require_once 'include/GoogleAuthenticator.php';
$gauth = new GoogleAuthenticator();

if(empty($_SESSION['user_id']))
{
	echo "<script> window.location = 'index.php'; </script>";
}
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tbl_users WHERE user_id='$user_id'";
$stmt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt,$sql)){
        	exit('SQL Error');
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
		if($user_row = mysqli_fetch_assoc($result)){
		$secret_key    = $user_row['google_auth_code'];
		$email         		= $user_row['email'];
		$google_QR_Code 	= $gauth->getQRCodeGoogleUrl($email, $secret_key,'COMP424');
	} else{
	echo "fail";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>2-Step Verification using Google Authenticator</title>
		<link rel="stylesheet" type="text/css" href="css/app_style.css" charset="utf-8" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	<body>
	<div class="container">
		<div class="row-fluid">
			<div class="col-md-auto" >
			<p>Verify with Google Authenticator application associated with your account on your smart phone.</p>
			<form id="verify2fa-form">
			<input type="hidden" id="process_name" name="process_name" value="verify_code" />
				<div class="form-group">
					<label for="email">Place your code here:</label>
					<input type="text" name="scan_code" class="form-control" id="scan_code" required />
				  </div>
				  
				<input type="button" class="btn btn-success btn-submit" value="Verify Code"/>
			</form>
			</div>
			<div style="text-align:center">
				<h6>Download Google Authenticator <br/> application using this link(s),</h6>
			<a href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank"><img class='app' src="images/iphone.png" /></a>
			<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank"><img class="app" src="images/android.png" /></a>
			</div>
		</div>
	</div>
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>
	<script>
		$(document).ready(function(){
			/* submit form details */
			$(document).on('click', '.btn-submit', function(ev){
				if($("#verify2fa-form").valid() == true){
					var data = $("#verify2fa-form").serialize();
					$.post('include/check_user.php', data, function(data,status){
						console.log("Submitting result ====> Data: " + data + "\nStatus: " + status);
						if( data == "Verify 2FA Success"){
							window.location = 'logged_in.php';
						}
						else{
							alert("Invalid Google Authenticator Code!");
						}
						
					});
				}
			});
		});
	</script>
	</body>
</html>

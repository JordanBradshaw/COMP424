
<?php

	
	/*$recaptcha_secret ="6Ld7hdoZAAAAAOMe8qT8taElXJNjunBjXftceHLe"; // Add secret key from website
	$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$_POST[g-recaptcha-response]);
	$response = json_decode($response,true);
	if($response["success"] == true)
{
	//correct loging code here
	debug.log("test");
}*/

	
// find class="g-recaptcha" data-sitekey="" and change to site key below
	
?>
<head>
	<script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<form method ="post" action ="form.php">
	<p>Captcha:</p>
	<div class="g-recaptcha" data-sitekey="6Ld7hdoZAAAAAJU-5NRUaR5Yjdql42oEkmjZtUeP"></div>
	<p><input type="submit" value="Submit" name ="submit"></p>
</form>
</body>
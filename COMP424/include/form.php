<?php

session_start();

if( isset($_SESSION['captcha']) and $_POST['my-captcha']== $_SESSION['captcha']){
	unset($_SESSION['captcha']);
	echo "Correct captcha!";
}
else
echo("nope!");

?>
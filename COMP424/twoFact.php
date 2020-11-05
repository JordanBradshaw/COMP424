<html>
<head>
	<link rel="stylesheet" href="include/styles.css">
	<style></style>
</head>
<body>
<main>

<?php
require "include/424Project.php";

if(isset($_GET['email'])){
        $uid=$_GET['email'];
}

echo $uid;

$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

function generate_string($input, $strength = 16) {
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }

    return $random_string;
}

$authStr=generate_string($permitted_chars, 10);

//echo $authStr;

$to="emmanoeld97@gmail.com"; //gonna change this to =$row['email']; or its equivalent
//$to      = 'nobody@example.com';
$subject = "Authorization Code";
$message = "This is your authorization code ";
$message .= $authStr;
$headers = "From:Test@Test.com";
mail($to, $subject, $message, $headers);

//echo $returnVal;

//if ($returnVal == true) {
//      echo "message sent";
//}
//else {
//      echo "message NOT sent";
//}

echo '<h1 style="text-alighn:center">Two-Factor Authentication</h1>
        <form method="post" action="include/testAuth.php">
                <label for="validationKey"Validation Key: </label> <br />
                <input type="text" id="validation" value="" name="validationString" />
                <input type="hidden" name="username" value="<?php echo $_GET['username']?>">

                <input type="button" id="authenticateButton" value="Authenticate" name="authenticateButton" />
        </form>'?>
</main>
</body>
</html>

<html>
<body>
<main>

<?php

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
$returnVal = mail($to, $subject, $message, $headers);
$returnVal = mail("emmanoeld97@gmail.com","test2","hey there");

echo $returnVal;

if ($returnVal == true) {
      echo "message sent";
}
else {
      echo "message NOT sent";
}

?>
</main>
</body>
</html>

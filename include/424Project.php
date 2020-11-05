<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "demo424";
$db = "COMP424";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
if (!$conn){
die("Connection failed: ".mysqli_connect_error());
}

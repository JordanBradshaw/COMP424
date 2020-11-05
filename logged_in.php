


<?php require "include/topNav.php";?>
<html>
<head>
<link rel="stylesheet" href="include/styles.css">
<style>
  </style>
</head>
<body>
<main>
<<?php
include('include/conn.php');
if(empty($_SESSION['user_id']))
{
	echo "<script> window.location = 'index.php'; </script>";
}
$user_id = $_SESSION['user_id'];
?>
if(isset($_SESSION['user_id'])){
if ($_SESSION['NAMEuser'] == "Administrator"){
  echo '<br><h1 align="center">Welcome Administrator!</h1><br><table align="center" style="border:1px solid black; border-collapse: collapse;"><tbody><tr style="border:1px solid black"><th>UserID</th><th>Username</th><th>Password</th><tr>';
  $sql = "SELECT * from users ORDER BY username ASC";
	$result = mysqli_query($conn, $sql);
	while ($row = mysqli_fetch_row($result)) {
		print("<tr><td>");
		print($row[0]);			// $row[0] is the first col (artistId)
		print("</td><td>");
    print($row[1]);			// $row[1] is the second col (name)
    print("</td><td>");
		print($row[2]);			// $row[1] is the second col (name)
		print("</td></tr>");
	}
echo '</tbody></table>';
}
else{
   echo "<p> Welcome fam ";
   echo $_SESSION[NAMEuser];
   echo " with a ID# of ";
   echo $_SESSION[IDuser];
   echo "</p><br>";
   echo "<p>BRING ON THE VIBES ->";
   echo '<input type="checkbox" id="rave" name="rave" value="rave">';
}}else{
echo ' <h3>Login Status: </h3><h3 id="response"></h3><br> <h1 style="text-align:center">~QUARANTINE 484 RAVE~</h1>
<h2>Account Creation Form</h2>
<form method="post" action="include/signupSubmit.php">
  <label>
    Username: <br />
    <input type="text" id="usernameField" value="" name="usernameString" />
    <br />
  <label for="password">Password: </label><br />
  <input type="password" id="passwordField" name="passwordString"/><br />
  <input type="button" id="signupButton" value="Submit" name="signupButton" />
</form>';
}?>
</main>

</body>
</html>
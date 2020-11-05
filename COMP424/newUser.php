<?php require "include/topNav.php";?>
<html>
<head>
<link rel="stylesheet" href="include/styles.css">
<style>
  </style>
</head>
<body>

<main>
<?php
require "include/424Project.php";
if(isset($_SESSION['IDuser'])){
if ($_SESSION['NAMEuser'] == "Administrator"){
  echo '<br><h1 align="center">Welcome Administrator!</h1><br><table align="center" style="border:1px solid black; border-collapse: collapse;"><tbody><tr style="border:1px solid black"><th>UserID</th><th>Username</th><th>Password</th><th>Phone Number</th><th>Email</th><tr>';
  $sql = "SELECT * from COMP424DB ORDER BY username ASC";
	$result = mysqli_query($conn, $sql);
	while ($row = mysqli_fetch_row($result)) {
		print("<tr><td>");
		print($row[0]);			// $row[0] is the first col (Id)
		print("</td><td>");
    print($row[1]);			// $row[1] is the second col (name)
    print("</td><td>");
    print($row[2]);			// $row[1] is the second col (password)
    print("</td><td>");
    print($row[3]);			// $row[1] is the second col (phone)
    print("</td><td>");
		print($row[4]);			// $row[1] is the second col (email)
		print("</td></tr>");
	}
echo '</tbody></table>';
}
else{
   echo "<p>Already have an account: ";
   echo $_SESSION[NAMEuser];
   echo " with a database ID# of ";
   echo $_SESSION[IDuser];
   echo "</p><br>";
}}else{
echo ' <h1 style="text-align:center">Account Creation Form:</h1>
<form method="post" action="include/signupSubmit.php">
  <label for="username">Username: <br />
  <input type="text" id="usernameField" value="" name="usernameString" /><br />

  <label for="password">Password: </label><br />
  <input type="password" id="passwordField" name="passwordString"/><br />

  <label for="cell">Cell Phone: </label><br />
  <input type="tel" id="phoneField" name="phoneString" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"><br />

  <label for="email"> Email: </label><br />
  <input type="email" id="emailField" name="emailString"><br />





  <input type="button" id="signupButton" value="Submit" name="signupButton" />
</form><br>
<h3>Login Status: </h3><h3 id="response"></h3>';}?>
</main>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script type="text/javascript">
$(document).ready(function () {
$("#loginButton").on('click', function () {
var usernameajax = $("#usernameNav").val();
var passwordajax = $("#passwordNav").val();
console.log(usernameajax);
console.log(passwordajax);
if(usernameajax == "" || passwordajax == "") {
  alert ('Check your input fields');
  }else{
$.ajax(
{
  url: 'include/loginSubmit.php',
  method: 'POST',
  data:{
    login: 1,
    usernameText: usernameajax,
    passwordText: passwordajax
  },
  success: function(response){
    $('#response').html(response);
    if (response.indexOf('Success') >= 0){
      window.location = 'index.php';
    }
  console.log(response);
  },
  dataType:'text'
}
);
}});
});
</script>

<script type="text/javascript">
$(document).ready(function () {
$("#signupButton").on('click', function () {
var usernameajax2 = $("#usernameField").val();
var passwordajax2 = $("#passwordField").val();
var phoneajax2 = $("#phoneField").val();
var emailajax2 = $("#emailField").val();
console.log(usernameajax2);
console.log(passwordajax2);
if(usernameajax2 == "" || passwordajax2 == "" || phoneajax2 == "" || emailajax2 == "") {
  alert ('Check your input fields');
  }else{
$.ajax(
{
  url: 'include/signupSubmit.php',
  method: 'POST',
  data:{
    newName: 1,
    usernameString: usernameajax2,
    passwordString: passwordajax2,
    phoneString: phoneajax2,
    emailString: emailajax2
  },
  success: function(response){
    $('#response').html(response);
    if (response.indexOf('Success') >= 0){
      window.location = 'index.php';
    }
  console.log(response);
  },
  dataType:'text'
}
);
}});
});
</script>

<script type="text/javascript">
$(document).ready(function () {
$("#logoutButton").on('click', function () {
$.ajax(
{
  url: 'include/logoutSubmit.php',
  method: 'POST',
  success: function(response){
    $('#response').html(response);
    if (response.indexOf('Logout Success') >= 0){
      window.location = 'index.php';
    }
  console.log(response);
  },
  dataType:'text'
}
);
}});
});
</script>
</body>
</html>

 <?php
 session_start();
 ?>
 <div class="topnav">
 <!-- Always put the parent tag of the child tags
   Such as you missed the <ul> tag that is parent tag
   of <li> tag -->
 <ul>
  <li><a href="index.php">Home</a></li>
  <li><a href="newUser.php">New User</a></li>
 </ul>
 <div class="topnav-right">
<?php
  
if(isset($_SESSION['IDuser'])){
  echo '<form action="include/logoutSubmit.php">
   <button type="submit" id="logoutButton" name="logoutButton">Logout</button>
    </form>';
  }else{
  echo '
  <form method="post" >
    <input type="text" id="usernameNav" placeholder="Username" name="usernameText">
    <input type="password" id="passwordNav" placeholder="Password" name="passwordText">
    <button type="button" id="loginButton" name="loginButton">Login</button>
    </form>';
  }
  ?>
  
  </div>
</div> 

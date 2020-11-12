<?php
	include("include/conn.php");

	if (isset($_POST['submit'])) {
		if(!isset($_POST['secQues'])) { 
			//this is meant to redirect to the forgot_pass page if they just got here using the link but it doesnt work
			header("Location:forgot_pass.php");
			exit;
		}
		else {
			$inputAns = $_POST['secQues'];
			//header("Location:forgot_pass.php");
			$emailDest = $_SESSION['passingE'];
			$sql = "SELECT * FROM tbl_users WHERE email='$emailDest'";
			$stmt = mysqli_stmt_init($conn);
			if(!mysqli_stmt_prepare($stmt,$sql)) {
				exit('SQL ERROR');
			}
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			if($user_row = mysqli_fetch_assoc($result)) {
			}
			//echo $user_row['security_answer'];
			if (strcmp($inputAns, $user_row['security_answer']) == 0) {
				echo "<br><br>";
				echo "This is the hashed password we need to email or use to log them in <br><br>";
				echo $user_row['password'];
			}
		}
	}

    
?>


<!doctype html>
<html>
<head>
    <title>Forgot password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/app_style.css" charset="utf-8" />
</head>
<body>
    <div class="container">
        <div class="row-fluid">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Password Recovery</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class=" col-md-auto">
                            <table class="table table-user-information">
                                    <tbody>
                                        <tr>
						<td>
						<form action="emailPass.php" method="post">
							<?php echo $user_row['security_question']?>
							<input type="email" name="emailSub" value="" />
						    <input type="submit" name="submit" value="Submit" />
       						 </form>
						

						</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="row">                               
                                                            
                                </div></br>
                                <div class="row justify-content-end"> 
                                <a href="index.php">
                                <button type="button" style="float: right; margin-right:8%;" class="btn btn-secondary">Back</button>
                                </div>
                                </a>
                            </div>
                    </div>
            
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>

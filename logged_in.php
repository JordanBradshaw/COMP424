<?php
include("include/conn.php");
if ($_SESSION['user_valid'] != 'True')) {
    echo "<script> window.location = 'index.php'; </script>";
}
$id = $_SESSION['user_id'];
$user_result = mysql_query("select * from users_info where id='$id'") or die(mysql_error());
$user_row = mysql_fetch_array($user_result);
?>
<!doctype html>
<html>
<head>
    <title>SoftAOX | Google Two Factor Authentication Sign In and Join Now using jQuery, PHP &amp; MySQL</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/app_style.css" charset="utf-8" />
</head>
<body>
    <div class="container">
        <div class="row-fluid">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Hi, <?php echo $user_row['user_name']; ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3 col-lg-3 " align="center">
                                <img alt="User Pic" src="img/user_icon.png" class="img-circle img-responsive">
                            </div>
                            <div class=" col-md-9 col-lg-9 ">
                                <table class="table table-user-information">
                                    <tbody>
                                        <tr>
                                            <td>User Name:</td>
                                            <td>
                                                <?php echo $user_row[ 'user_name']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Email:</td>
                                            <td>
                                                <?php echo $user_row[ 'email']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Google Authenticator Code:</td>
                                            <td>
                                                <?php echo $user_row[ 'google_authentication']; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="logout.php">
                                    <button type="button" class="btn btn-primary">Logout</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>

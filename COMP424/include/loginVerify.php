<?php
include('conn.php');
require_once 'googleLib/GoogleAuthenticator.php';
$gauth = new GoogleAuthenticator();
 
if (empty($_SESSION['id'])) {
    echo "<script> window.location = 'index.php'; </script>";
}
 
$id = $_SESSION['id'];
$user_result = mysql_query("select * from users_info where id='$id'") or die(mysql_error());
$user_row = mysql_fetch_array($user_result);
 
$secret_key = $user_row['google_authentication'];
$email      = $user_row['email'];
 
$google_QR_Code = $gauth->getQRCodeGoogleUrl($email, $secret_key, 'SoftAOX');
?>
<!doctype html>
<html>
<head>
    <title>SoftAOX | Google Two Factor Authentication Sign In and Join Now using jQuery, PHP &amp; MySQL</title>
    <link rel="stylesheet" type="text/css" href="css/app_style.css" charset="utf-8" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
    <div id="container">
        <h2 align="center">Google Two Factor Authentication Sign In and Join Now using jQuery, PHP &amp; MySQL</h2>
        <div id='device'>
            <p align="center">Scan Your QR Code With Google Authenticator APP On Your Smart Phone.</p>
            <div class="col-md-6 col-md-offset-3" align="center">
                <div id="img"><img src='<?php echo $google_QR_Code; ?>' /></div>
                <form id="LI-form">
                    <input type="hidden" id="process_name" name="process_name" value="verify_code" />
                    <div class="form-group">
                        <label for="email">Place your code here:</label>
                        <input type="text" name="scan_code" class="form-control" id="scan_code" required />
                    </div>
                    <input type="button" class="btn btn-success btn-submit" value="Verify Code" />
                </form>
                <div style="text-align:center">
                    <h5>Download Google Authenticator on the App Store</h5>
                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank" rel="noopener noreferrer"><img class="app" src="img/g_play_store.png" /></a>
 
                    <a href="https://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank" rel="noopener noreferrer"><img class='app' src="img/g_ios.png" /></a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-submit', function(ev) {
                if ($("#LI-form").valid() == true) {
                    var data = $("#LI-form").serialize();
                    $.post('user_validation.php', data, function(data, status) {
                        console.log("submitnig result ====> Data: " + data + "\nStatus: " + status);
                        if (data == "done") {
                            window.location = 'home.php';
                        } else {
                            alert("not done");
                        }
        
                    });
                }
            });
        });
    </script>
</body>
</html>
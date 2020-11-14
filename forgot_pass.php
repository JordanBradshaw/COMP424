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
                                                <form action="sendRet.php" method="post">
                                                    Email: <input type="email" name="emailSub" value="" />
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

<!DOCTYPE html>
<html>
<head>
    <title>2-Step Verification using Google Authenticator</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
    <link rel="stylesheet" type="text/css" href="css/app_style.css" charset="utf-8" />
    <style>
	meter {
	  /* Reset the default appearance */
	  -webkit-appearance: none;
	     -moz-appearance: none;
	          appearance: none;

	  margin: 0 auto 1em;
	  width: 100%;
	  height: 0.5em;

	  /* Applicable only to Firefox */
	  background: none;
	  background-color: rgba(0, 0, 0, 0.1);
	}

	meter::-webkit-meter-bar {
	  background: none;
	  background-color: rgba(0, 0, 0, 0.1);
	}

	/* Webkit based browsers */
	meter[value="1"]::-webkit-meter-optimum-value { background: red; }
	meter[value="2"]::-webkit-meter-optimum-value { background: yellow; }
	meter[value="3"]::-webkit-meter-optimum-value { background: orange; }
	meter[value="4"]::-webkit-meter-optimum-value { background: green; }

	/* Gecko based browsers */
	meter[value="1"]::-moz-meter-bar { background: red; }
	meter[value="2"]::-moz-meter-bar { background: yellow; }
	meter[value="3"]::-moz-meter-bar { background: orange; }
	meter[value="4"]::-moz-meter-bar { background: green; }

    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>


</head>
<body>

<div class="container" style="margin-top:5%;background-color:lightblue;max-width:500px;">
	<div class="row-fluid">
		<div class="col-md-auto" style="max-width:500px;margin:auto;">
			<div class="form-body">
                <ul class="nav nav-tabs final-login">
                    <li class="active">
						<a data-toggle="tab" href="#sectionA">Sign Up!</a>
                    </li>
                    <li>
						<a data-toggle="tab" href="#sectionB">Log In!</a>
                    </li>
                </ul>
				<div class="tab-content">
					<div id="sectionA" class="tab-pane fade in active">
			<!--<h3>Registration</h3>-->
					<div class="inner-form">
						<form name="signup-form" id="signup-form">
							<input type="hidden" id="process_name" name="process_name" value="user_register" />
			  					<div class="errorMsg errorMsgReg"></div>
			  					<div class="form-group">
									<label for="name">Full Name:</label>
										<input type="text" name="reg_name" class="form-control" id="reg_name" required />
			  					</div>
								  <div class="form-group">
									<label for="birthday">Birthday:</label>
									<input type="date" name="reg_birthday" class="form-control" id="reg_birthday" required />
			  					</div>
			  					<div class="form-group">
									<label for="email">Email:</label>
									<input type="email" name="reg_email" class="form-control" id="reg_email" required />
			  					</div>
			  					<div class="form-group">
									<label for="password">Password:</label>
									<input type="password" name="reg_password" class="form-control" id="reg_password" required />
									<meter max="4" id="password-strength-meter"></meter>
									<p id="password-strength-text"></p>
								</div>

								<script type="text/javascript">
									var strength = {
									        0: "Worst",
									        1: "Bad",
									        2: "Weak",
										3: "Good",
									        4: "Strong"
									}
									var password = document.getElementById('reg_password');
									var meter = document.getElementById('password-strength-meter');
									var text = document.getElementById('password-strength-text');

									password.addEventListener('input', function() {
										var val = password.value;
										var result = zxcvbn(val);

									// Update the password strength meter
									meter.value = result.score;

									// Update the text indicator
									if (val !== "") {
										text.innerHTML = "Strength: " + strength[result.score]; 
									} else {
										text.innerHTML = "";
									}
									});
								</script>

								<div class="form-group">
									<label for="password2">Re-enter Password:</label>
									<input type="password" name="reg_password2" class="form-control" id="reg_password2" required />
			  					</div>
			  				<button type="button" class="btn btn-primary btn-reg-submit">Submit</button>
						</form>
						<div class="clearfix"></div>
					</div>
					</div>
					<div id="sectionB" class="tab-pane fade">
					<div class="inner-form">
			<!--<h3>Login</h3>-->
						<form name="login-form" id="login-form">
							<input type="hidden" id="process_name" name="process_name" value="user_login" />
			  					<div class="errorMsg errorMsgReg"></div>
			  					<div class="form-group">
									<label for="login_email">Email:</label>
									<input type="email" name="login_email" class="form-control" id="login_email" required />
			  					</div>
			  					<div class="form-group">
									<label for="login_password">Password:</label>
										<input type="password" name="login_password" class="form-control" id="login_password" required />
			  					</div> 
			  					<button type="button" class="btn btn-success btn-login-submit">Login</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>

<script>
	$(document).ready(function(){
		/* submit form details */
		$(document).on('click', '.btn-reg-submit', function(ev){
			if($("#signup-form").valid() == true){
				var data = $("#signup-form").serialize();
				$.post('check_user.php', data, function(data,status){
					console.log("Submitting Result => Data: " + data + "\nStatus: " + status);
					if( data == "Username Created"){
						window.location = 'save_2fa.php';
					}
					else{
						alert("not done");
					}
					
				});
			}
		});
		/* ebd submit form details */
		
		/* submit form details */
		$(document).on('click', '.btn-login-submit', function(ev){
			if($("#login-form").valid() == true){
				var data = $("#login-form").serialize();
				$.post('check_user.php', data, function(data,status){
					console.log("Submitting Result => Data: " + data + "\nStatus: " + status);
					if( data == "done"){
						window.location = 'user_confirm.php';
					}
					else{
						alert("not done");
					}
					
				});
			}
		});
		/* ebd submit form details */
	});
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login to Mary using your Westminster Account</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
		<style>
	/*
	 * Specific styles of signin component
	 */
	/*
	 * General styles
	 */
	body, html {
			height: 100%;
			background-repeat: no-repeat;
			background-image: url('img/yard.jpg');
			background-size: cover;
	}

	.card-container.card {
			max-width: 350px;
			padding: 40px 40px;
	}

	.btn {
			font-weight: 700;
			height: 36px;
			-moz-user-select: none;
			-webkit-user-select: none;
			user-select: none;
			cursor: default;
	}

	/*
	 * Card component
	 */
	.card {
			background-color: #F7F7F7;
			/* just in case there no content*/
			padding: 20px 25px 30px;
			margin: 0 auto 25px;
			margin-top: 50px;
			/* shadows and rounded borders */
			-moz-border-radius: 2px;
			-webkit-border-radius: 2px;
			border-radius: 2px;
			-moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
			-webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
			box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
	}

	.profile-img-card {
			width: 96px;
			height: 96px;
			margin: 0 auto 10px;
			display: block;
			-moz-border-radius: 50%;
			-webkit-border-radius: 50%;
			border-radius: 50%;
	}

	/*
	 * Form styles
	 */
	.profile-name-card {
			font-size: 16px;
			font-weight: bold;
			text-align: center;
			margin: 10px 0 0;
			min-height: 1em;
	}

	.reauth-email {
			display: block;
			color: #404040;
			line-height: 2;
			margin-bottom: 10px;
			font-size: 14px;
			text-align: center;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			-moz-box-sizing: border-box;
			-webkit-box-sizing: border-box;
			box-sizing: border-box;
	}

	.form-signin #inputEmail,
	.form-signin #inputPassword {
			direction: ltr;
			height: 44px;
			font-size: 16px;
	}

	.form-signin input[type=username],
	.form-signin input[type=password],
	.form-signin input[type=text],
	.form-signin button {
			width: 100%;
			display: block;
			margin-bottom: 10px;
			z-index: 1;
			position: relative;
			-moz-box-sizing: border-box;
			-webkit-box-sizing: border-box;
			box-sizing: border-box;
	}

	.form-signin .form-control:focus {
			border-color: rgb(104, 145, 162);
			outline: 0;
			-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
			box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
	}

	.btn.btn-signin {
			/*background-color: #4d90fe; */
			background-color: rgb(104, 145, 162);
			/* background-color: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));*/
			padding: 0px;
			font-weight: 700;
			font-size: 14px;
			height: 36px;
			-moz-border-radius: 3px;
			-webkit-border-radius: 3px;
			border-radius: 3px;
			border: none;
			-o-transition: all 0.218s;
			-moz-transition: all 0.218s;
			-webkit-transition: all 0.218s;
			transition: all 0.218s;
	}

	.btn.btn-signin:hover,
	.btn.btn-signin:active,
	.btn.btn-signin:focus {
			background-color: rgb(12, 97, 33);
	}

	.forgot-password {
			color: rgb(104, 145, 162);
	}

	.forgot-password:hover,
	.forgot-password:active,
	.forgot-password:focus{
			color: rgb(12, 97, 33);
	}
	</style>
	<script>
	$(document).ready(function(){
		$("#loginbutton").click(function(){
			
			$("#loginbox").fadeOut(1000,function(){
				$("#loading").fadeIn();
			});
			$.ajax({
				url: "logincheck.php?username=" + $("#username").val() + "&password=" + $("#password").val()
				, success: function(result){
					if (result == "1") {
						window.location.href = "../";
					} else if (result == "2") {
						$("#loading").fadeOut(1000,function(){
							$("#message").text("Username of Password Incorrect");
							$("#loginbox").fadeIn();
						});
					} else {
						$("#loading").fadeOut(1000,function(){
							$("#loginbox").fadeIn();
						});
						bootbox.alert(result);
					}
				}, error: function(jqXHR, textStatus, errorThrown) {
					$("#loading").fadeOut(1000,function(){
						$("#loginbox").fadeIn();
					});
					bootbox.alert("Sorry - We could not connect to the server. Please check your internet connection and try again!")
				}
			});
			return false;
		});
	});
	</script>
</head>
<body>
	<div class="container">
		<div class="card card-container">
			<div id="loginbox">
				<img id="profile-img" class="profile-img-card" src="img/crest.png" />
				<p class="profile-name-card" id="message" style="margin-bottom: 10px;"></p>
				<form class="form-signin">
						<input type="username" id="username" class="form-control" placeholder="Westminster Username" required autofocus>
						<input type="password" id="password" class="form-control" placeholder="Westminster Password" required>
						<button class="btn btn-lg btn-primary btn-block btn-signin" id="loginbutton" type="submit">Sign in</button>
				</form>
			</div>
			<img id="loading" style="display: none;" class="profile-img-card" src="img/loading.gif" alt="Loading..."/>
		</div>
	</div>
</body>
</html>

<?php
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;
?>

<div class="container">
	<div class="login-form">
		<form>
			<h2 class="text-center">Login</h2>
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					<input id="input_username" type="text" class="form-control" placeholder="Username" required="required">
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-lock"></i></span>
					<input id="input_password" type="password" class="form-control" placeholder="Password" required="required">
				</div>
			</div>
			<div class="form-group">
				<button class="btn btn-primary login-btn btn-block" id="button_login">Log in</button>
			</div>
			<div class="clearfix">
				<a id="reset_pswd" href="" class="pull-right">Forgot Password?</a>
			</div>
		</form>
		<p class="text-center text-muted small">Don't have an account? <a href="index.php?p=signup">Sign up here!</a></p>
	</div>
</div>

<input type="hidden" name="token" id="token" value="<?= $token; ?>" />

<script type="text/javascript" src="./app/assets/js/login.js"></script>
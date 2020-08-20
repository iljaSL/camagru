<?php 
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;
?>

<div class="container">
<div class="signup-form">
    <forms>
		<h2>Create Account</h2>
        <div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-user"></i></span>
				<input type="text" class="form-control" id="input_username" placeholder="Username" required="required">
			</div>
        </div>
        <div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-paper-plane"></i></span>
				<input type="email" class="form-control" id="input_email" placeholder="Email" required="required">
			</div>
        </div>
		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock"></i></span>
				<input type="password" class="form-control" id="input_password" placeholder="Password" required="required">
			</div>
        </div>
		<div class="form-group">
            <button class="btn btn-primary btn-block btn-lg" id="button_create_account">Sign Up</button>
        </div>
    </form>
	<div class="text-center">Already have an account? <a href="index.php?p=login">Login here</a>.</div>
</div>
</div>


<input type="hidden" name="token" id="token" value="<?= $token; ?>" />

<script type="text/javascript" src="./app/assets/js/signup.js"></script>
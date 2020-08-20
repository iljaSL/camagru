<?php
$token = bin2hex(random_bytes(32));
$_SESSION['token'] = $token;
?>

<div class="containe-fluid" id="account">
	<div class="row text-center">
		<div  class="col-md-12 col-sm-12 mb-3">
			<form>
				<h3>Update your Username</h3>
				<p>Current username: <b id="display_current_username"><?= $_SESSION['username'] ?></b></p>
				<div class="form-group">
					<input type="text" class="input form-control" id="input_username" placeholder="New username" required="required">
				</div>
				<div class="form-group">
					<input type="password" class="input form-control" id="input_username_password" placeholder="Confirm with your Password" required="required">
				</div>
				<button class="btn btn-primary" id="btn_username">Submit</button>
			</form>
			<form>
				<h3>Update your Email</h3>
				<p>Current email: <b id="display_current_email"><?= $_SESSION['email'] ?></b></p>
				<div class="form-group">
					<input type="email" class="input form-control" id="input_email" placeholder="New email" required="required">
				</div>
				<div class="form-group">
					<input type="password" class="input form-control" id="input_email_password" placeholder="Confirm with your Password" required="required">
				</div>
				<button class="btn btn-primary" id="btn_email">Submit</button>
			</form>
			<form>
				<h3 class="text-danger">Update your Password</h3>
				<div class="form-group">
					<input type="password" class="input form-control" id="input_pswd" placeholder="New password" required="required">
				</div>
				<div class="form-group">
					<input type="password" class="input form-control" id="input_pswd_password" placeholder="Confirm with your current Password" required="required">
				</div>
				<button class="btn btn-primary" id="btn_pswd">Submit</button>
			</form>
			<form>
				<h3>Notification preference for new comments</h3>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" id="email_pref_yes" name="answer" <?= $_SESSION['email_when_comment'] === '1' ? 'checked' : "" ?>>
					<label class="form-check-label" for="inlineRadio1">Yes</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" id="email_pref_no" name="answer" <?= $_SESSION['email_when_comment'] === '0' ? 'checked' : "" ?>>
					<label class="form-check-label" for="inlineRadio1">No</label>
				</div>
				<br>
				<button class="btn btn-primary" id="btn_email_pref">Submit</button>
			</form>
		</div>
	</div>
</div>


<input type="hidden" name="token" id="token" value="<?= $token; ?>" />

<script type="text/javascript" src="./app/assets/js/account.js"></script>


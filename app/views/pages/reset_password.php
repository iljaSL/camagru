<?php if ($match): ?>
<section class="section">
  <div class="container">
    <h1 class="title">Reset your password</h1>
  </div>
</section>
<div class="columns">
    <div class="column"></div>
    <div class="column is-one-quarter">
        <div class="form">
            <div class="field">
				<p class="control has-icons-left">
					<input class="input" id="input_new_pswd" type="password" placeholder="New password">
					<span class="icon is-small is-left">
						<i class="fas fa-lock"></i>
					</span>
				</p>
			</div>
            <div class="field is-grouped is-grouped-centered">
				<p class="control">
					<button id="button_reset_password" class="btn btn-primary">
						Reset password
					</button>
				</p>
			</div>
        </div>
    </div>
    <div class="column"></div>
</div>

<?php endif; ?>

<script type="text/javascript">
<?php if (isset($_GET['email']) && isset($_GET['hash'])) { ?>
    let email = "<?= $_GET['email']; ?>";
    let hash = "<?= $_GET['hash']; ?>";
<?php } ?>
</script>
<script type="text/javascript" src="./app/assets/js/resetPasswordEmail.js"></script>

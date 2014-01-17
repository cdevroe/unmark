<div class="row-fluid">
  <div class="span4">
    <h2>Sign Up For Free</h2>
		<hr />
    <p><strong>Note:</strong> Sign up for free today and use all of Nilai's current features. The hosted version of Nilai will be a paid-for-service in the future. At that time, you'll be able to upgrade or export all of your bookmarks.</p>
  </div>

  <div class="well span4">
    <?php if (isset($flash_message['message'])) { ?>
    <div class="alert">
      <a class="close" data-dismiss="alert">×</a>
      <strong>Warning!</strong> <?php echo $flash_message['message'];?>
    </div>
    <?php } ?>

    <form method="post" action="/users/add" class="form-inline">
      <input type="hidden" name="csrf_token" id="csrf_token" value="<?php print $csrf_token; ?>">
      <p><input type="text" class="input-small" name="email" id="email" placeholder="Email Address"></p>
      <p><input type="password" class="input-small" name="password" id="password" placeholder="Password"></p>
      <p><input type="checkbox" name="terms" id="terms" value="accept"> I accept the <a href="/terms">terms of use</a>.</p>
      <p><input type="submit" value="Complete Sign Up" name="join" id="join" disabled class="btn-primary"></p>
    </form>

  </div>

</div>
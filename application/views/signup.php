<?php $this->load->view('header'); ?>

<div class="main-content">
  <div class="span4">
    <h2>Sign Up For Free</h2>
		<hr />
    <p><strong>Note:</strong> Sign up for free today and use all of Nilai's current features. The hosted version of Nilai will be a paid-for-service in the future. At that time, you'll be able to upgrade or export all of your bookmarks.</p>
  </div>

  <div class="well span4">
    <?php if (isset($_GET['message'])) { ?>
    <div class="alert">
      <a class="close" data-dismiss="alert">×</a>
      <strong>Warning!</strong> <?php echo $_GET['message'];?>
    </div>
    <?php } ?>

    <?php echo form_open('users/add','class="form-inline"');?>
      <p><?php echo form_input('emailaddress','','class="input" placeholder="Email Address"');?></p>
      <p><?php echo form_password('password','','class="input" placeholder="Password"');?></p>
      <p><?php echo form_checkbox('terms', 'accept');?> I accept the <a href="/terms">terms of use</a>.</p>
      <p><?php echo form_submit('join','Complete Sign Up','disabled class="btn-primary"');?></p>
    <?php echo form_close();?>
  </div>

</div>

<?php $this->load->view('footer'); ?>
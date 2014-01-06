<?php $this->load->view('header'); ?>

<div class="row-fluid">
  <div class="span4">
    <h2>Sign Up For Free</h2>
		<hr />
    <p><strong>Note:</strong> Sign up for free today and use all of Nilai's current features. The hosted version of Nilai will be a paid-for-service in the future. At that time, you'll be able to upgrade or export all of your bookmarks.</p>
  </div>
  
  <div class="well span4">
    <?php if (isset($_GET['message'])) { ?>
    <div class="alert">
      <a class="close" data-dismiss="alert">×</a>
      <strong>Warning!</strong> <?=$_GET['message'];?>
    </div>
    <?php } ?>
    
    <?=form_open('users/add','class="form-inline"');?>
      <p><?=form_input('emailaddress','','class="input" placeholder="Email Address"');?></p>
      <p><?=form_password('password','','class="input" placeholder="Password"');?></p>
      <p><?=form_checkbox('terms', 'accept');?> I accept the <a href="/terms">terms of use</a>.</p>
      <p><?=form_submit('join','Complete Sign Up','disabled class="btn-primary"');?></p>
    <?=form_close();?>
  </div>
  
</div>

<?php $this->load->view('footer'); ?>
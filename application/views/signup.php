<?php $this->load->view('header'); ?>

<div class="row-fluid">
  <div class="span4">
    <h2>Sign up and start doing something with all of those links</h2>
		<hr />
  </div>
  
  <div class="well span4">
    <?php if (isset($_GET['message'])) { ?>
    <div class="alert">
      <a class="close" data-dismiss="alert">×</a>
      <strong>Warning!</strong> <?=$_GET['message'];?>
    </div>
    <?php } ?>
    
    <?=form_open('users/add','class="form-inline"');?>
      <p><?=form_input('emailaddress','Email','class="input"');?></p>
      <p><?=form_password('password','xxxxxxx','class="input"');?></p>
      <p><?=form_submit('join','Create Account','class="btn-primary"');?></p>
    <?=form_close();?>
  </div>
  
</div>

<?php $this->load->view('footer'); ?>
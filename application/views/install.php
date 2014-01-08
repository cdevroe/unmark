<?php $this->load->view('header'); ?>

<div class="row-fluid">
  <div class="span6">

		<?php if ($install_complete) { ?>
			<h2>Good News!</h2>
			<p>You already have a database. You are running the latest version of the database schema. And you have one or more users. You're all set. Just log in!</p>
		<?php } else { ?>
			<h2>Create a user</h2>
			<p>Your database is set up, it is running the latest database schema, but you do not have a user account yet. Go ahead and set one up.</p>
			<?php echo form_open('users/add','class="form-inline"');?>
      			<p></p>
      			<p><?php echo form_input('emailaddress','Email Address','class="input"');?></p>
      			<p><?php echo form_password('password','xxxxxxx','class="input"');?></p>
      			<p><?php echo form_submit('join','Create Account','class="btn-primary"');?></p>
    		<?php echo form_close();?>
		<?php } ?>

  </div>

  <div class="well span3">
    <a href="http://github.com/cdevroe">Documentation</a>
  </div>

</div>

<?php $this->load->view('footer'); ?>
<?php $this->load->view('header'); ?>

<div class="row-fluid">
  <div class="span4">
    <h2>Sign up and start doing something with all of those links</h2>
		<hr />
  </div>
  
  <div class="well span4">
    <?php if ($signupsLeft > 0) { ?>
    <!--<p><small><strong>Note:</strong> I'm only accepting <strong><?=$signupsLeft;?> more accounts</strong> today.</small></p> -->
    <?php if (isset($_GET['message'])) { ?>
    <div class="alert">
      <a class="close" data-dismiss="alert">×</a>
      <strong>Warning!</strong> <?=$_GET['message'];?>
    </div>
    <?php } ?>
    
    <?=form_open('users/add','class="form-inline"');?>
      <p><label class="checkbox" for="payment"><input type="radio" checked name="payment" id="payment" value="quarterly" /> $3 for three months</label>  <label class="checkbox" for="payment"><input type="radio" name="payment" id="payment" value="yearly" /> $10 a year</label></p>
      <p><?=form_input('emailaddress','Email','class="input-small"');?>
      <?=form_password('password','xxxxxxx','class="input-small"');?></p>
      <p><?=form_input('promocode','Promo code','class="input-small"');?> <?=form_submit('join','Join','class="btn-primary"');?></p>
    <?=form_close();?>
    <?php } else { ?>
    <p><small>Sorry, I'm not accepting any more sign ups today. Please come back tomorrow. </small></p>
    <?php } ?>
    <p><img src="/assets/images/paypal.gif" alt="paypal" width="253" height="80" /></p>
  </div>
  
</div>

<?php $this->load->view('footer'); ?>
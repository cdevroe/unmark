<?php $this->load->view('header'); ?>

<div class="row-fluid">
  <div class="span7">
    <h2>Create a group</h2>
    
    <?php if ($this->session->flashdata('message')) { ?>
    <div class="alert alert-success">
      <a class="close" data-dismiss="alert">×</a>
      <?=$this->session->flashdata('message');?>
    </div>
    <?php } ?>
		
		<div class="row-fluid">
		  <p>Groups can be used to share links with groups of people; family, friends, coworkers. Create a group, invite some people, share some links.</p>
		  <form id="create_group" method="post" action="/groups/add">
		<div class="span3">
  		  <p><input type="text" size="60" class="input-large" id="name" name="name" value="Name your group" /></p>
  		  <p><textarea id="description" name="description" class="input-large" cols="100" rows="8">What is the purpose of your group? (optional)</textarea><br /><small><strong>Examples:</strong><br />Sharing links for our upcoming vacation.<br />Links my coworkers need.</small></p>
  		  <?=form_submit('btn_add_group','Create this group','class="btn-primary"');?>
    </div>
    <div class="span3">
      <p><input type="text" size="60" class="input-large" id="invite1" name="invite1" value="coworker@job.com" /></p>
      <p><input type="text" size="60" class="input-large" id="invite2" name="invite2" value="" /></p>
      <p><input type="text" size="60" class="input-large" id="invite3" name="invite3" value="" /></p>
      <p><input type="text" size="60" class="input-large" id="invite4" name="invite4" value="" /></p>
      <p><input type="text" size="60" class="input-large" id="invite5" name="invite5" value="" /></p>
      <p><small>Everyone invited to this group will receive an email asking them to join Nilai (if they don't have an account already) so that they can be part of the group.</small></p>
    </div>
    <input type="hidden" id="uid" name="uid" value="<?=$uid;?>" />
    </form>
		</div>
		
  </div>
  
  <div class="well span3">
    
    <a href="/home">Back to your marks</a>
  </div>
  
</div>

<?php $this->load->view('footer'); ?>
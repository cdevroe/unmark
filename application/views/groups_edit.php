<?php $this->load->view('header'); ?>

<div class="row-fluid">
  <div class="span7">
    <h2>Edit your group</h2>
    
    <?php if ($this->session->flashdata('message')) { ?>
    <div class="alert alert-success">
      <a class="close" data-dismiss="alert">×</a>
      <?=$this->session->flashdata('message');?>
    </div>
    <?php } ?>
		
		<div class="row-fluid">
		  <p>The name and description of your group can be edited.</p>
		  <form id="edit_group" method="post" action="/groups/update">
		<div class="span3">
  		  <p><input type="text" size="60" class="input-large" id="name" name="name" value="<?=$group['name'];?>" /></p>
  		  <p><textarea id="description" name="description" class="input-large" cols="100" rows="8"><?=$group['description'];?></textarea><br /></p>
  		  <?=form_submit('btn_add_group','Update','class="btn-primary"');?>
    </div>
    <div class="span3">
      <p><small>If you would like to invite more people to this group you can do that from the group member management page.</small></p>
    </div>
    <input type="hidden" id="uid" name="uid" value="<?=$group['groupuid'];?>" />
    </form>
		</div>
		
  </div>
  
  <div class="well span3">
    
    <a href="/groups/<?=strtoupper($group['groupuid']);?>">Back to your group</a>
  </div>
  
</div>

<?php $this->load->view('footer'); ?>
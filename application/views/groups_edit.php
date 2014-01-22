<?php $this->load->view('header'); ?>

<div class="main-content">
    <h2>Edit your group</h2>

    <?php if ($this->session->flashdata('message')) { ?>
    <div class="alert alert-success">
      <a class="close" data-dismiss="alert">×</a>
      <?php echo $this->session->flashdata('message');?>
    </div>
    <?php } ?>

		<div class="row-fluid">
		  <p>The name and description of your group can be edited.</p>
		  <form id="edit_group" method="post" action="/groups/update">
		<div class="span3">
  		  <p><input type="text" size="60" class="input-large" id="name" name="name" value="<?php echo $group['name'];?>" /></p>
  		  <p><textarea id="description" name="description" class="input-large" cols="100" rows="8"><?php echo $group['description'];?></textarea><br /></p>
  		  <?php echo form_submit('btn_update_group','Update','class="btn active"');?>
        <input type="hidden" id="uid" name="uid" value="<?php echo $group['groupuid'];?>" />
    </form>
    </div>
    <div class="span3">
      <p><small>If you would like to invite more people to this group you can do that from <a href="<?php echo site_url().'groups/'.$group['groupuid'].'/members/';?>">the group member management page</a>.</small></p>
      <form id="delete_group" method="post" action="/groups/delete">
      <?php echo form_submit('btn_delete_group','Delete','class="btn danger"');?>
      <input type="hidden" id="uid" name="uid" value="<?php echo $group['groupuid'];?>" />
      </form>
    </div>

		</div>

</div>

<?php $this->load->view('footer'); ?>
<?php $this->load->view('header'); ?>

<div class="row-fluid">
  <div class="span7">
    <h2>Groups : Members : <?=$group['name'];?></h2>
    
    <?php if ($this->session->flashdata('message')) { ?>
    <div class="alert alert-success">
      <a class="close" data-dismiss="alert">×</a>
      <?=$this->session->flashdata('message');?>
    </div>
    <?php } ?>
		
		<div class="row-fluid">
		<div class="span8">
		  <p>Add or remove members of this group.</p>
		  
		  <form id="edit_group" method="post" action="/groups/<?=$group['groupuid'];?>/invite_member">
    		  <p><input type="text" size="60" class="input-large" id="emailaddress" name="emailaddress" value="coworker@job.com" /> <?=form_submit('btn_invite_member','Invite','class="btn-primary"');?></p>
      <input type="hidden" id="groupuid" name="groupuid" value="<?=$group['groupuid'];?>" />
      <input type="hidden" id="groupid" name="groupid" value="<?=$group['groupid'];?>" />
      </form>
		  
		  <?php if (isset($group['invites'])) { ?>
		  <h3>Invites</h3>
		  <div class="members">
		    <?php foreach($group['invites'] as $member) { ?>
		    <div class="member">
		      <p><?php if ($member['emailaddress'] == $this->session->userdata('emailaddress')) { ?><?php echo $member['emailaddress']. ' - This is you!'; } else { ?><!-- <a href="#" class="btn" title="Remove the invite"><i class="icon-ban-circle"></i></a> --> <strong><?=$member['emailaddress'];?></strong> <small>was invited on <?=date("d F Y",strtotime($member['dateinvited']));?></small><?php } ?></p>
		    </div>
		    <?php } ?>
		  </div>
		  <?php } ?>
		  
		  <h3>Members</h3>
		  <div class="members">
		    <?php foreach($group['members'] as $member) { ?>
		    <div class="member">
		      <p><?php if ($member['emailaddress'] == $this->session->userdata('emailaddress')) { ?><?php echo $member['emailaddress']. ' - This is you!'; } else { ?> <a href="<?=site_url();?>groups/<?=$group['groupuid'];?>/remove/<?=$member['id'];?>" class="btn" title="Remove this member"><i class="icon-ban-circle"></i></a> <strong><?=$member['emailaddress'];?></strong> <small>member since <?=date("d F Y",strtotime($member['datejoined']));?></small><?php } ?></p>
		    </div>
		    <?php } ?>
		  </div>
		  
		
  </div>
  
  <div class="well span3">
    
    <a href="/groups/<?=strtoupper($group['groupuid']);?>">Back to your group</a>
  </div>
  
</div>

<?php $this->load->view('footer'); ?>
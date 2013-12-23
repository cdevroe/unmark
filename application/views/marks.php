<?php $this->load->view('header'); ?>
<div class="row-fluid">
  <div class="span8 marks">
    <?php if ($this->session->flashdata('message')) { ?>
    <div class="alert alert-success">
      <a class="close" data-dismiss="alert">×</a>
      <?=$this->session->flashdata('message');?>
    </div>
    
    <?php } ?>
    
    <?php if (isset($invites)) { ?>
    <div class="alert alert-info">
      <!-- <a class="close" data-dismiss="alert">×</a> -->
      <h3>You've been invited to join some groups!</h3>
      <?php foreach ($invites as $invite) { ?>
      <p><strong>Heads up! <?=$invite['invitedemail'];?></strong> invited you to join the <strong>"<?=$invite['name'];?>"</strong> group! - <a href="/groups/invite/<?=strtoupper($invite['uid']);?>/<?=$invite['inviteid'];?>" title="Accept the invite" class="btn btn-mini btn-primary">Accept</a>  <a href="#" title="Reject the invite" class="btn btn-mini btn-danger">Reject</a></p>
      <?php } ?>
    </div>
    
    <?php } ?>
    
    <?php 
    // Set up the heading for the page.
    $heading='';
    if (isset($when) && $when != '') { $heading = ' : '.ucfirst($when); }
    
    if (isset($label) && $label != '') { $heading = ' : '.ucfirst($label); }

    if (isset($search) && $search != '') { $heading = ' : Search results : '.$search; }
    
    if (isset($group['name'])) { $heading = ' : Group : '.$group['name']; } ?>
    
    <?php if ($this->session->userdata('status') == 'unpaid') { ?>
    <div class="alert alert-error">
      <p>It seems as though your account has not been paid for. You can continue to use your account until your first month is up. However, if you'd like to keep your account: <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RZERQBDXYPJBU">$1/month</a>  or  <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RPHWTXXUKL638">$10/year</a>.</p>
    </div>
    <?php } ?>
    
    
    <?php if (isset($group['name']) && ($group['owner'] == $this->session->userdata('userid'))) { ?>
    <div class="btn-group groupbuttons">
      <a class="btn" title="Manage this group's members" href="/groups/<?=strtoupper($group['groupuid']);?>/members"><i class="icon-user"></i> <?=$group['member_count'];?> Members</a>
      <a class="btn" title="Edit this group" href="/groups/<?=strtoupper($group['groupuid']);?>/edit"><i class="icon-info-sign"></i> Edit</a>
    </div>
    <?php } elseif (isset($group['name']) && ($group['owner'] != $this->session->userdata('userid'))) { ?>
    <div class="groupinfo"><i class="icon-user"></i> <?=$group['member_count'];?> Members</div>
    <?php } ?>
    <h2>Links<?=$heading;?></h2>
    <?php if (isset($group['description'])) { ?><p><?=$group['description'];?></p><?php } ?>
    
    <hr />
    
    
    <?php if (!$marks) { ?>
    <div class="alert alert-info">
      <p>No links were found in this list. If you need some link suggestions <a href="http://twitter.com/nilaico/" target="_blank">follow @nilaico on Twitter</a>!</p>
    </div>
    <?php } else { ?>
		<?php foreach ($marks as $mark) {
//		print_r($mark);
		        // Parse URL to determine domain
	         $parsedUrl = parse_url($mark['url']);
	         
		        if ($this->session->flashdata('restoredid') && $this->session->flashdata('restoredid') == $mark['usersmarkid']) { $restored = ' restored'; } else { $restored = ''; }	?>
		  <div id="mark-<?=$mark['usersmarkid'];?>" class="row-fluid mark<?=$restored;?> xfolkentry">
		    <div class="markbuttons"><div class="btn-group"><a href="/marks/<?php if ($mark['status'] == 'archive') { echo 'restore'; } else { echo 'archive'; }?>/<?=$mark['usersmarkid'];?>" data-mark="<?=$mark['usersmarkid']?>" title="<?php if ($mark['status'] == 'archive') { echo 'Restore'; } else { echo 'Archive'; }?> this mark" class="btn btn-small archivemark"><i class="icon-<?php if ($mark['status'] == 'archive') { echo 'refresh'; } else { echo 'ok'; }?>"></i></a><a href="/marks/edit/<?=$mark['usersmarkid'];?>" title="Edit this mark" class="btn btn-small editmark"><i class="icon-info-sign"></i></a></div>
		    <?php if ($mark['tags'] != 'watch' && $mark['tags'] != 'listen') { ?><p style="text-align: center;"><a class="btn btn-mini mobilefriendly" href="http://www.google.com/gwt/x?u=<?=$mark['url'];?>" target="_blank" title="Mobile friendly version">text-only</a></p><?php } ?>
		    </div>
		    <div class="markmeta span10">
		      <h3 id="mark-<?=$mark['usersmarkid'];?>"><?php if ($mark['oembed'] != '' && $mark['oembed'] != 'None' || ($mark['recipe'] != '' && $mark['recipe'] != 'None')) { ?><a class="preview-button" data-mark="<?=$mark['usersmarkid']?>" href="#preview-<?=$mark['usersmarkid'];?>" title="Preview <?=$mark['title'];?>"><i class="icon-zoom-in"></i></a><?php } ?><?php if (strpos($mark['url'],'.jpg') !== FALSE || strpos($mark['url'],'.jpeg') !== FALSE || strpos($mark['url'],'.png') !== FALSE) { ?><a class="preview-button" data-mark="<?=$mark['usersmarkid']?>" href="#preview-<?=$mark['usersmarkid'];?>" title="Preview <?=$mark['title'];?>"><i class="icon-picture"></i></a><?php } ?> <?php if ($mark['groups'] != '0') { ?><a href="/groups/<?=strtoupper($mark['uid']);?>" class="label label-inverse"><?=ucfirst($mark['name']);?></a> <?php } ?> <a href="<?=$mark['url'];?>" title="<?=$mark['title'];?>" target="_blank" class="taggedlink"><?php
		  if ($mark['title'] !='') { 
		    if (strlen($mark['title']) > 65) { 
		      echo substr($mark['title'],0,50).'...';
		    } else {
		      echo $mark['title'];
		    }
		  } else { echo 'Untitled'; } ?></a></h3>
        <p><small><?php if ($mark['addedby'] != '0' && $mark['addedby'] != $this->session->userdata('userid')) { echo 'By '.$mark['emailaddress'].' - '; } ?><span class="dateadded"><a href="/marks/edit/<?=$mark['usersmarkid'];?>" title="Edit this mark"><?=strtolower(timespan(strtotime($mark['dateadded'])));?> ago</a></span> - <?php if ($mark['tags'] != '') { ?><a href="/home/label/<?=strtolower(str_replace(' ', '', $mark['tags'])); ?>" rel="tag"><?=strtolower($mark['tags']);?></a> - <?php } ?><a href="<?=$mark['url'];?>" title="<?=$mark['title'];?>"><?php
        if (strlen($mark['url']) > 70) {
          echo substr(str_replace('http://','',$mark['url']),0,35).'…';
        } else {
          echo str_replace('http://','',$mark['url']);
        } ?></a></small>
        </p>
        </div>
		  </div>
		  <?php if ($mark['note'] != '') { ?>
        <div class="note" id="note-<?=$mark['usersmarkid'];?>">
            <p><?=$mark['note'];?></p>
            <p><small><?php if ($mark['addedby'] != '0' && $mark['addedby'] != $this->session->userdata('userid')) { echo 'Note written by '.$mark['emailaddress']; } ?></small></p>
        </div>
      <?php } ?>
		  <?php if ($mark['oembed'] != '' && $mark['oembed'] != 'None') { ?>
		    <div class="preview-panel" id="preview-<?=$mark['usersmarkid'];?>">
            <p><?=$mark['oembed'];?></p>
        </div>
        <?php } ?>
        
        <?php if ($mark['recipe'] != '' && $mark['recipe'] != 'None') { ?>
		    <div class="preview-panel" id="preview-<?=$mark['usersmarkid'];?>">
            <?=$mark['recipe'];?>
        </div>
        <?php } ?>
        
        <?php if (strpos($mark['url'],'.jpg') !== FALSE || strpos($mark['url'],'.jpeg') !== FALSE || strpos($mark['url'],'.png') !== FALSE) { ?>
        <div class="preview-panel" id="preview-<?=$mark['usersmarkid'];?>">
            <img src="<?=$mark['url'];?>" width="400" />
        </div>
        <?php } ?>
        
		<?php } ?>
		<?php }// end if marks ?>
  </div>

  <div class="well span2 sidebar">
    <p><a class="btn" href="javascript:(function(){f='<?=site_url();?>marks/add?url='+encodeURIComponent(window.location.href)+'&title='+encodeURIComponent(document.title)+'&v=6&';a=function(){if(!window.open(f+'noui=1&jump=doclose','nilaiv1','location=1,links=0,scrollbars=0,toolbar=0,width=710,height=660'))location.href=f+'jump=yes'};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0)}else{a()}})()" title="Install bookmarklet">Add to Nilai</a></p><p><small>Drag this bookmarklet to your browser's bookmark bar to get started.</small></p>
    <hr />
    
    <?=form_open('marks/search','class="form-inline"');?>
      <?php if (isset($search) && $search != '') { $searchtext = $search; ?>
      <p><?=form_input('s',$searchtext,'class="input-small"');?> <?=form_submit('search','Search','class="btn-small"');?>
      <br />
      <small><a href="/home" title="Clear search results">(x) clear results</a></small></p>
      <?php } else { $searchtext = 'Search';  ?>
      <p><?=form_input('s',$searchtext,'class="input-small"');?> <?=form_submit('search','Search','class="btn-small"');?></p>
      <?php } ?>
    <?=form_close();?>

    <ul class="nav nav-list">
      <li class="nav-header">
        Help
      </li>
      <li>
        <a href="/help/how"><i class="icon-file"></i> How to use Nilai</a>
      </li>
      <li>
        <a href="/help/bookmarklet"><i class="icon-file"></i> Add the bookmarklet</a>
      </li>
      <li>
        <a href="/help/faq"><i class="icon-file"></i> FAQ</a>
      </li>
    </ul>
    <hr />
    <p><small>View <strong><a href="/changelog" title="Changelog">the changelog</a></strong> to see what's new or follow <a href="http://twitter.com/nilaico">@nilaico</a> for updates.</small></p>
    
    <?php if ($this->session->userdata('emailaddress') == 'colin@cdevroe.com' && isset($usercount)) { ?>
      <hr />
      <p><small>There are <strong><?=$usercount;?> paying users</strong> who have saved <strong><?=$markcount;?> links</strong> and created <strong><?=$groupcount;?> groups</strong> with <strong><?=$groupmemberscount;?> members</strong>.</small></p>
    <?php } ?>
    
  </div>
  
</div>

<?php $this->load->view('footer'); ?>
<div class="row-fluid">
  <div class="span8 marks">
    <?php if (isset($flash_message['message']) && ! empty($flash_message['message'])): ?>
    <div class="alert alert-success">
      <a class="close" data-dismiss="alert">×</a>
      <?php echo $flash_message['message'];?>
    </div>

    <?php endif; ?>

    <?php if (isset($invites)) { ?>
    <div class="alert alert-info">
      <!-- <a class="close" data-dismiss="alert">×</a> -->
      <h3>You've been invited to join some groups!</h3>
      <?php foreach ($invites as $invite) { ?>
      <p><strong>Heads up! <?php echo $invite['invitedemail'];?></strong> invited you to join the <strong>"<?php echo $invite['name'];?>"</strong> group! - <a href="/groups/invite/<?php echo strtoupper($invite['uid']);?>/<?php echo $invite['inviteid'];?>" title="Accept the invite" class="btn btn-mini btn-primary">Accept</a>  <a href="/groups/invite/<?php echo strtoupper($invite['uid']);?>/<?php echo $invite['inviteid'];?>/reject" title="Reject the invite" class="btn btn-mini btn-danger">Reject</a></p>
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


    <?php if (isset($group['name']) && (isset($user['user_id']) && $group['owner'] == $user['user_id'])) { ?>
    <div class="btn-group groupbuttons">
      <a class="btn" title="Manage this group's members" href="/groups/<?php echo strtoupper($group['groupuid']);?>/members"><i class="icon-user"></i> <?php echo $group['member_count'];?> Members</a>
      <a class="btn" title="Edit this group" href="/groups/<?php echo strtoupper($group['groupuid']);?>/edit"><i class="icon-info-sign"></i> Edit</a>
    </div>
    <?php } elseif (isset($group['name']) && (isset($user['user_id']) && $group['owner'] != $user['user_id'])) { ?>
    <!-- <div class="groupinfo"><i class="icon-user"></i> <?php echo $group['member_count'];?> Members</div> -->
    <div class="btn-group groupbuttons">
      <a class="btn" title="Members in Group"><i class="icon-user"></i> <?php echo $group['member_count'];?> Members</a>
      <a class="btn" title="Leave this group" href="/groups/<?php echo strtoupper($group['groupuid']);?>/leave"><i class="icon-remove"></i> Leave</a>
    </div>
    <?php } ?>
    <h2>Links<?php echo $heading;?></h2>
    <?php if (isset($group['description'])) { ?><p><?php echo $group['description'];?></p><?php } ?>

    <hr />


    <?php if (!$marks) { ?>
    <div class="alert alert-info">
      <p>No links were found in this list. If you need some link suggestions <a href="http://twitter.com/nilaico/" target="_blank">follow @nilaico on Twitter</a>!</p>
    </div>
    <?php } else { ?>
		<?php foreach ($marks as $mark) {
//		print_r($mark);
		        // Parse URL to determine domain
	         $parsedUrl = parse_url($mark->url);

		        //if ($this->session->flashdata('restoredid') && $this->session->flashdata('restoredid') == $mark['usersmarkid']) { $restored = ' restored'; } else { $restored = ''; }	?>
		  <div id="mark-<?php echo $mark['usersmarkid'];?>" class="row-fluid mark<?php echo $restored;?> xfolkentry">
		    <div class="markbuttons"><div class="btn-group"><a href="/marks/<?php if ($mark['status'] == 'archive') { echo 'restore'; } else { echo 'archive'; }?>/<?php echo $mark['usersmarkid'];?>" data-mark="<?php echo $mark['usersmarkid']?>" title="<?php if ($mark['status'] == 'archive') { echo 'Restore'; } else { echo 'Archive'; }?> this mark" class="btn btn-small archivemark"><i class="icon-<?php if ($mark['status'] == 'archive') { echo 'refresh'; } else { echo 'ok'; }?>"></i></a><a href="/marks/edit/<?php echo $mark['usersmarkid'];?>" title="Edit this mark" class="btn btn-small editmark"><i class="icon-info-sign"></i></a></div>
		    <?php if ($mark['tags'] != 'watch' && $mark['tags'] != 'listen') { ?><p style="text-align: center;"><a class="btn btn-mini mobilefriendly" href="http://www.google.com/gwt/x?u=<?php echo $mark['url'];?>" target="_blank" title="Mobile friendly version">text-only</a></p><?php } ?>
		    </div>
		    <div class="markmeta span10">
		      <h3 id="mark-<?php echo $mark['usersmarkid'];?>"><?php if ($mark['oembed'] != '' && $mark['oembed'] != 'None' || ($mark['recipe'] != '' && $mark['recipe'] != 'None')) { ?><a class="preview-button" data-mark="<?php echo $mark['usersmarkid']?>" href="#preview-<?php echo $mark['usersmarkid'];?>" title="Preview <?php echo $mark['title'];?>"><i class="icon-zoom-in"></i></a><?php } ?><?php if (strpos($mark['url'],'.jpg') !== FALSE || strpos($mark['url'],'.jpeg') !== FALSE || strpos($mark['url'],'.png') !== FALSE) { ?><a class="preview-button" data-mark="<?php echo $mark['usersmarkid']?>" href="#preview-<?php echo $mark['usersmarkid'];?>" title="Preview <?php echo $mark['title'];?>"><i class="icon-picture"></i></a><?php } ?> <?php if ($mark['groups'] != 0 && $mark['groups'] != '0') { ?><a href="/groups/<?php echo strtoupper($mark['uid']);?>" class="label label-inverse"><?php echo ucfirst($mark['name']);?></a> <?php } ?> <a href="<?php echo $mark['url'];?>" title="<?php echo $mark['title'];?>" target="_blank" class="taggedlink"><?php
		  if ($mark['title'] !='') {
		    if (strlen($mark['title']) > 65) {
		      echo substr($mark['title'],0,50).'...';
		    } else {
		      echo $mark['title'];
		    }
		  } else { echo 'Untitled'; } ?></a></h3>
        <p><small><?php if ($mark['addedby'] != '0' && isset($user['user_id']) && $mark['addedby'] != $user['user_id']) { echo 'By '.$mark['email'].' - '; } ?><span class="dateadded"><a href="/marks/edit/<?php echo $mark['usersmarkid'];?>" title="Edit this mark"><?php echo strtolower(timespan(strtotime($mark['dateadded'])));?> ago</a></span> - <?php if ($mark['tags'] != '') { ?><a href="/home/label/<?php echo strtolower(str_replace(' ', '', $mark['tags'])); ?>" rel="tag"><?php echo strtolower($mark['tags']);?></a> - <?php } ?><a href="<?php echo $mark['url'];?>" title="<?php echo $mark['title'];?>"><?php
        if (strlen($mark['url']) > 70) {
          echo substr(str_replace('http://','',$mark['url']),0,35).'…';
        } else {
          echo str_replace('http://','',$mark['url']);
        } ?></a></small>
        </p>
        </div>
		  </div>
		  <?php if ($mark['note'] != '') { ?>
        <div class="note" id="note-<?php echo $mark['usersmarkid'];?>">
            <p><?php echo $mark['note'];?></p>
            <p><small><?php if ($mark['addedby'] != '0' && isset($user['user_id']) && $mark['addedby'] != $user['user_id']) { echo 'Note written by '.$mark['email']; } ?></small></p>
        </div>
      <?php } ?>
		  <?php if ($mark['oembed'] != '' && $mark['oembed'] != 'None') { ?>
		    <div class="preview-panel" id="preview-<?php echo $mark['usersmarkid'];?>">
            <p><?php echo $mark['oembed'];?></p>
        </div>
        <?php } ?>

        <?php if ($mark['recipe'] != '' && $mark['recipe'] != 'None') { ?>
		    <div class="preview-panel" id="preview-<?php echo $mark['usersmarkid'];?>">
            <?php echo $mark['recipe'];?>
        </div>
        <?php } ?>

        <?php if (strpos($mark['url'],'.jpg') !== FALSE || strpos($mark['url'],'.jpeg') !== FALSE || strpos($mark['url'],'.png') !== FALSE) { ?>
        <div class="preview-panel" id="preview-<?php echo $mark['usersmarkid'];?>">
            <img src="<?php echo $mark['url'];?>" width="400" />
        </div>
        <?php } ?>

		<?php } ?>
		<?php }// end if marks ?>
  </div>

  <div class="well span2 sidebar">

    <?php if ( isset($marks_saved_today) ) {
      if (!$marks_saved_today) $marks_saved_today = 0;

      if (!$marks_archived_today) $marks_archived_today = 0;  ?>
    <p><small>You've <strong>saved <?php echo $marks_saved_today;?></strong> and <strong>archived <?php echo $marks_archived_today;?></strong> marks today.
    <?php if ( $marks_saved_today >= $marks_archived_today ) {
      echo 'Get busy!';
    } elseif ( $marks_saved_today < $marks_archived_today ) {
      echo 'Keep up the good work!';
    } ?></small></p>
    <hr>
    <?php } else { ?>
    <p><a class="btn" href="javascript:(function(){f='<?php echo site_url();?>marks/add?url='+encodeURIComponent(window.location.href)+'&title='+encodeURIComponent(document.title)+'&v=6&';a=function(){if(!window.open(f+'noui=1&jump=doclose','nilaiv1','location=1,links=0,scrollbars=0,toolbar=0,width=710,height=660'))location.href=f+'jump=yes'};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0)}else{a()}})()" title="Install bookmarklet">Add to Nilai</a></p><p><small>Drag this bookmarklet to your browser's bookmark bar to get started.</small></p>
    <hr />
    <?php } ?>

    <?php echo form_open('marks/search','class="form-inline"');?>
      <?php if (isset($search) && $search != '') { $searchtext = $search; ?>
      <p><?php echo form_input('s',$searchtext,'class="input-small"');?> <?php echo form_submit('search','Search','class="btn-small"');?>
      <br />
      <small><a href="/home" title="Clear search results">(x) clear results</a></small></p>
      <?php } else {  ?>
      <p><?php echo form_input('s','','class="input-small" placeholder="Search"');?> <?php echo form_submit('search','Search','class="btn-small"');?></p>
      <?php } ?>
    <?php echo form_close();?>

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
    <p><small><strong><a href="/changelog" title="Changelog">Version <?php echo NILAI_VERSION;?></a></strong> See what's new by following <a href="http://twitter.com/nilaico">@nilaico</a> for updates.</small></p>

  </div>

</div>
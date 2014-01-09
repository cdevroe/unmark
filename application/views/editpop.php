<?php $this->load->view('header'); ?>

<div class="row-fluid">
  <div class="span8">
    <h2><?php if (isset($markadded)) { echo 'Your link was added!'; } else { echo 'Edit your link'; } ?></h2>
		<h3><a href="<?php echo $url;?>"><?php if ($title != '' || strlen($title) > 0) { echo $title; } else { echo 'Untitled'; }?></a></h3>
		<p><small>
		<?php if (strlen($url) > 90) {
          echo substr(str_replace('http://','',$url),0,50).'…';
        } else {
          echo str_replace('http://','',$url);
        } ?>
		</small></p>
		<?php if ($addedby == $this->session->userdata('userid')) { ?>
		<textarea id="note" cols="10" rows="3" placeholder="Write a small note. Perhaps a reminder of why you're saving this."><?php echo $note;?></textarea>
		<?php } ?>
		<p><a href="<?php if (isset($markadded)) { echo 'javascript:window.close();'; } else { echo '/home'; } ?>" class="btn btn-primary">Save and close</a> <a href="/marks/delete/<?php echo $urlid;?>" class="btn btn-danger">Delete</a></p>
		<hr />

		<div class="row-fluid">
		<div class="span5">
		<!--<h3><?php if (isset($labeladded) && isset($markadded)) { echo 'Smart Labeled. Change it?'; } elseif (isset($labeladded) && !isset($markadded)) { echo 'Edit your label'; } else { echo 'Label your mark'; } ?></h3> -->
		<h3>Actions</h3>
		<p><a href="#" class="btn addlabel<?php if ($tags == 'read') { echo ' btn-success'; } ?>" title="Read">Read</a> <a href="#" class="btn addlabel<?php if ($tags == 'watch') { echo ' btn-success'; } ?>" title="Watch">Watch</a> <a href="#" class="btn addlabel<?php if ($tags == 'listen') { echo ' btn-success'; } ?>" title="Listen">Listen</a></p>
		<p><a href="#" class="btn addlabel<?php if ($tags == 'buy') { echo ' btn-success'; }?>" title="Buy">Buy</a> <a href="#" class="btn addlabel<?php if ($tags == 'eatdrink') { echo ' btn-success'; }?>" title="Eatdrink">Eat & Drink</a> <a href="#" class="btn addlabel<?php if ($tags == 'do') { echo ' btn-success'; } ?>" title="Do">Do</a></p>

		<p><?php if (isset($userlabeladded)) { ?>
		<a href="#" id="clearlabelbutton" class="btn addlabel<?php if ($tags == '') { echo ' disabled'; } ?>" title=""><i class="icon-ban-circle"></i> Unlabeled</a> <a href="#" id="smartlabelbutton" class="btn btn-danger">Stop using Smart Label?</a>

		<?php } elseif (isset($labeladded) && !isset($userlabeladded)) { ?>
		<!-- No smart label button because it matched the default smart label list -->
		<a href="#" id="clearlabelbutton" class="btn addlabel" title=""><i class="icon-ban-circle"></i> Unlabeled</a>

		<?php } elseif (!isset($markadded) && $tags != '') { ?>
		<a href="#" id="clearlabelbutton" class="btn addlabel" title=""><i class="icon-ban-circle"></i> Unlabeled</a>
		<a href="#" id="smartlabelbutton" class="btn">Add Smart Label?</a>

		<?php } else { ?>
		<a href="#" id="clearlabelbutton" class="btn addlabel disabled" title=""><i class="icon-ban-circle"></i> Unlabeled</a>
		<a href="#" id="smartlabelbutton" class="btn disabled">Add Smart Label?</a>
		<?php } ?>
		</p>
		<p><span id="smartlabelmessage"></span></p>
		</div>
		<div span="span3">

		<h3>Groups</h3>
		<?php if (isset($groups['belong'])) { ?>
		<p><?php foreach($groups['belong'] as $gb) {  ?>
    <a href="#" class="btn addgroup<?php if ($groupid == $gb['id']) { echo ' btn-success'; } ?>" title="<?php echo $gb['name'];?>" data-group="<?php echo $gb['id'];?>"><?php echo $gb['name'];?></a>
    <?php } ?>
    </p> <?php } ?>
    <p><a href="/groups/create" title="Create a new group" class="btn">Create a group +</a></p>
      </div>
      </div>

		<input type="hidden" id="urlid" name="urlid" value="<?php echo $urlid;?>" />
		<input type="hidden" id="domain" name="domain" value="<?php echo $urldomain;?>" />
		<input type="hidden" id="label" name="label" value="<?php echo $tags;?>" />
		<input type="hidden" id="group" name="group" value="<?php echo $groupid;?>" />

  </div>

</div>

<?php $this->load->view('footer'); ?>
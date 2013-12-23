<?php $this->load->view('header'); ?>

<div class="row-fluid">
  <div class="span4">
		<h2>Your mark has been saved!</h2>
		<h3><a href="<?=$url;?>"><?php if ($title != '' || strlen($title) > 0) { echo $title; } else { echo 'Untitled'; } ?></a></h3>
		<p><small><?=$url?></small></p>
		<p><a href="javascript:window.close();" class="btn btn-success">Save and close</a></p>
		<hr />
		<h3>Label your mark</h3>
		<p><a href="#" class="btn addlabel" title="Read Later">Read Later</a> <a href="#" class="btn addlabel" title="Video">Video</a> <a href="#" class="btn addlabel" title="Recipe">Recipe</a></p>
		<p><a href="#" class="btn addlabel" title="Shopping">Shopping</a> <a href="#" class="btn addlabel" title="Inspiration">Inspiration</a></p>
		<p><a href="#" class="btn addlabel" title="Documentation">Documentation</a> <a href="#" class="btn addlabel" title="Research">Research</a></p>
		<p><a href="#" class="btn addlabel" title=""><i class="icon-ban-circle"></i> Clear labels</a></p>
		
		<input type="hidden" id="urlid" name="urlid" value="<?=$urlid;?>" />
  </div>

</div>

<?php $this->load->view('footer'); ?>
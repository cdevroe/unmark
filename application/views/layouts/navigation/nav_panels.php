<div id="panel-label" class="nav-panel">
	<h4 class="nav-heading"><?php echo unmark_phrase('Labels'); ?></h4>
	<ul class="label-list">
		<?php
		if (isset($labels)) :
		foreach ($labels as $label) : ?>
			<li class="label-<?php print $label->label_id ?>">
				<a href="/marks/label/<?php print $label->slug; ?>"><?php print unmark_phrase($label->name); ?></a>
				<span><?php echo printMarksCount($label->total_active_marks); ?></span>
			</li>
		<?php endforeach; endif; ?>
	</ul>
</div>
<div id="panel-tags" class="nav-panel">
	<h4 class="nav-heading"><?php echo unmark_phrase('Most-Used Tags') ?></h4>
	<ul class="tag-list">
		<?php $this->load->view('layouts/navigation/tags_list.php'); ?>
	</ul>
</div>
<div id="panel-timeline" class="nav-panel">
	<h4 class="nav-heading"><?php echo unmark_phrase('Timeline')?></h4>
	<?php $this->load->view('layouts/timeline'); ?>
</div>
<div id="panel-search" class="nav-panel">
	<h4 class="nav-heading"><?php echo unmark_phrase('Search')?></h4>
	<form method="get" action="/marks/search" id="search-form">
		<input type="text" name="q" id="search-input" placeholder="<?php echo unmark_phrase('SEARCH...') ?>" autocapitalize="off">
		<button type="submit"><i class="icon-go"></i></button>
	</form>
	<h4 class="nav-heading"><?php echo unmark_phrase('Most-Used Tags') ?></h4>
	<ul class="tag-list">
		<?php $this->load->view('layouts/navigation/tags_list.php'); ?>
	</ul>
</div>
<div id="panel-settings" class="nav-panel">
	<button id="logout-btn" class="danger" data-action="logout"><?php echo unmark_phrase('Sign Out') ?></button>

	<?php $this->load->view('layouts/accountlinks'); ?>

	<h4 class="nav-heading"><?php echo unmark_phrase('Help')?></h4>
	<ul class="nav-list">
		<li><a target="_blank" href="http://help.unmark.it/" title="Unmark Help"><?php echo unmark_phrase('Unmark Help') ?></a></li>
		<li><a target="_blank" href="http://help.unmark.it/bookmarklet" title="Get the bookmarklet"><?php echo unmark_phrase('Bookmarklet Help') ?></a></li>
		<li><a target="_blank" href="http://twitter.com/unmarkit" title="Follow us on Twitter">Follow us on Twitter</a></li>
		<li><a target="_blank" href="mailto:colin+unmark@cdevroe.com" title="Contact support">Contact Support</a></li>
	</ul>
	<h4 class="nav-heading"><?php echo unmark_phrase('Tools')?></h4>
	<ul class="nav-list">
		<li><a href="javascript:(function()%7Bf%3D%27<?php print rtrim(base_url(),'/'); ?>%2Fmark/add%3Furl%3D%27%2BencodeURIComponent(window.location.href)%2B%27%26title%3D%27%2BencodeURIComponent(document.title)%2B%27%26v%3D6%26%27%3Ba%3Dfunction()%7Bif(!window.open(f%2B%27noui%3D1%26jump%3Ddoclose%27,%27nilaiv2%27,%27location%3D1,links%3D0,scrollbars%3D0,toolbar%3D0,width%3D594,height%3D485%27))location.href%3Df%2B%27jump%3Dyes%27%7D%3Bif(/Firefox/.test(navigator.userAgent))%7BsetTimeout(a,0)%7Delse%7Ba()%7D%7D)();">Save to Unmark</a></li>
		<li><a target="_blank" href="https://chrome.google.com/webstore/detail/unmark/cdhnljlbeehjgddokagghpfgahhlifch"><?php echo unmark_phrase('Get the Chrome Extension') ?></a></li>
	</ul>
</div>
<div id="panel-archive" class="nav-panel">
	<h4 class="nav-heading"><?php echo unmark_phrase('Archive')?></h4>
	<small>Archive stuff here...</small>
</div>

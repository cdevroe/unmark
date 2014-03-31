<div id="panel-label" class="nav-panel">
	<ul class="label-list">
		<?php
		if (isset($labels)) :
		foreach ($labels as $label) : ?>
			<li class="label-<?php print $label->label_id ?>">
				<a href="/marks/label/<?php print $label->slug; ?>"><?php print _($label->name); ?></a>
				<span><?php echo printMarksCount($label->total_active_marks); ?></span>
			</li>
		<?php endforeach; endif; ?>
	</ul>
</div>
<div id="panel-timeline" class="nav-panel">
	<?php $this->load->view('layouts/timeline'); ?>
</div>
<div id="panel-search" class="nav-panel">
	<form method="get" action="/marks/search" id="search-form">
		<input type="text" name="q" id="search-input" placeholder="<?php echo _('SEARCH...') ?>" autocapitalize="off">
		<button type="submit"><i class="icon-go"></i></button>
	</form>

	<h4 class="nav-heading"><?php echo _('Most-Used Tags') ?></h4>
	<ul class="tag-list">
		<?php $this->load->view('layouts/navigation/tags_list.php'); ?>
	</ul>
</div>
<div id="panel-settings" class="nav-panel">
	<button id="logout-btn" class="danger" data-action="logout"><?php echo _('Sign Out') ?></button>

	<?php $this->load->view('layouts/accountlinks'); ?>

	<h4 class="nav-heading"><?php echo _('Help')?></h4>
	<ul class="nav-list">
		<li><a target="_blank" href="http://help.unmark.it/"><?php echo _('How to Use Unmark') ?></a></li>
		<li><a target="_blank" href="http://help.unmark.it/faq"><?php echo _('View Our FAQ') ?></a></li>
		<li><a target="_blank" href="http://help.unmark.it/bookmarklet"><?php echo _('Get the Bookmarklet') ?></a></li>
		<li><a target="_blank" href="https://chrome.google.com/webstore/detail/unmark/cdhnljlbeehjgddokagghpfgahhlifch"><?php echo _('Get the Chrome Extension') ?></a></li>
	</ul>
</div>

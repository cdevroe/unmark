<div id="panel-label" class="nav-panel">
	<ul class="label-list">
		<?php
		if (isset($labels)) :
		foreach ($labels as $label) : ?>
			<li class="label-<?php print $label->label_id ?>">
				<a href="/marks/label/<?php print $label->slug; ?>"><?php print $label->name; ?></a>
				<span><?php print determinePlurality($label->total_active_marks, 'link'); ?></span>
			</li>
		<?php endforeach; endif; ?>
	</ul>
</div>
<div id="panel-timeline" class="nav-panel">
	<?php $this->load->view('layouts/timeline'); ?>
</div>
<div id="panel-search" class="nav-panel">
	<form method="get" action="/marks/search" id="search-form">
		<input type="text" name="q" id="search-input" placeholder="SEARCH...">
		<i class="icon-go"></i>
	</form>

	<h4 class="nav-heading">Most-Used Tags</h4>
	<ul class="tag-list">
		<?php if (isset($tags) && $tags['popular'] != '') : ?>
			<?php foreach ($tags['popular'] as $pop_tag) : ?>
				<li><a href="/marks/tag/<?php print $pop_tag->slug; ?>">#<?php print $pop_tag->name; ?></a></li>
			<?php endforeach; ?>
		<?php else : ?>
		<li>No Tags Found</li>
		<?php endif; ?>
	</ul>
</div>
<div id="panel-settings" class="nav-panel">
	<button id="logout-btn" class="danger" data-action="logout">Sign Out</button>

	<?php $this->load->view('layouts/accountlinks'); ?>

	<h4 class="nav-heading">Help</h4>
	<ul class="nav-list">
		<li><a target="_blank" href="http://unmark-help.getbarley.com/">How to Use Unmark</a></li>
		<li><a target="_blank" href="http://unmark-help.getbarley.com/faq">FAQ</a></li>
		<li><a target="_blank" href="http://unmark-help.getbarley.com/bookmarklet">Get the Bookmarklet</a></li>
	</ul>
</div>

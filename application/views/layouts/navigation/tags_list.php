<?php if (isset($tags) && $tags['popular'] != '') : ?>
	<?php foreach ($tags['popular'] as $pop_tag) : ?>
		<li><a href="/marks/tag/<?php print $pop_tag->slug; ?>">#<?php print $pop_tag->name; ?></a></li>
	<?php endforeach; ?>
<?php else : ?>
<li><?php echo _('No Tags Found'); ?></li>
<?php endif; ?>

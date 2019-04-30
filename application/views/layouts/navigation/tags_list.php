<?php if (isset($tags) && $tags['popular'] != '') : ?>
	<?php foreach ($tags['popular'] as $pop_tag) : ?>
		<li class="tag-<?php print $pop_tag->tag_id ?>">
			<a href="/marks/tag/<?php print $pop_tag->slug; ?>">#<?php print $pop_tag->name; ?></a>
			<span><?php echo printMarksCount($pop_tag->total); ?></span>	
		</li>
	<?php endforeach; ?>
<?php else : ?>
<li><?php echo unmark_phrase('No Tags Found'); ?></li>
<?php endif; ?>

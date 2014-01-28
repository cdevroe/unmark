<div class="marks">
    <div class="marks_list">
        <?php foreach ($marks as $mark) : ?>
            <div id="mark-<?php print $mark->mark_id; ?>" class="mark label-<?php print $mark->label_id; ?>">
                <h2><?php print $mark->title; ?></h2>
                <div class="mark-meta">
                    <span class="mark-date"><?php print $mark->created_on; ?></span>
                    <span class="mark-sep">&bull;</span>
                    <span class="mark-link"><a href="#"><?php print $mark->url; ?></a></span>
                </div>
                <div class="mark-actions">
                    <a class="action" href="#" data-action="show_mark_info" data-mark="mark-data-<?php print $mark->mark_id; ?>" class="mark-more">
                        <i class="barley-icon-elipsis"></i>
                    </a>
                    <a href="/mark/archive/<?php print $mark->mark_id; ?>" class="mark-archive">
                        <i class="barley-icon-ok"></i>
                    </a>
                </div>
                <script id="mark-data-<?php print $mark->mark_id; ?>" type="application/json">{ "label_id": "<?php print addslashes($mark->label_id); ?>", "label_name": "<?php print addslashes($mark->label_name); ?>", "mark_id": "<?php print addslashes($mark->mark_id); ?>", "notes": "<?php print addslashes($mark->notes); ?>", "preview": "<?php print addslashes($mark->embed); ?>" }</script>
            </div>
        <?php endforeach; ?>
    </div>
</div>
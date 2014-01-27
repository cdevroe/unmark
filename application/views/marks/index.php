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
                    <a href="#show-more" data-mark-id="<?php print $mark->mark_id; ?>" class="mark-more">
                        <i class="barley-icon-elipsis"></i>
                    </a>
                    <a href="/mark/archive/<?php print $mark->mark_id; ?>" class="mark-archive">
                        <i class="barley-icon-ok"></i>
                    </a>
                </div>
                <div class="mark-corner"></div>
                <div class="mark-info">
                    <div class="mark-data-notes"><?php print $mark->notes; ?></div>
                    <div class="mark-data-preview"><?php print $mark->embed; ?></div>
                    <div class="mark-data-label"><?php print $mark->label_id; ?></div>
                    <div class="mark-data-label-name"><?php print $mark->label_name; ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
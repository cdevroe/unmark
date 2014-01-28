<div class="marks">
    <div class="marks_list">
        <?php foreach ($marks as $mark) :

                $notes = ($mark->notes == "" ? 0 : $mark->notes);
                $preview = ($mark->embed == "" ? 0 : $mark->embed);

                $marks_data = array(
                    "mark_id"       => $mark->mark_id,
                    "label_id"      => $mark->label_id,
                    "label_name"    => $mark->label_name,
                    "notes"         => $notes,
                    "preview"       => $preview
                );
            ?>
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
                <script id="mark-data-<?php print $mark->mark_id; ?>" type="application/json"><?php echo json_encode($marks_data); ?></script>
            </div>
        <?php endforeach; ?>
    </div>
</div>
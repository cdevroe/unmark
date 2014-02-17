<?php if (isset($errors['2'])) : ?>
<h2 class="marks-heading">Sorry, No links found</h2>
<?php else : ?>
<?php if ($lookup_type != "all") :

    $heading = array();

    $link_plural = ($total > 1) ? " links " : " link ";

    if (isset($_GET['q'])) { $search_term = $_GET['q']; }

    switch ($lookup_type) {
        case 'label':
            $heading['icon']    =   'icon-circle';
            $heading['title']   =   $total . $link_plural . "labeled " . $active_label['label_name'];
            break;
        case 'archive':
            $heading['icon']    =   'icon-heading_archive';
            $heading['title']   =   $total . $link_plural . "archived";
            break;
        case 'tag':
            $heading['icon']    =   'icon-heading_tag';
            $heading['title']   =   $total . $link_plural . "tagged " . $active_tag['tag_name'];
            break;
        case 'search':
            $heading['icon']    =   'icon-heading_search';
            $heading['title']   =   $total . $link_plural . "found containing \"" . $search_term . "\"";
            break;
        default:
            $heading['icon']    =   'icon-heading_time';
            $heading['title']   =   $total . $link_plural;
            $default_title      = true;
    }

    // If a lookup time frame
    // Work some magic
    $in_the           = (stristr($lookup_type, 'last-')) ? 'in the ' : '';
    $heading['title'] = (isset($default_title) && $lookup_type != 'custom_date') ? $total . $link_plural . 'created ' . $in_the . str_replace('-', ' ', $lookup_type) : $heading['title'];

?>
<h2 class="marks-heading"><i class="<?php print $heading['icon']; ?>"></i> <?php print $heading['title']; ?></h2>
<?php endif; ?>

<?php if (isset($active_label)) : ?>
<div id="lookup-<?php print $lookup_type; ?>" class="marks" data-label-class="label-<?php print $active_label['label_id']; ?>">
<?php else : ?>
<div id="lookup-<?php print $lookup_type; ?>" class="marks">
<?php endif; ?>
    <div class="marks_list">
        <?php foreach ($marks as $mark) :

                $notes = ($mark->notes == "" ? "Add a note or #hashtags ..." : $mark->notes);
                $preview = ($mark->embed == "" ? 0 : $mark->embed);
                $archived = ($mark->archived_on == "" ? 0 : 1);

                $marks_data = array(
                    "mark_id"       => $mark->mark_id,
                    "label_id"      => $mark->label_id,
                    "label_name"    => $mark->label_name,
                    "notes"         => $notes,
                    "preview"       => $preview,
                    "archived"      => $archived
                );

                $nice_url = rtrim(preg_replace('/https?:\/\/(www.)?/', '', $mark->url), '/');

            ?>
            <div id="mark-<?php print $mark->mark_id; ?>" class="mark label-<?php print $mark->label_id; ?>">
                <h2><a target="_blank" href="<?php print $mark->url; ?>"><?php print $mark->title; ?></a></h2>
                <div class="mark-meta">
                    <span class="mark-date"><?php print $mark->nice_time; ?></span>
                    <span class="mark-sep">&bull;</span>
                    <span class="mark-link"><a target="_blank" href="<?php print $mark->url; ?>"><?php print $nice_url; ?></a></span>
                </div>
                <div class="mark-actions">
                    <a class="action mark-info" href="#" data-action="show_mark_info" data-mark="mark-data-<?php print $mark->mark_id; ?>" class="mark-more">
                        <i class="icon-goto_link"></i>
                    </a>
                    <?php if ($lookup_type == "archive") : ?>
                        <a class="action mark-archive" data-action="mark_restore" href="#" data-id="<?php print $mark->mark_id; ?>">
                            <i class="icon-spinner"></i>
                        </a>
                    <?php else : ?>
                        <a class="action mark-archive" data-action="mark_archive" href="#" data-id="<?php print $mark->mark_id; ?>">
                            <i class="icon-check"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <script id="mark-data-<?php print $mark->mark_id; ?>" type="application/json"><?php echo json_encode($marks_data); ?></script>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<script type="text/javascript">
window.nilai_total_pages = <?php print $pages . ";\n"; ?>
</script>
<?php endif; ?>
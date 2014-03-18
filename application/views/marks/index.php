<?php if (isset($errors['2'])) : ?>
<h2 class="marks-heading">Sorry, No marks found</h2>
<?php else : ?>
<?php if (isset($lookup_type) && $lookup_type != "all") :

    // Variable Setup
    $heading = array();
    $link_plural = " ".determinePlurality($total, "mark", false)." ";
    $search_term = (isset($_GET['q'])) ? $_GET['q'] : '';
    $label_name = (isset($active_label['label_name'])) ? $active_label['label_name'] : '';
    $tag_name = (isset($active_tag['tag_name'])) ? $active_tag['tag_name'] : '';

    // Page Details
    switch ($lookup_type) {
        case 'label':
            $heading['icon']    =   'icon-circle';
            $heading['title']   =   $link_plural . "labeled " . $label_name;
            break;
        case 'archive':
            $heading['icon']    =   'icon-heading_archive';
            $heading['title']   =   $link_plural . "archived";
            break;
        case 'tag':
            $heading['icon']    =   'icon-heading_tag';
            $heading['title']   =   $link_plural . "tagged " . $tag_name;
            break;
        case 'search':
            $heading['icon']    =   'icon-heading_search';
            $heading['title']   =   $link_plural . "found containing \"" . $search_term . "\"";
            break;
        default:
            $heading['icon']    =   'icon-heading_time';
            $heading['title']   =   $link_plural;
            $default_title      =   true;
    }

    // If a lookup time frame
    // Work some magic
    $in_the           = (stristr($lookup_type, 'last-')) ? 'in the ' : '';
    $heading['title'] = (isset($default_title) && $lookup_type != 'custom_date') ? $link_plural . 'created ' . $in_the . str_replace('-', ' ', $lookup_type) : $heading['title'];

?>
<h2 class="marks-heading"><i class="<?php print $heading['icon']; ?>"></i> <?php print $heading['title']; ?></h2>
<?php endif; ?>

<?php if (isset($active_label)) : ?>
<div id="lookup-<?php print $lookup_type; ?>" class="marks" data-label-class="label-<?php print $active_label['label_id']; ?>">
<?php else : ?>
<div id="lookup-<?php print $lookup_type; ?>" class="marks">
<?php endif; ?>
    <div class="marks_list">
        <?php if (isset($marks)) : ?>
            <?php foreach ($marks as $mark) : ?>
                <div id="mark-<?php print $mark->mark_id; ?>" class="mark label-<?php print $mark->label_id; ?>">
                    <h2><a target="_blank" href="<?php print $mark->url; ?>"><?php print $mark->title; ?></a></h2>
                    <div class="mark-meta">
                        <span class="mark-date"><?php print $mark->nice_time; ?></span>
                        <span class="mark-sep">&bull;</span>
                        <span class="mark-link"><a target="_blank" href="<?php print $mark->url; ?>"><?php print niceUrl($mark->url); ?></a></span>
                    </div>
                    <div class="mark-actions">
                        <a title="View Mark Info" class="action mark-archive tabletonly" href="#" data-nofade="true" data-action="show_mark_info" data-mark="mark-data-<?php print $mark->mark_id; ?>">
                            <i class="icon-ellipsis"></i>
                        </a>
                        <a target="_blank" title="Open Mark" class="mark-info" href="<?php print $mark->url; ?>" data-mark="mark-data-<?php print $mark->mark_id; ?>">
                            <i class="icon-goto_link"></i>
                        </a>
                        <?php if ($lookup_type == "archive") : ?>
                            <a title="Unarchive Mark" class="action mark-archive" data-action="mark_restore" href="#" data-id="<?php print $mark->mark_id; ?>">
                                <i class="icon-unarchive"></i>
                            </a>
                        <?php else : ?>
                            <a title="Archive Mark" class="action mark-archive" data-action="mark_archive" href="#" data-id="<?php print $mark->mark_id; ?>">
                                <i class="icon-check"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="note-placeholder"></div>
                    <script id="mark-data-<?php print $mark->mark_id; ?>" type="application/json"><?php echo json_encode($mark); ?></script>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div id="mark-x" class="mark label-x"><h2>No Marks Found</h2></div>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($pages)) : ?>
<script type="text/javascript">
window.unmark_total_pages = <?php print $pages . ";\n"; ?>
</script>
<?php endif; ?>

<?php endif; ?>

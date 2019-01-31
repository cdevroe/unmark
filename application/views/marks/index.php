

            <?php if (isset($errors['2'])) : ?>
                <header class="marks-heading">
                    <div class="marks-heading-wrapper">
                        <div class="marks-heading-bar">
                            <h2><?php echo unmark_phrase('Sorry, No marks found')?></h2>
                            <?php $this->load->view('layouts/topbar/searchform.php'); ?>
                        </div>
                    </div>
                </header>
            <?php if (isset($_GET['q']) & $lookup_type == "search") : // Only if this is for a search ?>
                <header class="marks-heading">
                    <div class="marks-heading-wrapper">
                        <div class="marks-heading-bar">
                            <?php $this->load->view('layouts/topbar/searchform.php'); ?>
                        </div>
                    </div>
                </header>
                <div class="marks continue-search no-results"><?php echo sprintf( unmark_phrase('Would you like to <a href="/marks/archive/search?q=%s">try searching your archive</a>?'), (isset($_GET['q'])) ? $_GET['q'] : ''); ?></div>
            <?php elseif ($lookup_type == 'tag') : ?>
                <header class="marks-heading">
                    <div class="marks-heading-wrapper">
                        <div class="marks-heading-bar">
                            <?php $this->load->view('layouts/topbar/searchform.php'); ?>
                        </div>
                    </div>
                </header>
            <div class="marks continue-search no-results"><?php echo sprintf( unmark_phrase('Would you like to <a href="/marks/archive/search?q=%s">try searching your archive</a>?'), $active_tag['tag_name']); ?></div>
            <?php endif; ?>
            <?php else : ?>
            <?php if (isset($lookup_type) && $lookup_type != "all") :

                // Variable Setup
                $heading = array();
                $search_term = (isset($_GET['q'])) ? $_GET['q'] : '';
                $label_name = (isset($active_label['label_name'])) ? $active_label['label_name'] : '';
                $tag_name = (isset($active_tag['tag_name'])) ? $active_tag['tag_name'] : '';

                // Page Details
                switch ($lookup_type) {
                    case 'label':
                        $heading['icon']    =   'icon-circle';
                        //print_r($total);exit;
                        $heading['title']   =   sprintf(unmark_phrase('mark labeled %s', 'marks labeled %s', $total), unmark_phrase($label_name));
                        break;
                    case 'archive':
                        if ( $search_term != '' ) { // Someone is searching their archives
                            $heading['icon']    =   'icon-heading_search';
                            $heading['title']   =   sprintf(unmark_phrase('archived mark found containing "%s"', 'archived marks found containing "%s"', $total), $search_term);
                        } else {
                            $heading['icon']    =   'icon-heading_archive';
                            $heading['title']   =   unmark_phrase('mark archived', 'marks archived', $total);
                        }
                        break;
                    case 'tag':
                        $heading['icon']    =   'icon-heading_tag';
                        $heading['title']   =   sprintf(unmark_phrase('mark tagged %s', 'marks tagged %s', $total), $tag_name);
                        break;
                    case 'search':
                        $heading['icon']    =   'icon-heading_search';
                        $heading['title']   =   sprintf(unmark_phrase('mark found containing "%s"', 'marks found containing "%s"', $total), $search_term);
                        break;
                    default:
                        $heading['icon']    =   'icon-heading_time';
                        $heading['title']   =   unmark_phrase('mark', 'marks', $total);
                        $default_title      =   true;
                }

                // If a lookup time frame
                // Work some magic

                if(stristr($lookup_type, 'last-')){
                    $heading['title'] = (isset($default_title) && $lookup_type != 'custom_date') ? sprintf(unmark_phrase('mark created in the %s', 'marks created in the %s', $total), unmark_phrase(str_replace('-', ' ', $lookup_type))) : $heading['title'];
                } else {
                    $heading['title'] = (isset($default_title) && $lookup_type != 'custom_date') ? sprintf(unmark_phrase('mark created %s', 'marks created %s', $total), unmark_phrase(str_replace('-', ' ', $lookup_type))) : $heading['title'];
                }

            ?>
            <header class="marks-heading">
                <div class="marks-heading-wrapper">
                    <div class="marks-heading-bar">
                        <h2><i class="<?php print $heading['icon']; ?>"></i> <?php print $heading['title']; ?></h2>
                        <?php $this->load->view('layouts/topbar/searchform.php'); ?>
                    </div>
                </div>
            </header>
            <?php else : ?>
            <header class="marks-heading">
                <div class="marks-heading-wrapper">
                    <div class="marks-heading-bar">
                        <h2 class="default-message">All Marks</h2>
                        <?php $this->load->view('layouts/topbar/searchform.php'); ?>
                    </div>
                </div>
            </header>
            <?php endif; ?>



<?php if (isset($active_label)) : ?>
<div id="lookup-<?php print $lookup_type; ?>" class="marks" data-label-class="label-<?php print $active_label['label_id']; ?>">
<?php else : ?>
<div id="lookup-<?php print $lookup_type; ?>" class="marks">
<?php endif; ?>
    <div class="marks_list">
        <?php if (isset($marks)) : ?>
            <?php foreach ($marks as $mark) :
            if (isset($mark->mark_title)) $mark->title = $mark->mark_title; ?>
                <div id="mark-<?php print $mark->mark_id; ?>" class="mark label-<?php print $mark->label_id; ?>">
                    <h2 class="hideoutline"><a target="_blank" rel="noopener noreferrer" href="<?php print $mark->url; ?>"><?php print $mark->title; ?></a></h2>
                    <div class="mark-meta">
                        <span class="mark-date"><?php print $mark->nice_time; ?></span>
                        <span class="mark-sep">&bull;</span>
                        <span class="mark-link"><a target="_blank" rel="noopener noreferrer" href="<?php print $mark->url; ?>"><?php print niceUrl($mark->url); ?></a></span>
                    </div>
                    <div class="archive-target">
                        <?php if ($lookup_type == "archive") : ?>
                            <a title="Unarchive Mark" class="action mark-archive" data-action="mark_restore" href="#" data-id="<?php print $mark->mark_id; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><rect x="6" y="28" width="2" height="20"/><rect x="38" y="28" width="2" height="20"/><rect x="29.74" y="-2.79" width="2.06" height="24" transform="translate(2.5 24.45) rotate(-45)"/><rect x="14.18" y="-2.68" width="2.36" height="24" transform="translate(19.63 26.77) rotate(-135)"/><line x1="23.01" x2="39.98" y2="16.97"/><rect x="22" y="3" width="2" height="33"/><rect x="6" y="46" width="34" height="2"/></svg>
                            </a>
                        <?php else : ?>
                            <a title="Archive Mark" class="action mark-archive" data-action="mark_archive" href="#" data-id="<?php print $mark->mark_id; ?>">
                                <i>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><rect x="25.8" y="6.95" width="4" height="30.5" transform="translate(23.84 -13.16) rotate(45)"/><rect x="12.16" y="22.45" width="4" height="13.43" transform="translate(-16.47 18.56) rotate(-45)"/></svg>
                                </i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="mark-actions">
                        <a title="View Mark Info" class="action mark-info" href="#" data-nofade="true" data-action="show_mark_info" data-mark="mark-data-<?php print $mark->mark_id; ?>">
                            <i>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 8"><circle cx="18" cy="4" r="4"/><circle cx="32" cy="4" r="4"/><circle cx="4" cy="4" r="4"/></svg>
                            </i>
                        </a>
                    </div>
                    <div class="note-placeholder"></div>
                    <script id="mark-data-<?php print $mark->mark_id; ?>" type="application/json"><?php echo json_encode($mark); ?></script>
                </div>
            <?php endforeach; ?>
            <?php if (isset($_GET['q']) && $lookup_type == "search" || $lookup_type == "tag" ) : ?>
                <div class="marks continue-search with-results"><?php echo sprintf( unmark_phrase('Would you like to <a href="/marks/archive/search?q=%s">try searching your archive</a>?'), (isset($_GET['q'])) ? $_GET['q'] : $tag_name); ?></div>
            <?php endif; ?>
        <?php else : ?>
            <div id="mark-x" class="mark label-x"><h2><?php echo unmark_phrase('No Marks Found')?></h2></div>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($pages)) : ?>
<script type="text/javascript">
window.unmark_total_pages = <?php print $pages . ";\n"; ?>
</script>
<?php endif; ?>

<?php endif; ?>

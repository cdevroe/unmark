<?php /*
<div class="sidebar-default">
    <div class="sidebar-block" id="sidebar-graph">
        <div class="sidebar-inner">
            <?php if (isset($stats)) : ?>
            <p>
                <?php
                    echo sprintf(unmark_phrase('You saved %s mark today','You saved %s marks today',$stats['saved']['today']), '<span class="ns-today">' . $stats['saved']['today'] . '</span>');
                    echo ' ';
                    echo sprintf(unmark_phrase('and archived %s'), '<span class="na-today">' . $stats['archived']['today'] . '</span>');
                ?>.
            </p>
            <?php endif; ?>
        </div>
    </div>
    <?php if (isset($stats) && $stats['marks']['ages ago'] > 0) : ?>
    <div class="sidebar-block">
        <div class="sidebar-inner">
            <p><?php echo sprintf(unmark_phrase('You have %s mark that are over 1 year old. Want to tidy up a bit?', 'You have %s mark that are over 1 year old. Want to tidy up a bit?',$stats['marks']['ages ago']), '<span class="ns-year">' . $stats['marks']['ages ago'] . '</span>')?></p>
            <a href="/marks/ages-ago" class="btn"><?php echo unmark_phrase('View Marks'); ?></a>
            <button data-action="dismiss_this"><?php echo unmark_phrase('Do Nothing'); ?></button>
            <button data-action="archive_all"><?php echo unmark_phrase('Archive All'); ?></button>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($stats) && $stats['marks']['total'] < 5) : ?>
    <div class="sidebar-block">
        <div class="sidebar-inner">
            <a href="javascript:(function()%7Bl%3D%22<?php print rtrim(base_url(),'/'); ?>%2Fmark%2Fadd%3Furl%3D%22%2BencodeURIComponent(window.location.href)%2B%22%26title%3D%22%2BencodeURIComponent(document.title)%2B%22%26v%3D1%26nowindow%3Dyes%26%22%3Bvar%20e%3Dwindow.open(l%2B%22noui%3D1%22%2C%22Unmark%22%2C%22location%3D0%2Clinks%3D0%2Cscrollbars%3D0%2Ctoolbar%3D0%2Cwidth%3D594%2Cheight%3D485%22)%3B%7D)()" class="btn">Unmark+</a>
            <li><a target="_blank" rel="noopener noreferrer" href="https://chrome.google.com/webstore/detail/unmark/cdhnljlbeehjgddokagghpfgahhlifch"><?php echo unmark_phrase('Get the Chrome Extension') ?></a></li>
        </div>
    </div>
    <?php endif; ?>
    <?php $this->load->view('layouts/sidebar/sidebar_notices'); ?>
</div>
*/ ?>

<div id="mark-info-dump" class="sidebar-mark-info"></div>
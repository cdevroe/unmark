<div class="sidebar-default">
    <div class="sidebar-block" id="sidebar-graph">
        <div class="sidebar-inner">
            <?php if (isset($stats)) : ?>
            <p>
                <?php 
                    echo sprintf(ngettext('You saved %s mark today','You saved %s marks today',$stats['saved']['today']), '<span class="ns-today">' . $stats['saved']['today'] . '</span>');
                    echo ' ';
                    echo sprintf(_('and archived %s'), '<span class="na-today">' . $stats['archived']['today'] . '</span>'); 
                ?>.
            </p>
            <?php endif; ?>
        </div>
    </div>
    <?php if (isset($stats) && $stats['marks']['ages ago'] > 0) : ?>
    <div class="sidebar-block">
        <div class="sidebar-inner">
            <p><?php echo sprintf(ngettext('You have %s mark that are over 1 year old. Want to tidy up a bit?', 'You have %s mark that are over 1 year old. Want to tidy up a bit?',$stats['marks']['ages ago']), '<span class="ns-year">' . $stats['marks']['ages ago'] . '</span>')?></p>
            <a href="/marks/ages-ago" class="btn"><?php echo _('View Marks'); ?></a>
            <button data-action="dismiss_this"><?php echo _('Do Nothing'); ?></button>
            <button data-action="archive_all"><?php echo _('Archive All'); ?></button>
        </div>
    </div>
    <?php endif; ?>
    <div class="sidebar-block">
        <div class="sidebar-inner">
            <a href="javascript:(function()%7Bl%3D%22<?php print getFullUrl(); ?>%2Fmark%2Fadd%3Furl%3D%22%2BencodeURIComponent(window.location.href)%2B%22%26title%3D%22%2BencodeURIComponent(document.title)%2B%22%26v%3D1%26nowindow%3Dyes%26%22%3Bvar%20e%3Dwindow.open(l%2B%22noui%3D1%22%2C%22Unmark%22%2C%22location%3D0%2Clinks%3D0%2Cscrollbars%3D0%2Ctoolbar%3D0%2Cwidth%3D594%2Cheight%3D485%22)%3B%7D)()" class="btn">Unmark+</a>
            <p class="clear sidenote"><?php echo _('To add marks, drag the button above into your bookmark toolbar.'); ?>
                <br /> <?php echo _('Or, use our'); ?> <a href="https://chrome.google.com/webstore/detail/unmark/cdhnljlbeehjgddokagghpfgahhlifch" target="_blank"><?php echo _('Chrome Extension'); ?></a>. <a href="http://help.unmark.it/bookmarklet" target="_blank"><?php echo _('Learn More')?></a>.</p>
        </div>
    </div>
    <?php $this->load->view('layouts/sidebar/sidebar_notices'); ?>
</div>

<div id="mark-info-dump" class="sidebar-mark-info"></div>

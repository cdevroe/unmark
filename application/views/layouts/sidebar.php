<div class="sidebar-default">
    <div class="sidebar-block">
        <div class="sidebar-inner">
            <?php if (isset($stats)) : ?>
            <canvas id="unmark-graph" class="graph" width="400" height="100"></canvas>
            <p>
                You saved <span class="ns-today"><?php print $stats['saved']['today']; ?></span>
                links today and archived <span class="na-today"><?php print $stats['archived']['today']; ?></span>.
            </p>
            <?php endif; ?>
        </div>
    </div>
    <?php if (isset($stats) && $stats['marks']['ages ago'] > 0) : ?>
        <div class="sidebar-block">
            <div class="sidebar-inner">
                <p>You have <span class="ns-year"><?php print $stats['marks']['ages ago']; ?></span> links that are over 1 year old. Want to tidy up a bit?</p>
                <a href="/marks/ages-ago" class="btn">View Links</a>
                <button data-action="dismiss_this">Do Nothing</button>
                <button data-action="archive_all">Archive All</button>
            </div>
        </div>
    <?php endif; ?>
    <?php $this->load->view('layouts/sidebar/sidebar_notices'); ?>
</div>

<div id="mark-info-dump" class="sidebar-mark-info"></div>

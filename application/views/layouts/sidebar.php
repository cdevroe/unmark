<div class="sidebar-default">
    <div class="sidebar-block">
        <div class="sidebar-inner">
            <canvas id="nilai-graph" class="graph" width="500" height="150"></canvas>
            <p>You saved <?php print $stats['saved']['today']; ?> links today and archived <?php print $stats['archived']['today']; ?>.</p>
        </div>
    </div>
    <?php if ($stats['marks']['ages ago'] > 0) : ?>
        <div class="sidebar-block">
            <div class="sidebar-inner">
                <p>You have <?php print $stats['marks']['ages ago']; ?> links that are over 1 year old. Want to tidy up a bit?</p>
                <a href="/marks/ages-ago" class="btn">View Links</a>
                <button data-action="dismiss_this">Do Nothing</button>
                <button data-action="archive_all">Archive All</button>
            </div>
        </div>
    <?php endif; ?>
</div>

<div id="mark-info-dump" class="sidebar-mark-info"></div>
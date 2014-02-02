<div class="sidebar-default">
    <div class="sidebar-block">
        <div class="sidebar-inner">
            <canvas class="graph" width="500" height="100"></canvas>
            <p>You saved <?php print $stats['saved']['today']; ?> links today and archived only <?php print $stats['archived']['today']; ?>.</p>
        </div>
    </div>
    <div class="sidebar-block">
        <div class="sidebar-inner">
            <p>You have <?php print $stats['marks']['last year']; ?> links that are over 1 year old. Want to tidy up a bit?</p>
            <button data-action="view-this">View Links</button>
            <button data-action="dismiss-this">Do Nothing</button>
            <button data-action="delete-mark">Delete All</button>
        </div>
    </div>
</div>

<div id="mark-info-dump" class="sidebar-mark-info"></div>
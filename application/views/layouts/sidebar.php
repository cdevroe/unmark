<div class="sidebar-default">
    <div class="sidebar-block">
        <div class="sidebar-inner">
            <p>You saved <?php print $stats['saved']['yesterday']; ?> links yesterday and archived only <?php print $stats['archived']['yesterday']; ?>.</p>
        </div>
    </div>
    <div class="sidebar-block">
        <div class="sidebar-inner">
            <p>You have <?php print $stats['marks']['last year']; ?> links that are over 1 year old. Want to tidy up a bit?</p>
            <button data-action="view-links">View Links</button>
            <button data-action="dismiss">Do Nothing</button>
            <button data-action="delete">Delete All</button>
        </div>
    </div>
</div>

<div id="mark-info-dump" class="sidebar-mark-info"></div>
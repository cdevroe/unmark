<div class="sidebar-block">
    <div class="sidebar-inner">
        <p>You saved <?php print $stats['saved']['yesterday']; ?> links yesterday and archived only <?php print $stats['archived']['yesterday']; ?>.</p>
    </div>
</div>
<div class="sidebar-block">
    <div class="sidebar-inner">
        <p>You have <?php print $stats['marks']['last year']; ?> links that are over 1 year old. Want to tidy up a bit?</p>
        <button id="action-view-links">View Links</button>
        <button id="action-dismiss">Do Nothing</button>
        <button id="action-delete">Delete All</button>
    </div>
</div>
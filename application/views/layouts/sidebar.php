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

<div class="sidebar-mark-info">
    <div class="sidebar-block">
        <div class="sidebar-inner">
            <div class="sidebar-action">
                <a class="action" data-action="sidebar-expand" href="#"><i class="barley-icon-resize-full"></i></a>
                <a class="action" data-action="sidebar-collapse" href="#"><i class="barley-icon-remove"></i></a>
            </div>
            <div class="sidebar-label">
                <h4></h4><a class="action" data-action="marks-edit-label" href="#"><i class="barley-icon-pencil"></i></a>
            </div>
            <div class="sidebar-info-panel">
                <div class="sidebar-info-preview"></div>
                <div class="sidebar-info-notes"></div>
                <div class="sidebar-info-share"></div>
            </div>
            <button data-action="delete-this">Delete Link</button>
        </div>
    </div>
</div>
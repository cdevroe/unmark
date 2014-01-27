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
    <div class="sidebar-action">
        <a class="action" data-action="sidebar-expand" href="#"><i class="barley-icon-resize-full"></i></a>
        <a class="action" data-action="sidebar-collapse" href="#"><i class="barley-icon-remove"></i></a>
    </div>
    <div class="sidebar-label label-3">
        <h3>Watch</h3><a class="action" data-action="marks-edit-label" href="#"><i class="barley-icon-pencil"></i></a>
    </div>
    <div class="sidebar-info-panel">
        <h4>Preview <i class="barley-icon-chevron-up"></i></h4>
        <section class="sidebar-info-preview">
            <iframe width="448" height="252" src="//www.youtube.com/embed/6KwPHaylRjo" frameborder="0" allowfullscreen></iframe>
            <p>Corned beef venison chuck jowl tenderloin brisket hamburger filet mignon landjaeger doner tongue pastrami ground round t-bone porchetta. Pastrami corned beef salami beef frankfurter turducken shoulder jerky tri-tip tongue kielbasa bacon shankle filet mignon. Pork belly tongue strip steak salami hamburger.</p>
        </section>
        <h4>Notes <i class="barley-icon-chevron-up"></i></h4>
        <section class="sidebar-info-notes">
            <p>Corned beef venison chuck jowl tenderloin brisket hamburger filet mignon landjaeger doner tongue pastrami ground round t-bone porchetta. Pastrami corned beef salami beef frankfurter turducken shoulder jerky tri-tip tongue kielbasa bacon shankle filet mignon. Pork belly tongue strip steak salami hamburger.</p>
        </section>
        <h4>Share <i class="barley-icon-chevron-up"></i></h4>
        <section class="sidebar-info-share">
            <a class="social-link" href="#"><i class="barley-icon-social-twitter"></i></a>
            <a class="social-link" href="#"><i class="barley-icon-social-facebook"></i></a>
            <a class="social-link" href="#"><i class="barley-icon-social-dropbox"></i></a>
            <a class="social-link" href="#"><i class="barley-icon-pinterest"></i></a>
            <a class="social-link" href="#"><i class="barley-icon-envelope-alt"></i></a>
        </section>
    </div>
    <button data-action="delete-this">Delete Link</button>
</div>
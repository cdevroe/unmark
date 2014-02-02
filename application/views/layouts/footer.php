            </div>
            <div class="sidebar-content"><?php include('sidebar.php'); ?></div>
        </div>
    </div> <!-- end main-wrapper -->



<script type="text/javascript">
var nilai  = nilai || {};
nilai.vars = {};
nilai.vars.csrf_token   = '<?php print $csrf_token; ?>';
</script>
<script src="/assets/libraries/jquery/jquery-2.1.0.min.js"></script>
<script src="/assets/js/plugins/hogan.js"></script>
<script src="/assets/js/plugins/nilai-graph.js"></script>
<script src="/assets/js/templates/nilai-sidebar.js"></script>
<script src="/assets/js/nilai.js"></script>

<script type="text/javascript">
$(document).ready(function() { 

    // Graph for Saved
    nilai.graph.initGraph($('.graph'), -5, 0, [ 
        12, 
        <?php print $stats['saved']['4 days ago']; ?>, 
        <?php print $stats['saved']['3 days ago']; ?>, 
        <?php print $stats['saved']['2 days ago']; ?>, 
        <?php print $stats['saved']['yesterday']; ?>,
        <?php print $stats['saved']['today']; ?>
        ], '#CAC8C9', '#727071'); 

    // Graph for Archived
    nilai.graph.initGraph($('.graph'), -5, 0, [ 
        12, 
        <?php print $stats['archived']['4 days ago']; ?>, 
        <?php print $stats['archived']['3 days ago']; ?>, 
        <?php print $stats['archived']['2 days ago']; ?>, 
        <?php print $stats['archived']['yesterday']; ?>,
        <?php print $stats['archived']['today']; ?>
        ], '#CAC8C9', '#727071');

});
</script>

    
</body>
</html>
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
<script type="text/javascript" src="/assets/libraries/raphael/raphael.min.js"></script>
<script src="/assets/js/plugins/hogan.js"></script>
<script src="/assets/js/plugins/nilai-graph.js"></script>
<script src="/assets/js/templates/nilai-sidebar.js"></script>
<script src="/assets/js/nilai.js"></script>
    
</body>
</html>
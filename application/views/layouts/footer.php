            </div>
            <div class="sidebar-content"><?php $this->load->view('layouts/sidebar'); ?></div>
        </div>
    </div> <!-- end main-wrapper -->
</div> <!-- end nilai-wrapper -->

<?php $this->load->view('layouts/userforms'); ?>

<?php $this->load->view('layouts/jsvars'); ?>

<script src="/assets/libraries/jquery/jquery-2.1.0.min.js"></script>
<script src="/assets/js/plugins/hogan.js"></script>
<script src="/assets/js/plugins/pjax.js"></script>
<script src="/assets/js/plugins/Chart.min.js"></script>
<script src="/assets/js/plugins/nilai-graph.js"></script>
<script src="/assets/js/templates/nilai-templates.js"></script>
<script src="/assets/js/nilai.js"></script>
<script src="/assets/js/nilai.actions.js"></script>
<script src="/assets/js/nilai.marks.js"></script>
<script src="/assets/js/nilai.client.js"></script>
<script src="/assets/js/nilai.init.js"></script>


<script type="text/javascript">
$(document).ready(function() { 
    nilai.createGraph(<?php print $stats['archived']['4 days ago'].", ".$stats['archived']['3 days ago'].", ".$stats['archived']['2 days ago'].", ".$stats['archived']['yesterday'].", ".$stats['archived']['today'].", ".$stats['saved']['4 days ago'].", ".$stats['saved']['3 days ago'].", ".$stats['saved']['2 days ago'].", ".$stats['saved']['yesterday'].", ".$stats['saved']['today']; ?>);
});
</script>


</body>
</html>
<?php $this->load->view('layouts/userforms'); ?>

<?php $this->load->view('layouts/jsvars'); ?>

<script src="/assets/libraries/jquery/jquery-2.1.0.min.js"></script>
<script src="/assets/js/production/unmark.plugins.js"></script>
<script src="/assets/js/production/unmark.loggedin.js"></script>

<?php if (isset($stats)) : ?>
<script type="text/javascript">
$(document).ready(function() {
    unmark.createGraph(<?php print $stats['archived']['4 days ago'].", ".$stats['archived']['3 days ago'].", ".$stats['archived']['2 days ago'].", ".$stats['archived']['yesterday'].", ".$stats['archived']['today'].", ".$stats['saved']['4 days ago'].", ".$stats['saved']['3 days ago'].", ".$stats['saved']['2 days ago'].", ".$stats['saved']['yesterday'].", ".$stats['saved']['today']; ?>);
});
</script>
<?php endif; ?>

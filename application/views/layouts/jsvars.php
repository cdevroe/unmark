<script type="text/javascript">
var nilai  = nilai || {};
nilai.vars = {};
nilai.vars.csrf_token = '<?php print $csrf_token; ?>';
<?php 
// Check for Page Stats
if(isset($per_page)) {
    print "nilai.vars.per_page = $per_page;\n";
}
// Check for Stats
if(isset($stats)) {
    print "nilai.vars.stats_set   = true;\n";
} else {
    print "nilai.vars.stats_set   = false;\n";
}
// Check for labels
if(isset($labels)) {
    print "nilai.vars.labels_set   = true;";
} else {
    print "nilai.vars.labels_set   = false;";
}
?>        
</script>
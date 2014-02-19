<script type="text/javascript">
var unmark  = unmark || {};
unmark.vars = {};
unmark.vars.csrf_token = '<?php print $csrf_token; ?>';
<?php 
// Check for Page Stats
if(isset($per_page)) {
    print "unmark.vars.per_page = $per_page;\n";
}
// Check for Stats
if(isset($stats)) {
    print "unmark.vars.stats_set   = true;\n";
} else {
    print "unmark.vars.stats_set   = false;\n";
}
// Check for labels
if(isset($labels)) {
    print "unmark.vars.labels_set   = true;";
} else {
    print "unmark.vars.labels_set   = false;";
}
?>        
</script>
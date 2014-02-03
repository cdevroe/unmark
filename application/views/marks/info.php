<html>
<head>
    <title>Nilai : Mark Added</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/nilai.css" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
</head>
<body>

<?php $nice_url = rtrim(preg_replace('/https?:\/\/(www.)?/', '', $mark->url), '/'); ?>

<div class="mark-added">
    <div class="mark-added-block">
        This link has been added to your stream.
        <i class="barley-icon-ok"></i>
    </div>

    <div class="mark-added-info">
        <h1><?php print $mark->title; ?></h1>
        <span><?php print $nice_url; ?></span>
    </div>
    
    <div class="mark-added-label mark-added-settings">
        <a class="action" data-action="add_label" href="">Add A Label</a>
    </div>

    <div class="mark-added-note mark-added-settings">
        <a class="action" data-action="add_label" href="">Add A Note</a>
        <section class="mark-add-notes" contenteditable="true"></section>
    </div>

    <div class="mark-added-actions">
        <button class="delete" data-action="delete_mark" data-id="<?php print $mark->mark_id; ?>">Delete Link</button>
        <button>Update & Close</button>
    </div>


</div>


<script type="text/javascript">
var nilai  = nilai || {};
nilai.vars = {};
nilai.vars.csrf_token   = '<?php print $csrf_token; ?>';
</script>

<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="/assets/js/nilai.js"></script>

</body>
</html>
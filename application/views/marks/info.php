<html>
<head>
    <title>Nilai : Mark Added</title>
</head>
<body>
    <h1>Mark Added</h1>

    You add a link to the page - <a href="<?php print $mark->url; ?>"><?php print $mark->title; ?></a> <br /><br />

    Currently labeled as : <pre style="display:inline;"><?php print $mark->label_name; ?></pre><br /><br />

    <form action="#" type="post" id="temp_label">
        <select name="label_id" id="templabel">
            <option value="1">Unlabeled</option>
            <option value="2">Read</option>
            <option value="3">Watch</option>
            <option value="4">Listen</option>
            <option value="5">Buy</option>
            <option value="6">Eat & Drink</option>
            <option value="7">Do</option>
        </select>
        <input id="tempmarkid" type="hidden" name="mark_id" value="<?php print $mark->mark_id; ?>" />
    </form>


<script type="text/javascript">
var nilai  = nilai || {};
nilai.vars = {};
nilai.vars.csrf_token   = '<?php print $csrf_token; ?>';
</script>

<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="/assets/js/nilai.js"></script>

</body>
</html>
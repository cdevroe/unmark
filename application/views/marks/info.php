<html>
<head>
    <title>Unmark : Mark Added</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/unmark.css" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
    <script>
        /* grunticon Stylesheet Loader | https://github.com/filamentgroup/grunticon | (c) 2012 Scott Jehl, Filament Group, Inc. | MIT license. */
        window.grunticon=function(e){if(e&&3===e.length){var t=window,n=!!t.document.createElementNS&&!!t.document.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect&&!!document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image","1.1"),A=function(A){var o=t.document.createElement("link"),r=t.document.getElementsByTagName("script")[0];o.rel="stylesheet",o.href=e[A&&n?0:A?1:2],r.parentNode.insertBefore(o,r)},o=new t.Image;o.onerror=function(){A(!1)},o.onload=function(){A(1===o.width&&1===o.height)},o.src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="}};
        grunticon( [ "/assets/css/icons.data.svg.css", "/assets/css/icons.data.png.css", "/assets/css/icons.fallback.css" ] );
    </script>
    <noscript><link href="/assets/css/icons.fallback.css" rel="stylesheet"></noscript>
</head>
<body class="unmark-solo greybg">

<?php $nice_url = rtrim(preg_replace('/https?:\/\/(www.)?/', '', $mark->url), '/'); ?>

<div class="mark-added">
    <div class="mark-added-block">
        This link has been added to your stream.
        <i class="icon-check"></i>
    </div>

    <div class="mark-added-info">
        <h1><?php print $mark->title; ?></h1>
        <span><?php print $nice_url; ?></span>
    </div>
    
    <div class="mark-added-label mark-added-settings">
        <span id="label-chosen"></span>
        <a class="action" data-action="marks_addLabel" href="#">Unlabeled</a>
        <section data-id="<?php print $mark->mark_id; ?>">
            <ul class="label-choices"></ul>
        </section>
    </div>

    <div class="mark-added-note mark-added-settings">
        <a class="action" data-action="marks_addNotes" href="">Add A Note</a>
        <section class="hideoutline" data-id="<?php print $mark->mark_id; ?>" contenteditable="true"></section>
    </div>

    <div class="mark-added-actions">
        <button class="delete" data-action="delete_mark" data-view="bookmarklet" data-id="<?php print $mark->mark_id; ?>">Delete Link</button>
        <button data-action="close_window">Update &amp; Close</button>
    </div>


</div>

<?php $this->load->view('layouts/jsvars'); ?>

<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="/assets/js/unmark.js"></script>
<script src="/assets/js/unmark.actions.js"></script>
<script src="/assets/js/unmark.marks.js"></script>
<script src="/assets/js/unmark.add.js"></script>
<script src="/assets/js/unmark.init.js"></script>

</body>
</html>
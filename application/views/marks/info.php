<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title><?php echo _('Unmark : Mark Added'); ?></title>
    <link href='//fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/unmark.css?v=<?php echo ASSET_VERSION; ?>" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
    <script src="/assets/js/plugins/modernizr-2.7.1.min.js"></script>
    <script>
        /* grunticon Stylesheet Loader | https://github.com/filamentgroup/grunticon | (c) 2012 Scott Jehl, Filament Group, Inc. | MIT license. */
        window.grunticon=function(e){if(e&&3===e.length){var t=window,n=!!t.document.createElementNS&&!!t.document.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect&&!!document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image","1.1"),A=function(A){var o=t.document.createElement("link"),r=t.document.getElementsByTagName("script")[0];o.rel="stylesheet",o.href=e[A&&n?0:A?1:2],r.parentNode.insertBefore(o,r)},o=new t.Image;o.onerror=function(){A(!1)},o.onload=function(){A(1===o.width&&1===o.height)},o.src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="}};
        grunticon( [ "/assets/css/icons.data.svg.css", "/assets/css/icons.data.png.css", "/assets/css/icons.fallback.css" ] );
    </script>
    <noscript><link href="/assets/css/icons.fallback.css" rel="stylesheet"></noscript>
</head>
<body class="unmark-solo greybg">

<div class="mark-added" data-label="<?php print $mark->label_id; ?>" data-label-name="<?php print $mark->label_name; ?>">
    <div class="mark-added-block">
        <?php echo _('This link has been added to your stream.'); ?>
        <i class="icon-check"></i>
    </div>

    <div class="mark-added-info">
        <h1><?php print $mark->title; ?></h1>
        <span><?php print niceUrl($mark->url); ?></span>
    </div>

    <div class="mark-added-label mark-added-settings">
        <span id="label-chosen"></span>
        <a id="currLabel" class="action" data-action="marks_addLabel" href="#"><?php echo _('Unlabeled'); ?></a>
        <section data-id="<?php print $mark->mark_id; ?>">
            <ul class="label-choices"></ul>
        </section>
    </div>

    <div class="mark-added-note mark-added-settings">
        <?php if (empty($mark->notes)) : ?>
            <a class="action" data-action="marks_addNotes" href=""><?php echo _('Add A Note'); ?></a>
            <textarea class="mark-added-notes-area hide" data-id="<?php print $mark->mark_id; ?>" placeholder="<?php echo _('Type note text or #tags here...'); ?>"></textarea>
        <?php else : ?>
            <textarea class="mark-added-notes-area" data-id="<?php print $mark->mark_id; ?>" placeholder="<?php echo _('Type note text or #tags here...'); ?>"><?php print $mark->notes; ?></textarea>
        <?php endif; ?>
    </div>

    <div class="mark-added-actions">
        <button class="delete" data-action="delete_mark" data-view="bookmarklet" data-id="<?php print $mark->mark_id; ?>"><?php echo _('Delete Link'); ?></button>
        <button data-action="close_window"><?php echo _('Update &amp; Close'); ?></button>
    </div>

</div>

<?php $this->load->view('layouts/jsvars'); ?>

<script src="/assets/libraries/jquery/jquery-2.1.0.min.js"></script>
<script src="/assets/js/production/unmark.bookmarklet.js?v=<?php echo ASSET_VERSION; ?>"></script>

</body>
</html>
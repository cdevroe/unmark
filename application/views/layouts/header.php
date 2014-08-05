<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="apple-mobile-web-app-capable" content="no">
    <title><?php echo _('Unmark'); ?></title>
    <link href='//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic|Merriweather:400,300,300italic,400italic,700,700italic|Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/unmark.css?v=<?php echo ASSET_VERSION; ?>" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/touch_icons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/touch_icons/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/touch_icons/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/touch_icons/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/touch_icons/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/touch_icons/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/touch_icons/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/touch_icons/apple-touch-icon-152x152.png">
    <script src="/assets/js/plugins/modernizr-2.7.1.min.js"></script>
    <script>
        /* grunticon Stylesheet Loader | https://github.com/filamentgroup/grunticon | (c) 2012 Scott Jehl, Filament Group, Inc. | MIT license. */
        window.grunticon=function(e){if(e&&3===e.length){var t=window,n=!!t.document.createElementNS&&!!t.document.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect&&!!document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image","1.1"),A=function(A){var o=t.document.createElement("link"),r=t.document.getElementsByTagName("script")[0];o.rel="stylesheet",o.href=e[A&&n?0:A?1:2],r.parentNode.insertBefore(o,r)},o=new t.Image;o.onerror=function(){A(!1)},o.onload=function(){A(1===o.width&&1===o.height)},o.src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="}};
        grunticon( [ "/assets/css/icons.data.svg.css", "/assets/css/icons.data.png.css", "/assets/css/icons.fallback.css" ] );
    </script>
    <noscript><link href="/assets/css/icons.fallback.css" rel="stylesheet"></noscript>
</head>

<?php if (isset($active_label)) : ?>
<body id="unmark" class="label-<?php print $active_label['label_id']; ?>" data-lookup="<?php print $lookup_type; ?>">
<?php else : ?>
<body id="unmark" data-lookup="<?php if(isset($lookup_type)) { print $lookup_type; } ?>">
<?php endif; ?>

    <div id="unmark-wrapper">

    <a id="mobile-sidebar-show" alt="<?php echo _('View/Hide Details'); ?>" href="#"><i class="icon-ellipsis"></i></a>

    <div class="branding"><div class="icon-logo_text_white logo-text"></div></div>

    <?php $this->load->view('layouts/navigation'); ?>

    <div class="main-wrapper">
        <div class="inner-wrapper">
            <div class="main-content">

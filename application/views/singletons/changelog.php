<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title><?php echo unmark_phrase('Unmark : Version Changelog'); ?></title>
    <link href='//fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/unmark.css?v=<?php echo $this->config->item('unmark_version'); ?>" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
    <script src="/assets/js/plugins/modernizr-2.7.1.min.js"></script>
    <script>
        /* grunticon Stylesheet Loader | https://github.com/filamentgroup/grunticon | (c) 2012 Scott Jehl, Filament Group, Inc. | MIT license. */
        window.grunticon=function(e){if(e&&3===e.length){var t=window,n=!!t.document.createElementNS&&!!t.document.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect&&!!document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image","1.1"),A=function(A){var o=t.document.createElement("link"),r=t.document.getElementsByTagName("script")[0];o.rel="stylesheet",o.href=e[A&&n?0:A?1:2],r.parentNode.insertBefore(o,r)},o=new t.Image;o.onerror=function(){A(!1)},o.onload=function(){A(1===o.width&&1===o.height)},o.src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="}};
        grunticon( [ "/assets/css/icons.data.svg.css", "/assets/css/icons.data.png.css", "/assets/css/icons.fallback.css" ] );
    </script>
    <noscript><link href="/assets/css/icons.fallback.css" rel="stylesheet"></noscript>
</head>
<body class="unmark-solo">

<h1>Changelog</h1>

<p>This changelog relates to the hosted version of Unmark at <a href="https://unmark.it/">unmark.it</a></p>

<h2>Saturday, February 23, 2019</h2>
<small>Version: 1.8.095</small>
<ul>
    <li>New: An all-new design</li>
    <li>New: A new icon!</li>
    <li>New: Support for importing from Pocket</li>
    <li>Fix: Better compatibility with password managers</li>
    <li>New: Progressive Web App Support (to install, Add To Homescreen on your mobile device)</li>
    <li>New: Share to Unmark (Android only)</li>
    <li>Fix: Speed improvements throughout the app</li>
</ul>

<script type="text/javascript">
var unmark  = unmark || {};
unmark.vars = {};
unmark.vars.token   = '<?php print $token; ?>';
unmark.vars.csrf_token   = '<?php print $csrf_token; ?>';
</script>

<script src="/assets/libraries/jquery/jquery-2.1.0.min.js"></script>
<script src="/assets/js/production/unmark.loggedout.js?v=<?php echo $this->config->item('unmark_version'); ?>"></script>

</body>
</html>

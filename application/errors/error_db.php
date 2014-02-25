<!DOCTYPE html>
<html>
<head>
    <title>Eek, something is wrong</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/unmark.css">
    <link rel="icon" type="image/ico" href="/favicon.ico">
    <script>
        /* grunticon Stylesheet Loader | https://github.com/filamentgroup/grunticon | (c) 2012 Scott Jehl, Filament Group, Inc. | MIT license. */
        window.grunticon=function(e){if(e&&3===e.length){var t=window,n=!!t.document.createElementNS&&!!t.document.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect&&!!document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image","1.1"),A=function(A){var o=t.document.createElement("link"),r=t.document.getElementsByTagName("script")[0];o.rel="stylesheet",o.href=e[A&&n?0:A?1:2],r.parentNode.insertBefore(o,r)},o=new t.Image;o.onerror=function(){A(!1)},o.onload=function(){A(1===o.width&&1===o.height)},o.src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="}};
        grunticon( [ "/assets/css/icons.data.svg.css", "/assets/css/icons.data.png.css", "/assets/css/icons.fallback.css" ] );
    </script>
    <noscript><link href="/assets/css/icons.fallback.css" rel="stylesheet"></noscript>
</head>
<body class="unmark-solo" id="unmark-login">

	<div style="width: 50%; margin: 0 auto; text-align: left; background-color: #ccc; padding: 5px; margin-top: 30px;">
	<?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'production'): ?>
		<h1>An unexpected error has occured</h1>
		<p>The issue has been logged and will be taken care of shortly.</p>
	<?php else: ?>
		<h1><?php print $heading; ?></h1>
		<p><?php print $message; ?></p>
	<?php endif; ?>
	</div>

</body>
</html>
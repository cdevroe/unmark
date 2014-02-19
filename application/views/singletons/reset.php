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
<body class="unmark-solo" id="unmark-login">

<div class="resetWrapper">
    <div class="loginInner">
        <div class="login-ball"></div>
        <h1>Reset Password</h1>
        <form id="unmarkReset" method="post" action="/login">
            <input type="password" class="field-input" name="password" id="password" placeholder="New Password..." autocomplete="off" />
            <input type="password" class="field-input" name="password2" id="password2" placeholder="Once More..." autocomplete="off" />
            <button class="login-submit" type="submit"><i class="icon-go"></i></button>
        </form>
        <div class="response-message"></div>
        <a href="#" class="forgot-pass">How did I get here?</a>
        <div class="gethere">
            <p>You clicked on a link in an email from us.</p>
            <p class="last">You can choose a new password here and be on your way.</p>
            <a class="help" href="/help">HELP</a><a class="home" href="/">UNMARK.IT</a>
        </div>
    </div>
</div>


<script type="text/javascript">
var unmark  = unmark || {};
unmark.vars = {};
unmark.vars.token   = '<?php print $token; ?>';
unmark.vars.csrf_token   = '<?php print $csrf_token; ?>';     
</script>
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="/assets/js/unmark.js"></script>
<script src="/assets/js/unmark.reset.js"></script>

</body>
</html>
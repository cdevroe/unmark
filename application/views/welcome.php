<html>
<head>
    <title>Welcome to Unmark</title>
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

<div class="loginWrapper">
    <div class="loginInner">
        <div class="login-ball"></div>
        <h1>Sign In</h1>
        <form id="unmarkLogin" method="post" action="/login">
            <input type="email" class="field-input" name="email" id="email" placeholder="Email Address" autocomplete="off" autocapitalize="off" autocorrect="off" />
            <input type="password" class="field-input" name="password" id="password" placeholder="Password" autocomplete="off" />
            <button class="login-submit" type="submit"><i class="icon-go"></i></button>
        </form>
        <div class="response-message"></div>
        <a href="/register" class="register">Register for Unmark</a> &nbsp; | &nbsp; <a href="#" class="forgot-pass">Forgot Password?</a>
    </div>
</div>

<div class="forgotPassWrapper">
    <div class="loginInner">
        <div class="login-ball"></div>
        <h1>Reset Password</h1>
        <form id="unmarkForgotPass" method="post" action="/tools/forgotPassword">
            <input type="email" class="field-input" name="email" id="forgot_email" placeholder="Email Address" autocomplete="off" autocapitalize="off" autocorrect="off" />
            <button class="forgot-submit" type="submit"><i class="icon-go"></i></button>
        </form>
        <div class="response-message"></div>
        <a href="#" class="forgot-pass">Need to Sign In?</a>
    </div>
</div>

<div class="unmark-spinner"></div>
<div class="unmark-success"><i class="icon-check"></i></div>

<?php $this->load->view('layouts/footer_unlogged_scripts')?>

</body>
</html>
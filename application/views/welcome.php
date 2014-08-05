<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title><?php echo _('Unmark - The to do app for bookmarks.') ?></title>
    <link href='//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic|Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/unmark.css?v=<?php echo ASSET_VERSION; ?>" />
    <link rel="stylesheet" href="/assets/css/unmark_welcome.css?v=<?php echo ASSET_VERSION; ?>" />
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
<body class="unmark-solo" id="unmark-login">

<div id="unmark_login_page">
    <div class="loginWrapper">
        <div class="loginInner">
            <div class="login-ball"><img src="/assets/images/logo.png" /></div>
            <h1><?php echo _('Sign In To') ?></h1>
            <div class="login-text"><img src="/assets/images/icons/logo_text_light.png" /></div>
            <form id="unmarkLogin" method="post" action="/login">
                <input type="email" class="field-input" name="email" id="email" placeholder="<?php echo _('Email Address') ?>" autocapitalize="off" />
                <input type="password" class="field-input" name="password" id="password" placeholder="<?php echo _('Password') ?>" />
                <button class="login-submit" type="submit"><i class="icon-go"></i></button>
            </form>
            <div class="response-message"></div>
            <a href="#" class="forgot-pass" title="<?php echo _('Forgot Password?') ?>"><?php echo _('Forgot Password?') ?></a>
            <span class="sep">&bull;</span>
            <a href="http://help.unmark.it" class="register" title="<?php echo _('Visit Us') ?>"><?php echo _('What is Unmark?') ?></a>
        </div>
    </div>

    <div class="forgotPassWrapper">
        <div class="loginInner">
            <div class="login-ball"><img src="/assets/images/logo.png" /></div>
            <h1><?php echo _('Reset Password For') ?></h1>
            <div class="login-text"><img src="/assets/images/icons/logo_text_light.png" /></div>
            <form id="unmarkForgotPass" method="post" action="/tools/forgotPassword">
                <input type="email" class="field-input" name="email" id="forgot_email" placeholder="<?php echo _('Email Address') ?>" autocomplete="off" autocapitalize="off" autocorrect="off" />
                <button class="forgot-submit" type="submit"><i class="icon-go"></i></button>
            </form>
            <div class="response-message"></div>
            <a href="#" class="forgot-pass" title="<?php echo _('Sign into your account') ?>"><?php echo _('Need to Sign In?') ?></a>
        </div>
    </div>

    <div class="unmark-spinner"></div>
    <div class="unmark-success"><i class="icon-check"></i></div>

</div>

<?php $this->load->view('layouts/footer_unlogged_scripts'); ?>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title><?php echo _('Unmark : User Registration') ?></title>
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
<body class="unmark-solo" id="unmark-login">

<div class="loginWrapper">
    <div class="loginInner internalform">
        <div class="login-ball"><img src="/assets/images/logo.png" /></div>
        <h1><?php echo _('Register For') ?></h1>
        <div class="login-text"><img src="/assets/images/icons/logo_text_light.png" /></div>
        <form id="register_user" method="post" action="#" class="ajaxsbmt">
            <input type="email" class="field-input" name="email" id="email" placeholder="<?php echo _('Email Address') ?>" autocomplete="off" autocapitalize="off" autocorrect="off" />
            <input type="password" class="field-input" name="password" id="password" placeholder="<?php echo _('Password') ?>" autocomplete="off" />
            <input type="password" class="field-input" name="password2" id="password2" placeholder="<?php echo _('Password Again') ?>" autocomplete="off" />
            <button class="login-submit" type="submit"><i class="icon-go"></i></button>
        </form>
        <div class="response-message"></div>
        <a href="/" class="register"><?php echo _('Have An Account? Sign In Here') ?></a>
    </div>
</div>

<?php $this->load->view('layouts/footer_unlogged_scripts')?>

</body>
</html>

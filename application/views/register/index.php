<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title><?php echo unmark_phrase('Unmark : User Registration') ?></title>
    <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,600,600i,700" rel="stylesheet">
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
<body class="unmark-solo" id="unmark-login">
<div id="unmark_login_page">
<div class="loginWrapper">
    <div class="loginInner internalform">
      <div class="login-branding">
        <div class="login-branding-wrapper">
          <div class="login-ball">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 60"><defs><style>.a{fill:#5cc0e8;}.b{fill:#f3776e;}.c{fill:#c1e1f1;}.d{fill:#35acd4;}.e{fill:#0098cb;}</style></defs><path class="a" d="M0,0V40a20,20,0,0,0,40,0V0H0Z"/><polygon class="b" points="40 40 40 0 0 0 40 40"/><polygon class="c" points="40 40 0 40 0 0 40 40"/><path class="d" d="M0.69,45.22L40,40H0A20,20,0,0,0,.69,45.22Z"/><path class="e" d="M0.09,41.81L40,40H0C0,40.61,0,41.21.09,41.81Z"/></svg>
          </div>
          <?php /* <h1><?php echo unmark_phrase('Register For') ?></h1> */ ?>
          <div class="login-text">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 136 28.76"><title>um_text</title><path d="M12.62,21.07c0,3.44-2,5.25-4.93,5.25s-4.81-1.76-4.81-5.25V8.93H0V21.51A7.35,7.35,0,0,0,2,27a7,7,0,0,0,5.09,1.84,7.22,7.22,0,0,0,5.57-2.44v2.2H15.5V8.93H12.62V21.07ZM31.07,8.69a7.1,7.1,0,0,0-5.53,2.4V8.93H22.66V28.64h2.88V16.5c0-3.44,1.92-5.25,4.89-5.25S35.28,13,35.28,16.5V28.64h2.88V16a7.28,7.28,0,0,0-2-5.49A6.91,6.91,0,0,0,31.07,8.69Zm35.11,0A7.54,7.54,0,0,0,59.69,12a6.5,6.5,0,0,0-6-3.28,7.19,7.19,0,0,0-5.57,2.4V8.93H45.19V28.64h2.88V16.5c0-3.44,2-5.25,4.93-5.25S57.85,13,57.85,16.5V28.64h2.88V16.18a4.6,4.6,0,0,1,4.93-4.93c3,0,4.85,1.76,4.85,5.25V28.64h2.88V16a7.33,7.33,0,0,0-2.12-5.49A7.18,7.18,0,0,0,66.18,8.69Zm20.54,0c-3.2,0-5.21.64-7,2.92l2,1.8c1.16-1.64,2.44-2.24,5-2.24,3.6,0,5,1.44,5,4.33v1.88H85.79c-4.41,0-6.81,2.24-6.81,5.69a5.68,5.68,0,0,0,1.48,4c1.24,1.28,2.84,1.8,5.57,1.8s4.09-.52,5.69-2.12v1.88H94.6V15.26C94.6,11,92,8.69,86.71,8.69Zm5,13c0,1.64-.32,2.76-1,3.4-1.24,1.2-2.72,1.32-4.41,1.32-3.12,0-4.53-1.08-4.53-3.36s1.44-3.48,4.41-3.48h5.53v2.12Zm12.8-10.33V8.93h-2.88V28.64h2.88V16.54c0-3,1.84-5.29,4.73-5.29a4.12,4.12,0,0,1,3.4,1.44l2.12-2.16A6.07,6.07,0,0,0,110,8.69,6.62,6.62,0,0,0,104.51,11.34Zm23.64,5.13,6.69-7.53h-3.6l-8.89,10.25V0.12h-2.88V28.64h2.88V23l3.85-4.41,6.25,10.09H136Z" transform="translate(0 -0.12)"/></svg>
          </div>
        </div>
      </div>
      <form id="register_user" method="post" action="#" class="ajaxsbmt">
            <input type="email" class="field-input" name="email" id="email" placeholder="<?php echo unmark_phrase('Email Address') ?>" autocomplete="off" autocapitalize="off" autocorrect="off" />
            <input type="password" class="field-input" name="password" id="password" placeholder="<?php echo unmark_phrase('Password') ?>" autocomplete="off" />
            <input type="password" class="field-input" name="password2" id="password2" placeholder="<?php echo unmark_phrase('Password Again') ?>" autocomplete="off" />
            <button class="login-submit" type="submit">Register</button>
        </form>
        <div class="response-message"></div>
        <div class="login-links">
          <div class="login-links-wrapper">
            <p><em>By registering an account, you are agreeing with our<a href="https://www.notion.so/cdevroe/Unmark-Terms-of-Use-505bfd6398634ceaba0865274b9ba258" class="register" title="Terms of Use"> <?php echo unmark_phrase('Terms of Use') ?></a>.</em></p>
            <p><a href="/" class="register"><?php echo unmark_phrase('Have An Account? Sign In Here') ?></a></p>
          </div>
        </div>
    </div>
</div>
</div>
<?php $this->load->view('layouts/footer_unlogged_scripts')?>

</body>
</html>

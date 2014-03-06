<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>Unmark - The to do app for bookmarks.</title>
    <link href='//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic|Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/unmark.css" />
    <link rel="stylesheet" href="/assets/css/unmark_welcome.css" />
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
            <h1>Sign In To</h1>
            <div class="login-text"><img src="/assets/images/icons/logo_text_light.png" /></div>
            <form id="unmarkLogin" method="post" action="/login">
                <input type="email" class="field-input" name="email" id="email" placeholder="Email Address" autocomplete="off" autocapitalize="off" autocorrect="off" />
                <input type="password" class="field-input" name="password" id="password" placeholder="Password" autocomplete="off" />
                <button class="login-submit" type="submit"><i class="icon-go"></i></button>
            </form>
            <div class="response-message"></div>
            <a href="#" class="forgot-pass" title="Forgot Password?">Forgot Password?</a><span class="sep">&bull;</span><a href="/register" class="register" title="Register for free">Register Free</a>
        </div>
    </div>

    <div class="forgotPassWrapper">
        <div class="loginInner">
            <div class="login-ball"><img src="/assets/images/logo.png" /></div>
            <h1>Reset Password For</h1>
            <div class="login-text"><img src="/assets/images/icons/logo_text_light.png" /></div>
            <form id="unmarkForgotPass" method="post" action="/tools/forgotPassword">
                <input type="email" class="field-input" name="email" id="forgot_email" placeholder="Email Address" autocomplete="off" autocapitalize="off" autocorrect="off" />
                <button class="forgot-submit" type="submit"><i class="icon-go"></i></button>
            </form>
            <div class="response-message"></div>
            <a href="#" class="forgot-pass" title="Sign into your account">Need to Sign In?</a>
        </div>
    </div>

    <div class="unmark-spinner"></div>
    <div class="unmark-success"><i class="icon-check"></i></div>

    <div class="login-page-bottom">
        <a href="#" class="toggle_welcome" title="What is Unmark?"><span>What is Unmark?</span></a>
    </div>

</div>

<div id="unmark_about_page">
    <section id="top">
        <div class="logo">
            <img class="logo-mark" src="../assets/images/logo.png">
            <div class="logo-text icon-logo_text_light"></div>
        </div>
        <nav>
            <ul>
                <li><a href="#" class="toggle_welcome" title="Sign in or Sign Up">Sign In / Register <span class="icon-circle_arrow_down"></span></a></li>
                <li><a href="https://github.com/plainmade/unmark" title="Unmark on GitHub">Fork <i class="full-only">on GitHub</i> <span class="icon-github"></span></a></li>
            </ul>
        </nav>
    </section>
    <section id="bottom">
        <div class="content">
            <h2>Do something with your bookmarks</h2>
            <div class="demo">
                <div class="chrome">
                    <span class="dot"></span><span class="dot"></span><span class="dot"></span>
                </div>
                <img src="../assets/images/demo.gif">
            </div>
            <p>Unmark is designed to help you actually <em>do something</em> with your bookmarks, rather than just hoard them. A simple layout puts the focus on your task at hand and friendly reminders keep you in line. Filtering options let you find what you're looking for.</p>
            <p><strong>Register for free or grab the open source version from GitHub.</strong></p>

            <h2>Few, but key features</h2>
            <div class="feature-detail">
                <figure class="feature">
                    <img src="../../assets/images/feature_preview.jpg">
                    <figcaption>Preview Content Inline</figcaption>
                </figure>
                <figure class="feature">
                    <img src="../../assets/images/feature_notes.jpg">
                    <figcaption>Tag & Keep Notes</figcaption>
                </figure>
                <figure class="feature">
                    <img src="../../assets/images/feature_bookmarklet.jpg">
                    <figcaption>Save from Menu Bar</figcaption>
                </figure>
                <figure class="feature mobile-only">
                    <img src="../../assets/images/feature_chrome.jpg">
                    <figcaption>More Coming Soon</figcaption>
                </figure>
                <!--
                <figure class="feature">
                    <img src="http://placehold.it/344x300&text=.">
                    <figcaption>Fork on GitHub</figcaption>
                </figure>
                <figure class="feature">
                    <img src="http://placehold.it/344x300&text=.">
                    <figcaption>Chrome Extension</figcaption>
                </figure>
                <figure class="feature">
                    <img src="http://placehold.it/344x300&text=.">
                    <figcaption>Receive Suggestions</figcaption>
                </figure>
                -->
            </div>

            <h2>Use free or upgrade</h2>
            <section class="chart">
                <div class="table-column">
                    <ul>
                        <li>Filter <span class="full-only">saved bookmarks</span></li>
                        <li>Preview <span class="full-only">rich media</span></li>
                        <li>Add labels<span class="full-only">, notes & tags</span></li>
                        <li>Search <span class="full-only">saved bookmarks</span></li>
                        <li>Save <span class="full-only">bookmarks</span></li>
                        <li>Keep <span class="full-only">bookmarks</span></li>
                        <li>Price</li>
                    </ul>
                </div>
                <div class="table-column">
                    <ul>
                        <li><span class="yay icon-small_check"></span></li>
                        <li><span class="yay icon-small_check"></span></li>
                        <li><span class="yay icon-small_check"></span></li>
                        <li><span class="yay icon-small_x"></span></li>
                        <li>5 per day</li>
                        <li>50 total</li>
                        <li>Free</li>
                    </ul>
                </div>
                <div class="table-column">
                    <ul>
                        <li><span class="yay icon-small_check"></span></li>
                        <li><span class="yay icon-small_check"></span></li>
                        <li><span class="yay icon-small_check"></span></li>
                        <li><span class="nay icon-small_check"></span></li>
                        <li>Unlimited</li>
                        <li>Unlimited</li>
                        <li>$12/year</li>
                    </ul>
                </div>
            </section>
            <p>Use Unmark free for as long as you'd like or upgrade to a paid account at any time. An open source version is <a href="http://github.com/plainmade/unmark">available on GitHub</a>. Fork it and set up your own install.</p>
            <footer>
                <div class="links">
                    <a href="http://help.unmark.it/" title="Unmark Help Center">Help</a>
                    <a href="http://twitter.com/unmarkit" title="Follow @unmarkit on Twitter">@unmarkit</a>
                </div>
                <div class="byline">Made by <a href="http://plainmade.com" target="_blank" title="Plain is a small software company in Pennsylvania">Plain</a></div>
            </footer>
        </div>
    </section>
</div>

<?php $this->load->view('layouts/footer_unlogged_scripts'); ?>

</body>
</html>

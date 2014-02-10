<html>
<head>
    <title>Nilai : Mark Added</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/nilai.css" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
</head>
<body class="nilai-solo" id="nilai-login">

<div class="loginWrapper">
    <div class="loginInner">
        <div class="login-ball"></div>
        <h1>Sign In</h1>
        <form id="nilaiLogin" method="post" action="/login">
            <input type="email" class="field-input" name="email" id="email" placeholder="Email Address" autocomplete="off" autocapitalize="off" autocorrect="off" />
            <input type="password" class="field-input" name="password" id="password" placeholder="Password" autocomplete="off" />
            <button class="login-submit" type="submit"><i class="barley-icon-chevron-right"></i></button>
        </form>
        <div class="response-message"></div>
        <a href="#" class="forgot-pass">Forgot Password?</a>
    </div>
</div>

<div class="forgotPassWrapper">
    <div class="loginInner">
        <div class="login-ball"></div>
        <h1>Reset Password</h1>
        <form id="nilaiForgotPass" method="post" action="/tools/forgotPassword">
            <input type="email" class="field-input" name="email" id="forgot_email" placeholder="Email Address" autocomplete="off" autocapitalize="off" autocorrect="off" />
            <button class="forgot-submit" type="submit"><i class="barley-icon-chevron-right"></i></button>
        </form>
        <div class="response-message"></div>
        <a href="#" class="forgot-pass">Need to Sign In?</a>
    </div>
</div>

<div class="nilai-spinner"></div>
<div class="nilai-success"><i class="barley-icon-ok"></i></div>


<script type="text/javascript">
var nilai  = nilai || {};
nilai.vars = {};
nilai.vars.csrf_token   = '<?php print $csrf_token; ?>';       
</script>
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="/assets/js/nilai.js"></script>
<script src="/assets/js/nilai-login.js"></script>

</body>
</html>
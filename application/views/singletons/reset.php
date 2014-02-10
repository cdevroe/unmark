<html>
<head>
    <title>Nilai : Mark Added</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/nilai.css" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
</head>
<body class="nilai-solo" id="nilai-login">

<div class="resetWrapper">
    <div class="loginInner">
        <div class="login-ball"></div>
        <h1>Reset Password</h1>
        <form id="nilaiLogin" method="post" action="/login">
            <input type="password" class="field-input" name="password" id="password" placeholder="New Password..." autocomplete="off" />
            <input type="password2" class="field-input" name="password2" id="password2" placeholder="Once More..." autocomplete="off" />
            <button class="login-submit" type="submit"><i class="barley-icon-chevron-right"></i></button>
        </form>
        <div class="response-message"></div>
        <a href="#" class="forgot-pass">How did I get here?</a>
    </div>
</div>


<script type="text/javascript">
var nilai  = nilai || {};
nilai.vars = {};
nilai.vars.token   = '<?php print $token; ?>';       
</script>
<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="/assets/js/nilai.js"></script>
<script src="/assets/js/nilai-reset.js"></script>

</body>
</html>
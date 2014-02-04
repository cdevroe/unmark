<html>
<head>
    <title>Nilai : Mark Added</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/nilai.css" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
</head>
<body class="nilai-solo" id="nilai-login">

<div class="loginWrapper">

    <div class="login-ball"></div>

    <h1>Sign In</h1>

    <form method="post" action="/login">
        <input type="hidden" name="csrf_token" id="csrf_token" value="<?php print $csrf_token; ?>">
        <input type="email" class="field-input" name="email" id="email" placeholder="Email Address">
        <input type="password" class="field-input" name="password" id="password" placeholder="Password">
        <button type="submit"><i class="barley-icon-chevron-right"></i></button>
    </form>

    <a href="#" class="forgot-pass action" data-action="forgot_pass">Forgot Password?</a>
</div>

<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="/assets/js/nilai-login.js"></script>

</body>
</html>
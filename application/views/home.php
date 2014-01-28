<html>
<head>
    <title>Welcome to Nilai</title>
    <style type="text/css">
        .wrapper {
            width:500px;
            margin:0 auto;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <h1>Welcome to Nilai</h1>

        <h3>Please Sign In</h3>

        <form method="post" action="/login" class="form-inline">
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php print $csrf_token; ?>">
            <input type="text" class="input-small" name="email" id="email" placeholder="Email Address"> <br />
            <input type="password" class="input-small" name="password" id="password" placeholder="Password"> <br /><br />
            <input type="submit" value="Log in" name="login" id="login" class="btn">
        </form>

        <small>No Account? Too Bad... Signup Is Coming Soon</small>
    </div>

</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Eek, something is wrong</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/unmark.css">
    <link rel="icon" type="image/ico" href="/favicon.ico">
</head>
<body class="unmark-solo" id="unmark-login">
    <div id="error-wrapper">
        <div id="error-icon"><img src="/assets/images/icons/large_x.png" /></div>
        <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'production'): ?>
            <h1>An unexpected error has occured</h1>
            <p>The issue has been logged and will be taken care of shortly.</p>
        <?php else: ?>
            <h1><?php print $heading; ?></h1>
            <p><?php print $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

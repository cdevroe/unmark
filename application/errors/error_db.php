<!DOCTYPE html>
<html>
<head>
    <title>Eek, something is wrong</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo config_item('base_url') ?>assets/css/unmark.css">
    <link rel="icon" type="image/ico" href="<?php echo config_item('base_url') ?>favicon.ico">
</head>
<body class="unmark-solo" id="unmark-login">
	<div id="error-wrapper">
        <div id="error-icon"><img src="<?php echo config_item('base_url') ?>assets/images/icons/large_x.png" /></div>
		<h1><?php print $heading; ?></h1>
		<p><?php print $message; ?></p>
	</div>
</body>
</html>

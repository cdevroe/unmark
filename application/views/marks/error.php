<!DOCTYPE html>
<html>
<head>
	<title><?php echo _('Sorry, Could Not Add this Mark'); ?></title>
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="/assets/css/unmark.css">
	<link rel="icon" type="image/ico" href="/favicon.ico">
</head>
<body class="unmark-solo" id="unmark-login">
	<div id="error-wrapper">
		<div id="error-icon"><img src="/assets/images/icons/large_x.png" /></div>
		<h1>Yikes!</h1>
		<p><?php print current($errors); ?></p>
		<p><?php echo _('This mark could not be added to your stream.')?></p>
	</div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=50, initial-scale=1.0, user-scalable=no">
	<title>Nilai<?php if (empty($logged_in)) { echo ': Save your links for later.'; } ?></title>

	<link rel="stylesheet" href="/assets/bootstrap/compiled/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/nilai.css">

  <link rel="icon" type="image/ico" href="/favicon.ico">

  <script src="/assets/jquery/jquery-1.7.1.min.js"></script>
  <script src="/assets/jquery/jquery.scrollTo-1.4.2-min.js"></script>
  <script src="/assets/bootstrap/compiled/js/bootstrap.min.js"></script>
  <script src="/assets/js/nilai.js"></script>

  <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-29837394-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>
<body>
<div class="container-fluid">
<div class="row-fluid">
  <div class="logo">
    <h1><a href="/home"><img src="/assets/images/logo-60.png" alt="logo-60" width="60" height="60" /></a><?php if (empty($logged_in)) { ?> Nilai<?php } ?></h1>
  </div>

  <?php if (empty($logged_in)) { ?>
  <div class="navigation">
    <!--<ul class="nav nav-pills">
      <li><a class="btn btn-primary" href="/sirius" title="Create Account"><i class="icon-user"></i> Create Account</a></li>
    </ul> -->
    <form method="post" action="/login" class="form-inline">
      <input type="hidden" name="csrf_token" id="csrf_token" value="<?php print $csrf_token; ?>">
      <input type="text" class="input-small" name="email" id="email" placeholder="Email Address">
      <input type="password" class="input-small" name="password" id="password" placeholder="Password">
      <input type="submit" value="Log in" name="login" id="login" class="btn">
    </form>
    <?php if (isset($flash_message['message']) && ! empty($flash_message['message'])): ?>
      <?php print $flash_message['message']; ?>
    <?php endif; ?>
  </div>

  <?php } ?>

  <?php  if (isset($when)) { if (! empty($logged_in) && $when !='') { ?>
  <div class="navigation">
    <ul class="nav nav-pills">
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list-alt"></i> Sort <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li<?php if ($when == 'all') echo ' class="active"';?>>
          <a href="/marks">All</a>
        </li>
        <li<?php if ($when == 'today') echo ' class="active"';?>>
          <a href="/marks/today">Today</a>
        </li>
        <li<?php if ($when == 'yesterday') echo ' class="active"';?>>
          <a href="/marks/yesterday">Yesterday</a>
        </li>
        <li class="divider"></li>
        <li<?php if ($label == 'read') echo ' class="active"';?>>
          <a href="/marks/label/read">Read</a>
        </li>
        <li<?php if ($label == 'watch') echo ' class="active"';?>>
          <a href="/marks/label/watch">Watch</a>
        </li>
        <li<?php if ($label == 'listen') echo ' class="active"';?>>
          <a href="/marks/label/listen">Listen</a>
        </li>
        <li<?php if ($label == 'buy') echo ' class="active"';?>>
          <a href="/marks/label/buy">Buy</a>
        </li>
        <li<?php if ($label == 'eatdrink') echo ' class="active"';?>>
          <a href="/marks/label/eatdrink">Eat & Drink</a>
        </li>
        <li<?php if ($label == 'do') echo ' class="active"';?>>
          <a href="/marks/label/do">Do</a>
        </li>
        <li class="divider"></li>
        <li<?php if ($label == 'unlabeled') echo ' class="active"';?>>
          <a href="/marks/label/unlabeled">Unlabeled</a>
        </li>
        <li<?php if ($when == 'archive') echo ' class="active"';?>>
          <a href="/marks/archive">Archive</a>
        </li>
      </ul>
      </li>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list-alt"></i> Groups <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li<?php if ($group == 'create') echo ' class="active"';?>>
          <a href="/groups/create">Create a group +</a>
        </li>
        <?php if (isset($groups['belong']) && !empty($groups['belong'])) { ?>
        <li class="divider"></li>
        <?php //foreach($groups['belong'] as $gb) {  ?>
        <li<?php //if ($groups['groupuid'] == strtoupper($gb['uid'])) echo ' class="active"';?>>
          <a href="/groups/<?php //echo strtoupper($gb['uid']);?>"><?php //echo $gb['name'];?></a>
        </li>
        <?php //} ?>
        <?php } ?>
      </ul>
      </li>
      <li><a href="/logout"><i class="icon-off"></i> Out</a></li>
    </ul>
    <hr />
  </div>
  <?php } } ?>
</div>
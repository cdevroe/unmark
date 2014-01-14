<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=50, initial-scale=1.0, user-scalable=no">
	<title>Nilai<?php if (!$this->session->userdata('userid')) { echo ': Save your links for later.'; } ?></title>

	<link rel="stylesheet" href="<?php echo site_url();?>assets/bootstrap/compiled/css/bootstrap.min.css" />
  <link rel="stylesheet" href="<?php echo site_url();?>assets/css/nilai.css" />

  <link rel="icon" type="image/ico" href="<?php echo site_url();?>favicon.ico" />

  <script src="<?php echo site_url();?>assets/jquery/jquery-1.7.1.min.js"></script>
  <script src="<?php echo site_url();?>assets/jquery/jquery.scrollTo-1.4.2-min.js"></script>
  <script src="<?php echo site_url();?>assets/bootstrap/compiled/js/bootstrap.min.js"></script>
  <script src="<?php echo site_url();?>assets/js/nilai.js"></script>

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
    <h1><a href="/home"><img src="<?php echo site_url();?>assets/images/logo-60.png" alt="logo-60" width="60" height="60" /></a><?php if (!$this->session->userdata('userid')) { ?> Nilai<?php } ?></h1>
  </div>

  <?php if (!$this->session->userdata('userid')) { ?>
  <div class="navigation">
    <!--<ul class="nav nav-pills">
      <li><a class="btn btn-primary" href="/sirius" title="Create Account"><i class="icon-user"></i> Create Account</a></li>
    </ul> -->
    <form method="post" action="users/login" class="form-inline">
      <?php echo form_input('emailaddress','','class="input-small" placeholder="Email Address"');?>
      <?php echo form_password('password','','class="input-small" placeholder="Password"');?>
      <input type="submit" value="Log in" name="login" id="login" class="btn" />
    </form>
  </div>

  <?php } ?>

  <?php  if (isset($when)) { if ($this->session->userdata('userid') && $when !='') { ?>
  <div class="navigation">
    <ul class="nav nav-pills">
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list-alt"></i> Sort <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li<?php if ($when == 'all') echo ' class="active"';?>>
          <a href="/home">All</a>
        </li>
        <li<?php if ($when == 'today') echo ' class="active"';?>>
          <a href="/home/today">Today</a>
        </li>
        <li<?php if ($when == 'yesterday') echo ' class="active"';?>>
          <a href="/home/yesterday">Yesterday</a>
        </li>
        <li class="divider"></li>
        <li<?php if ($label == 'read') echo ' class="active"';?>>
          <a href="/home/label/read">Read</a>
        </li>
        <li<?php if ($label == 'watch') echo ' class="active"';?>>
          <a href="/home/label/watch">Watch</a>
        </li>
        <li<?php if ($label == 'listen') echo ' class="active"';?>>
          <a href="/home/label/listen">Listen</a>
        </li>
        <li<?php if ($label == 'buy') echo ' class="active"';?>>
          <a href="/home/label/buy">Buy</a>
        </li>
        <li<?php if ($label == 'eatdrink') echo ' class="active"';?>>
          <a href="/home/label/eatdrink">Eat & Drink</a>
        </li>
        <li<?php if ($label == 'do') echo ' class="active"';?>>
          <a href="/home/label/do">Do</a>
        </li>
        <li class="divider"></li>
        <li<?php if ($label == 'unlabeled') echo ' class="active"';?>>
          <a href="/home/label/unlabeled">Unlabeled</a>
        </li>
        <li<?php if ($when == 'archive') echo ' class="active"';?>>
          <a href="/home/archive">Archive</a>
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
        <?php foreach($groups['belong'] as $gb) {  ?>
        <li<?php if ($group['groupuid'] == strtoupper($gb['uid'])) echo ' class="active"';?>>
          <a href="/groups/<?php echo strtoupper($gb['uid']);?>"><?php echo $gb['name'];?></a>
        </li>
        <?php }
        } ?>
      </ul>
      </li>
      <li><a href="/users/logout"><i class="icon-off"></i> Out</a></li>
    </ul>
    <hr />
  </div>
  <?php } } ?>
</div>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=50, initial-scale=1.0, user-scalable=no">
    <title>Nilai<?php if (! isset($_SESSION['logged_in'])) { echo ': Save your links for later.'; } ?></title>
    <link rel="stylesheet" href="/assets/css/nilai.css" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
    <script src="/assets/jquery/jquery-1.7.1.min.js"></script>
    <script src="/assets/jquery/jquery.scrollTo-1.4.2-min.js"></script>
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

    <?php include('navigation.php'); ?>
    <?php include('timeline.php'); ?>

    <div class="main-wrapper">
        <?php $this->load->view($yield); ?>
        <div class="sidebar-content"><?php include('sidebar.php'); ?></div>
    </div> <!-- end main-wrapper -->
</body>
</html>
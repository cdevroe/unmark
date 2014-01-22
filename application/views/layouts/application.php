<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=50, initial-scale=1.0, user-scalable=no">
    <title>Nilai<?php if (! isset($_SESSION['logged_in'])) { echo ': Save your links for later.'; } ?></title>
    <link rel="stylesheet" href="/assets/css/nilai.css" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
</head>
<body>

    <?php include('navigation.php'); ?>
    <?php include('timeline.php'); ?>

    <div class="main-wrapper">
        <?php $this->load->view($yield); ?>
        <div class="sidebar-content"><?php include('sidebar.php'); ?></div>
    </div> <!-- end main-wrapper -->


<script src="/assets/jquery/jquery-1.7.1.min.js"></script>
<script src="/assets/jquery/jquery.scrollTo-1.4.2-min.js"></script>
<script src="/assets/js/nilai.js"></script>
    
</body>
</html>
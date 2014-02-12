<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=50, initial-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>Nilai<?php if (! isset($_SESSION['logged_in'])) { echo ': Save your links for later.'; } ?></title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/nilai.css" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
</head>

<?php if (isset($active_label)) : ?>
<body id="nilai" class="label-<?php print $active_label['label_id']; ?>" data-lookup="<?php print $lookup_type; ?>">
<?php else : ?>
<body id="nilai" data-lookup="<?php print $lookup_type; ?>">
<?php endif; ?>

    <?php $this->load->view('layouts/navigation'); ?>

    <div class="main-wrapper">
        <div class="inner-wrapper">
            <div class="main-content">
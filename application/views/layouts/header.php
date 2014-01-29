<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=50, initial-scale=1.0, user-scalable=no">
    <title>Nilai<?php if (! isset($_SESSION['logged_in'])) { echo ': Save your links for later.'; } ?></title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/nilai.css" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
</head>

<body>

<?php 
    global $active_label;
    $active_label = 0;
    if (isset($labels)){
        foreach ($labels as $label) {
            if (isset($label->current)) {
                if ($label->current == 1) {
                    $active_label = $label->label_id;
                }
            }
        }
    }
    print "<body class='label-" . $active_label . "'>";
?>

    <?php include('navigation.php'); ?>

    <div class="main-wrapper">
        <div class="inner-wrapper">
            <div class="main-content">
<html>
<head>
    <title><?php echo unmark_phrase('Unmark : Importer'); ?></title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="assets/css/unmark.css" />
    <link rel="icon" type="image/ico" href="favicon.ico" />
</head>
<body class="unmark-solo" id="unmark-login">
<div id="unmark_login_page">
    <div id="unmark-setup">
        <?php
            if ($success === false) {
                print '<p>' . unmark_phrase('Sorry, there was an error. Please <a href=\'/\'>go back</a> and try again.').'</p>';
                if (isset($errors) ) {
                    foreach($errors as $prop) {
                        $first = $prop;
                        break;
                    }
                    print '<p>'. sprintf(unmark_phrase('ERROR: %s'), $first) . '</p>';
                }
            } else {
                print '<p>' . unmark_phrase('Successfull Upload!') . '</p>';
                print '<p>' . sprintf(unmark_phrase('%s marks added', '%s marks added', $result['added']), $result['added']) . '</p>';
                print '<p>' . sprintf(unmark_phrase('%s marks skipped', '%s marks skipped', $result['skipped']), $result['skipped']) . '</p>';
                print '<p>' . sprintf(unmark_phrase('%s marks failed', '%s marks failed', $result['failed']), $result['failed']) . '</p>';
                print '<p>' . sprintf(unmark_phrase('%s marks total', '%s marks total', $result['total']), $result['total']) . '</p>';
                print '<p>' . unmark_phrase('Head <a href=\'/\'>back</a> to see them now!') . '</p>';
            }
        ?>
    </div>
</div>

<script src="assets/libraries/jquery/jquery-2.1.0.min.js"></script>
<script src="assets/js/production/unmark.loggedout.js"></script>

</body>
</html>

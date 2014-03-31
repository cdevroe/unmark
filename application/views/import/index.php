<html>
<head>
    <title><?php echo _('Unmark : Importer'); ?></title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/unmark.css" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
</head>
<body class="unmark-solo" id="unmark-login">

    <div id="unmark-setup">
        <?php
            if ($success === false) {
                print '<p>' . _('Sorry, there was an error. Please <a href=\'/\'>go back</a> and try again.').'</p>';
                if (isset($errors) ) {
                    foreach($errors as $prop) {
                        $first = $prop;
                        break;
                    } 
                    print '<p>'. sprintf(_('ERROR: %s'), $first) . '</p>';
                }
            } else {
                print '<p>' . _('Successfull Upload!') . '</p>';
                print '<p>' . sprintf(ngettext('%s mark added', '%s marks added', $result['added']), $result['added']) . '</p>';
                print '<p>' . sprintf(ngettext('%s mark skipped', '%s marks skipped', $result['skipped']), $result['skipped']) . '</p>';
                print '<p>' . sprintf(ngettext('%s mark failed', '%s marks failed', $result['failed']), $result['failed']) . '</p>';
                print '<p>' . sprintf(ngettext('%s mark total', '%s marks total', $result['total']), $result['total']) . '</p>';
                print '<p>' . _('Head <a href=\'/\'>back</a> to see them now!') . '</p>';
            }
        ?>
    </div>

<script src="/assets/libraries/jquery/jquery-2.1.0.min.js"></script>
<script src="/assets/js/production/unmark.loggedout.js"></script>

</body>
</html>
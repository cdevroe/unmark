<html>
<head>
    <title><?php echo unmark_phrase('Unmark : Importer'); ?></title>
    <link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,600,600i,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/unmark.css" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
</head>
<body class="unmark-solo" id="unmark-login">
<div id="unmark_login_page">
    <div id="unmark-setup">
      <figure>
        <?php
            if ($success === false) {
                print '<header><h1>' . unmark_phrase('Sorry, there was an error.').'</h1></header>';
                print '<p><span>' . unmark_phrase('Please make sure you have a valid export.json file.') . '</span></p>';
                print unmark_phrase('<a href=\'/\' class="back-button"> Go back & try again</a>');
                if (isset($errors) ) {
                    foreach($errors as $prop) {
                        $first = $prop;
                        break;
                    }
                    print '<p>'. sprintf(unmark_phrase('ERROR: %s'), $first) . '</p>';
                }
            } else {
                print '<header><h1>' . unmark_phrase('Successful Upload!') . '</h1></header>';
                print '<p>' . sprintf(unmark_phrase('%s marks added', '%s marks added', $result['added']), $result['added']) . '</p>';
                print '<p>' . sprintf(unmark_phrase('%s marks skipped', '%s marks skipped', $result['skipped']), $result['skipped']) . '</p>';
                print '<p>' . sprintf(unmark_phrase('%s marks failed', '%s marks failed', $result['failed']), $result['failed']) . '</p>';
                print '<p>' . sprintf(unmark_phrase('%s marks total', '%s marks total', $result['total']), $result['total']) . '</p>';
                print unmark_phrase('<a href=\'/\' class="back-button"> Go back & see them</a>');
            }
        ?>
      </figure>
    </div>
</div>

<script src="/assets/libraries/jquery/jquery-2.1.0.min.js"></script>
<script src="/assets/js/production/unmark.loggedout.js"></script>

</body>
</html>

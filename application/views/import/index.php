<html>
<head>
    <title>Unmark Importer</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400|Merriweather' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/assets/css/unmark.css" />
    <link rel="icon" type="image/ico" href="/favicon.ico" />
</head>
<body class="unmark-solo" id="unmark-login">

    <div id="unmark-setup">
        <?php
            if ($success === false) {
                if (isset($errors) ) {
                    foreach($errors as $prop) {
                        $first = $prop;
                        break;
                    } 
                    print "<p>Sorry, there was an error. Please <a href='/''>go back</a> and try again</p>" . "<p>ERROR: " . $first . "</p>";
                } else{
                    print "<p>Sorry, there was an error. Please <a href='/''>go back</a> and try again</p>";
                }
            } else {
                print "<p>Successfull Upload!</p>";
                print "<p>" . $result['added'] . " marks added</p>";
                print "<p>" . $result['skipped'] . " marks skipped</p>";
                print "<p>" . $result['failed'] . " marks failed</p>";
                print "<p>" . $result['total'] . " marks total</p>";
                print "<p>Head <a href='/''>back</a> to see them now!</p>";
            }
        ?>
    </div>

<script src="/assets/libraries/jquery/jquery-2.1.0.min.js"></script>
<script src="/assets/js/production/unmark.loggedout.js"></script>

</body>
</html>
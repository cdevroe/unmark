<?php
$url = '';
if ( $_POST ) :
    $time_start = microtime(true);


    $url = filter_var($_POST['url'], FILTER_VALIDATE_URL);

    if ($url === false) {
        $url = '';
        die('The URL submitted was invalid.');
    }


    echo '<p><strong>URL:</strong>' . htmlspecialchars($url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>';

    $title = '';
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    if ($dom->loadHTMLFile($_POST['url'])) {
        $list = $dom->getElementsByTagName("title");
        if ($list->length > 0) {
            $title = $list->item(0)->textContent;
        }
    }
    $time_end = microtime(true);
    if ( strlen($title) > 0 ) : echo '<p><strong>Title:</strong> ' . $title . '</p>'; endif;
    $execution_time = ($time_end - $time_start);
    echo '<p><b>Total Execution Time:</b> '.$execution_time.' seconds</p>';

endif; ?>

<form method="post" action="/marks/add">
<input type="hidden" name="add_from_url" value="1">
<input type="text" name="url" value="<?=$url;?>" placeholder="http://">
<input type="submit" value="Get">
</form>


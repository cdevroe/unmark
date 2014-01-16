<?php
$content_type = (! isset($content_type) || empty($content_type)) ? 'application/json' : $content_type;
header('Content-Type: ' . $content_type);
print (isset($json) && ! empty($json)) ? $json : '{}';
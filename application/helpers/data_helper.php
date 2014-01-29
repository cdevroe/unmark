<?php defined("BASEPATH") or exit("No direct script access allowed");

function decodeValue($str)
{
    return (is_string($str) && ! empty($str)) ? stripslashes(html_entity_decode(rawurldecode(trim($str)), ENT_QUOTES, 'UTF-8')) : $str;
}

function findPage()
{
    $uri  = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';
    $uri  = explode('?', $uri);
    $uri  = explode('/', $uri[0]);
    $uri  = end($uri);
    return (! is_numeric($uri) || empty($uri) || isValid($uri, 'date') === true || isValid($uri, 'year')) ? 1 : $uri;
}

function findStartFinish($start, $finish)
{
    $start   = trim(urldecode($start));
    $start   = (isValid($start, 'date') === false && isValid($start, 'year') === false) ? preg_replace('/\b\-\b/', ' ', $start) : $start;
    $finish  = trim(urldecode($finish));
    $finish  = (isValid($finish, 'date') === false && isValid($finish, 'year') === false) ? preg_replace('/\b\-\b/', ' ', $finish) : $finish;


    // check for single year
    if (isValid($start, 'year') === true && isValid($finish, 'year') !== true) {
        $finish = $start . '-12-31';
        $start  = $start . '-01-01';
    }
    // Check for both ranges being years
    elseif (isValid($start, 'year') === true && isValid($finish, 'year') === true) {
        $finish = ($finish > $start) ? ($finish - 1) . '-12-31' : $finish . '-12-31';
        $start  = $start . '-01-01';
    }
    // Check for start as date, nothing as finish
    // Set finish to start
    // Single lookup
    elseif (isValid($start, 'date') === true && isValid($finish, 'date') !== true) {
        $finish = $start;
    }

    /*print strtotime($start) . "<BR>";
    print date('Y-m-d', strtotime($start)) . "<BR>";
    print $finish . "<BR>";
    print strtotime($finish) . "<BR>";
    print date('Y-m-d', strtotime($finish)) . "<BR>";*/

    // Figure start/finish timestamps
    // If empty, assign to today
    $start  = strtotime($start);
    $start  = (empty($start)) ? strtotime('today') : $start;
    $finish = strtotime($finish);
    $finish = (empty($finish)) ? $start : $finish;

    // Fix ordering if need be
    if ($start > $finish) {
        $s      = $start;
        $finish = $start;
        $start  = $s;
    }

    // Return
    return array('start' => $start, 'finish' => $finish);
}

// Format any errors coming back to standardize them
function formatErrors($errors, $errno=0)
{
    if (is_string($errors)) {
        $message        = $errors;
        $errors         = array();
        $errors[$errno] = $message;
    }
    return $errors;
}

function generateSlug($str)
{
    $str  = strip_tags(html_entity_decode($str, ENT_QUOTES, 'UTF-8'));
    $slug = strtolower(trim(str_replace('--', '-', preg_replace('/\W+/','-', $str)), '-'));
    return (empty($slug)) ? false : $slug;
}

function generateTimeSpan($date)
{
    $timestamp  = strtotime($date);
    $difference = time() - $timestamp;
    $dividers   = array(
        'year'   => 31536000,
        'month'  => 2628000,
        'week'   => 604800,
        'day'    => 86400,
        'hour'   => 3600,
        'minute' => 60
    );

    $results = array();
    if (empty($timestamp) || $difference < 0) {
        $results['second'] = 1;
    }
    elseif ($difference < 60) {
        $results['second'] = $difference;
    }
    else {
        foreach ($dividers as $type => $seconds) {
            $results[$type] = round($difference / $seconds, 0);
        }
    }

    foreach ($results as $type => $number) {
        if (! empty($number)) {
            $s = ($number == '1') ? '' : 's';
            return $number . ' ' . $type . $s . ' ago';
        }
    }

    return 'Just Now';
}

function getLastJsonError()
{
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            $e = false;
        break;
        case JSON_ERROR_DEPTH:
            $e = 'Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            $e = 'Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            $e = 'Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            $e = 'Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            $e = 'Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            $e = 'Unknown error';
        break;
    }
    return $e;
}

function getSmartLabelInfo($url)
{
    $url    = strtolower($url);
    $scheme = parse_url($url, PHP_URL_SCHEME);
    $url    = (empty($scheme)) ? 'http://' . $url : $url;
    $parse  = parse_url($url);
    $domain = (isset($parse['host']) && ! empty($parse['host'])) ? $parse['host'] : $url;
    $path   = (isset($parse['path']) && ! empty($parse['path'])) ? $parse['path'] : '';
    $path   = (substr($path, strlen($path) - 1) == '/') ? substr($path, 0, strlen($path) - 1) : $path;
    $path   = ($path == '/') ? '' : $path;

    return array(
        'domain' => $domain,
        'path'   => $path,
        'key'    => md5(str_replace('www.', '', $domain))
    );

}

function getTagsFromHash($str)
{
    preg_match_all('/#([0-9a-z_-]*)/is', $str, $tags);
    return (isset($tags[1]) && ! empty($tags[1])) ? $tags[1] : array();
}

function purifyHTML($str, $exceptions=array())
{
    if (is_string($str)) {
        $find   = array("\n", "\r");
        $tags   = array(
            'alert', 'applet', 'audio', 'basefont', 'base', 'behavior', 'bgsound', 'blink', 'body',
            'embed', 'expression', 'form', 'iframe', 'ilayer', 'input', 'isindex', 'layer', 'font',
            'html', 'link', 'style', 'body', 'head', 'xml', 'script', 'link', 'meta', 'object', 'plaintext',
            'textarea', 'title', 'video', 'xss'
        );

        $tags   = array_diff($tags, $exceptions);
        $str    = str_replace($find, '<br>', decodeValue($str));
        $str    = stripTags($str, $tags);
        $str    = stripPHP($str);
    }
    return $str;
}

function readEasy($str)
{
    $find    = array('/', '-', '_');
    $replace = array('', ' ', ' ');
    return ucwords(strtolower(str_replace($find, $replace, $str)));
}

function readyDomain($domain)
{
    return str_replace(array('http://', 'https://'), '', strtolower($domain));
}

function standardizePath($url)
{
    $url        = trim($url);
    $len        = strlen($url);
    $first_char = substr($url, 0, 1);
    $last_char  = substr($url, $len - 1, 1);

    if ($len < 1) {
        return '/';
    }
    elseif ($len == 1 && $first_char == '/') {
        return $url;
    }
    else {
        $url = (substr($url, 0, 1) != '/') ? '/' . $url : $url;
        return (substr($url, strlen($url) - 1, 1) == '/') ? substr($url, 0, strlen($url) - 1) : $url;
    }
}

function stripPHP($str)
{
    return (is_string($str)) ? preg_replace('/<\\?.*(\\?>|$)/Us', '', decodeValue($str)) : $str;
}

function stripTags($str, $tags)
{
    if (is_string($str)) {
        $whitelist  = array('iframe');
        $tags       = (! is_array($tags)) ? array($tags) : $tags;
        $str        = decodeValue($str);
        foreach ($tags as $tag) {
            preg_match_all('/<' . $tag . '.*?(src=("|\')(.*?)\\2.*?)?\/?>((.*?)<\/' . $tag . '>)?/ism', $str, $m);
            if (isset($m[0]) && ! empty($m[0])) {
                foreach ($m[0] as $k => $v) {
                    // 0 = complete
                    // 3 = src url if applicable
                    // 5 = innerHTML if applicable
                    $e_tag = $tag;
                    $keep  = false;
                    if (in_array($tag, $whitelist)) {
                        // Figure URL
                        $url = $m[3][$k];
                        if (! empty($url)) {
                            $e_tag = $e_tag . ' with src of: ' . $url;
                            $keep  = isHostApproved($url);
                        }
                    }

                    // If not a keeper, replace with HTML comment
                    if ($keep === false) {
                        $str = str_replace($m[0][$k], '<!-- tag (' . $e_tag . ') not allowed -->', $str);
                    }
                }
            }
        }
    }
    return $str;
}
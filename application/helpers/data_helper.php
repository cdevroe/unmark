<?php defined("BASEPATH") or exit("No direct script access allowed");

function decodeValue($str)
{
    return (is_string($str) && ! empty($str)) ? stripslashes(html_entity_decode(rawurldecode(trim($str)), ENT_QUOTES, 'UTF-8')) : $str;
}

function formatDomain($domain)
{
    return str_replace('www.', '', strtolower($domain));
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

function formatPath($path='/')
{
    $path = (substr($path, 0, 1) != '/') ? '/' . $path : $path;
    return ($path == '/') ? '' : $path;
}

function generateSlug($str)
{
    $str  = strip_tags(html_entity_decode($str, ENT_QUOTES, 'UTF-8'));
    $slug = strtolower(trim(str_replace('--', '-', preg_replace('/\W+/','-', $str)), '-'));
    return (empty($slug)) ? false : $slug;
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
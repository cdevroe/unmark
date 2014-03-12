<?php defined("BASEPATH") or exit("No direct script access allowed");

// Checks the count and makes the string plural (well adds an s) if it's longer than 1 or 0.
function determinePlurality($num, $str, $show_num=true)
{
    if ($num == 0) { return "No ". $str . "s"; }
    if ($show_num) {
        return ($num > 1) ? $num . " " . $str . "s" : $num . " " . $str;
    } else {
        return ($num > 1) ? $str . "s" : $str;
    }

}


// Checks current date with passed date and responsds with proper verbiage.
function formatExpires($date)
{
    $timestamp  = strtotime($date);
    if (time() > $timestamp) {
        return "per Year"; // Expired
    } else {
        return "on " . date('m/d/Y', $timestamp); // Not Yet Expired
    }
}

// Takes a URL and returns a pretty looking version for view
function niceUrl($url)
{
    return rtrim(preg_replace('/https?:\/\/(www.)?/', '', $url), '/');
}


// Get URL For Bookmarklet
function getFullUrl()
{
    return @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
}

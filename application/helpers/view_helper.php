<?php defined("BASEPATH") or exit("No direct script access allowed");

// Prints marks count in nice format
function printMarksCount($num)
{
    if($num > 0) {
        return $num;
    } else {
        return '0';
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

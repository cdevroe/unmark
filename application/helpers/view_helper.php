<?php defined("BASEPATH") or exit("No direct script access allowed");

function determinePlurality($num, $str)
{
    if ($num == 0) { return "No ". $str . "s"; }
    return ($num > 1) ? $num . " " . $str . "s" : $num . " " . $str;
}

function formatExpires($date) // Checks current date with passed date and responsds with proper verbiage.
{
    $timestamp  = strtotime($date);
    if (time() > $timestamp) {
        return "per Year"; // Expired
    } else {
        return "on " . date('m/d/Y', $timestamp); // Not Yet Expired
    }
}
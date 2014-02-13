<?php defined("BASEPATH") or exit("No direct script access allowed");

function determinePlurality($num, $str)
{
    if ($num == 0) { return "No ". $str . "s"; }
    return ($num > 1) ? $num . " " . $str . "s" : $num . " " . $str;
}
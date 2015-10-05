<?php defined("BASEPATH") or exit("No direct script access allowed");

function isValid($val, $type, $extra_options = null)
{
    //Types: email, password
    if ($type == 'email') {
        return (preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $val)) ? true : false;
    }
    // Starting 1.6.5 : Minimum 6 chars
    // OLD: At least one upper and one number, min 6 characters (deprecated)
    elseif ($type == 'password') {
        return (strlen($val) >= 6) ? true : false;
        //disabled 1.6.5 return (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $val) && strlen($val) >= 6) ? true : false;
    }
    elseif ($type == 'numeric') {
        return (is_numeric($val) && $val >= 0) ? true : false;
    }
    elseif ($type == 'bool') {
        return ($val === true || $val === false || $val == '0' || $val == '1') ? true : false;
    }
    elseif ($type == 'string') {
        return (is_string($val)) ? true : false;
    }
    elseif ($type == 'datetime') {
        return (preg_match('/([0-9]{4}-[0-9]{2}-[0-9]{2})\s([0-9]{2}:[0-9]{2}:[0-9]{2})/', $val)) ? true : false;
    }
    elseif ($type == 'date') {
        return (preg_match('/([0-9]{4}-[0-9]{2}-[0-9]{2}|[0-9]{2}-[0-9]{2}-[0-9]{4})/', $val)) ? true : false;
    }
    elseif ($type == 'year') {
        return (preg_match('/([0-9]{4})/', $val)) ? true : false;
    }
    elseif ($type == 'time') {
        return (preg_match('/([0-9]{2}:[0-9]{2}:[0-9]{2})/', $val)) ? true : false;
    }
    elseif ($type == 'domain') {
        return (preg_match('/[A-Z0-9._-]+\.[A-Z]{2,}/i', $val) || $val == '*') ? true : false;
    }
    elseif ($type == 'subdomain') {
        return (preg_match('/^[A-Z0-9_-]{3,}$/i', $val)) ? true : false;
    }
    elseif ($type == 'json') {
        $tmp   = json_encode($val);
        return (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
    elseif ($type == 'variable') {
        // PHP variables
        // Must start with letter or underscore
        // Can only contain letters, underscores and/or numbers
        return (preg_match('/^[a-z_\x7f-\xff]([a-z0-9_\x7f-\xff])*$/i', $val)) ? true : false;
    }
    elseif ($type == 'slug') {
        // Slugs can only contain letters, numbers and dashes
        return (preg_match('/^[a-z0-9\-\/]+$/i', $val)) ? true : false;
    }
    elseif ($type == 'md5') {
        // just check the string length for MD5
        return (strlen($val) == 32) ? true : false;
    }
    elseif ($type == 'url') {
        // Use parse_url
        // If it has a scheme and a host, it's good to go
        $failed_schemes = array('chrome');
        if(!empty($extra_options) && !empty($extra_options['failed_schemes']) && is_array($extra_options['failed_schemes'])){
          $failed_schemes = array_merge($failed_schemes, $extra_options['failed_schemes']);  
        }
        $url            = parse_url($val);
        return (isset($url['scheme']) && ! empty($url['scheme']) && ! in_array($url['scheme'], $failed_schemes) && isset($url['host']) && ! empty($url['host'])) ? true : false;
    }
    return false;
}

function validate($options=array(), $data_types=array(), $required=array())
{
    $meets_reqs = true;
    $errors     = array();

    //Check required fields
    if (! empty($required)) {
        foreach ($required as $column) {
            if (! isset($options[$column]) || $options[$column] == '') {
                $errors[$column] = ucwords(strtolower(str_replace('_', ' ', $column))) . ' is required.';
            }
        }
    }

    // Check data type requirements
    if (! empty($data_types)) {
        foreach ($data_types as $column => $type) {
            if (isset($options[$column]) && ! isValid($options[$column], $type)) {
                $errors[$column] = ucwords(strtolower(str_replace('_', ' ', $column))) . ' is not valid.';
            }
        }
    }

    return (! empty($errors)) ? array(601 => $errors) : true;
}
<?php defined("BASEPATH") or exit("No direct script access allowed");

function generateCSRF()
{
    return generateToken(50);
}

function generateHash($str)
{
    $salt = generateToken(50);

    if (CRYPT_SHA512 == 1) {
        return crypt($str, '$6$rounds=5000$' . $salt . '$');
    }

    if (CRYPT_SHA256 == 1) {
        return crypt($str, '$5$rounds=5000$' . $salt . '$');
    }

    if (CRYPT_BLOWFISH == 1) {
        return crypt($str, '$2a$07$' . $salt . '$');
    }

    if (CRYPT_MD5 == 1) {
        return crypt($str, '$1$' . $salt . '$');
    }

    if (CRYPT_EXT_DES == 1) {
        return crypt($str, '_J9' . $salt);
    }

    if (CRYPT_STD_DES == 1) {
        return crypt($str, $salt);
    }

    return false;
    // Throw exception once everything is hooked up
}

function generatePassword($len=12)
{
    $password    = null;
    $chars       = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $i        =    1;

    // get a new password
    do {
        $password  .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    } while(strlen($password) < $len);


    if (isStrong($password) === false) {
        do {
            $password = generatePassword();
        } while (isStrong($password) === false);
    }

    return $password;
}

function generateToken($len=20)
{
    $token  = NULL;
    $chars  = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $i      = 1;

    while ($i <= $len) {
        $token  .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        $i      += 1;
    }

    return $token;
}

function isStrong($str)
{
    $numeric  = false;
    $lower    = false;
    $upper    = false;
    for ($i = 0; $i < strlen($str); $i++) {
        $ord      = ord(substr($str, $i, 1));
        $numeric  = ($ord >= 48 && $ord <= 57) ? true : $numeric;
        $lower    = ($ord >= 65 && $ord <= 90) ? true : $lower;
        $upper    = ($ord >= 97 && $ord <= 122) ? true : $upper;
    }

    return ($numeric === true && $lower === true && $upper === true) ? true : false;
}

function verifyHash($str, $hash)
{
    return (crypt($str, $hash) == $hash) ? true : false;
}
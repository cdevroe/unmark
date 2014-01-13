<?php defined("BASEPATH") or exit("No direct script access allowed");

function generateCSRF()
{
    return generateToken(50);
}

function generateHash($str)
{
    $token  = generateToken(50);
    $hashes = array(
        CRYPT_SHA512   => '$6$rounds=5000$' . $token . '$',
        CRYPT_SHA256   => '$5$rounds=5000$' . $token . '$',
        CRYPT_BLOWFISH => '$2a$07$' . $token . '$',
        CRYPT_MD5      => '$1$' . $token . '$',
        CRYPT_EXT_DES  => '_J9' . $token,
        CRYPT_STD_DES  => $token
    );

    foreach ($hashes as $hash => $salt) {
        if ($hash == 1) {
            return crypt($str, $salt);
        }
    }

    return false;
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

function isStrong($password)
{
    $numeric  = false;
    $lower    = false;
    $upper    = false;
    for ($i = 0; $i < strlen($password); $i++) {
        $ord      = ord(substr($password, $i, 1));
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
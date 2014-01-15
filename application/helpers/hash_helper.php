<?php defined("BASEPATH") or exit("No direct script access allowed");

function generateCSRF()
{
    return generateToken(50);
}

function generateHash($str, $salt=null)
{
    $salt = (empty($salt)) ? generateToken(50) : $salt;

    if (CRYPT_SHA512 == 1) {
        $crypt = crypt($str, '$6$rounds=5000$' . $salt . '$');
    }
    elseif (CRYPT_SHA256 == 1) {
        $crypt = crypt($str, '$5$rounds=5000$' . $salt . '$');
    }
    elseif (CRYPT_BLOWFISH == 1) {
        $crypt = crypt($str, '$2a$07$' . $salt . '$');
    }
    elseif(CRYPT_MD5 == 1) {
        $crypt = crypt($str, '$1$' . $salt . '$');
    }
    elseif(CRYPT_EXT_DES == 1) {
        $crypt = crypt($str, '_J9' . $salt);
    }
    elseif(CRYPT_STD_DES == 1) {
        $crypt = crypt($str, $salt);
    }

    if (! isset($crypt)) {
        return false;
        // Throw exception once everything is hooked up
    }

    return array('salt' => $salt, 'encrypted' => $crypt);
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
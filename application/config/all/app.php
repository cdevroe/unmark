<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 - Array of titles & urls to add to all newly registered accounts

 These will be added to every new account so when they register they
 have some links to *do* something with. The label of 'Do' will be added automatically.
*/
$config['new_account_links'] = array(
    'Read Unmark\'s FAQ' => array(
        'url'      => 'http://help.unmark.it/faq',
        'label_id' => '7'
    ),
    'How To Use Unmark'  => array(
        'url'      => 'http://help.unmark.it',
        'label_id' => '7'
    )
);

/*
 * Validity of password recovery token
 */

$config['forgot_password_token_valid_seconds'] = 60 * 60 * 24; // 24 hours

/*
 * Password reset URL
 */

$config['forgot_password_recovery_url'] = '{URL_BASE}password_reset/{TOKEN}';


/*
 * Email settings
 */

// Reset email subject
$config['password_reset_email_subject'] = 'Unmark - Password reset';
// Reset email from field address and description
$config['email_from'] = array('address'=>'noreply@unmark.it', 'description' => 'Unmark');
// Reset email reply to field address and description
$config['email_reply_to'] = array('address'=>'noreply@unmark.it', 'description' => 'Unmark');
// Reset email server settings
/*
 * Array with settings accepted by CI email component
 * Example settings for gmail:

$config['plain_email_settings'] = array(
                	'protocol' => 'smtp',
                    'smtp_host' => 'ssl://smtp.googlemail.com',
                    'smtp_user' => 'user',
                    'smtp_pass' => 'pass',
                    'smtp_port' => 465,
                    'smtp_timeout' => 5,
                    'newline' => "\r\n",
                    'charset'   => 'UTF-8',
                    'mailtype'  => 'html',
                );
*/
$config['plain_email_settings'] = array('charset' => 'UTF-8', 'mailtype' => 'html', 'newline' => "\r\n");
// Updated password email subject
$config['password_updated_email_subject'] = 'Unmark - Password updated';


// Embedly API Token
$config['embedly_api_key'] = '';

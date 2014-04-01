<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 - Define application errors and error codes
- This will keep the application lean and the formatErrors method can do easy lookups
- Feel free to add on
- After adding here, you need to update application/language/phrases.php, then regenerate .pot and update proper .po translations. Helper script to come
*/
$config['error_codes'] = array();

// Marks - Level 1 - 29
$config['error_codes'][1] = 'Mark could not be archived.';
$config['error_codes'][2] = 'No marks found.';
$config['error_codes'][3] = 'Mark could not be updated.';
$config['error_codes'][4] = 'Mark with this id could not be found for this account.';
$config['error_codes'][5] = 'Mark could not be restored.';
$config['error_codes'][6] = 'Could not add mark.';
$config['error_codes'][7] = 'Could not delete mark.';
$config['error_codes'][8] = 'This mark doesn\'t have a valid URL.';
$config['error_codes'][9] = 'This mark doesn\'t have a valid title.';

// Labels - Level 30 - 59
$config['error_codes'][30] = 'No `label_id` was found.';
$config['error_codes'][31] = 'No label found using this label_id for your account.';
$config['error_codes'][32] = 'No labels found for your account.';
$config['error_codes'][33] = 'This type of label could not be found.';
$config['error_codes'][34] = 'Label already exists for this account.';
$config['error_codes'][35] = 'No options found to update for this label.';
$config['error_codes'][36] = 'Label could not be created.';
$config['error_codes'][37] = 'You do not have access to create a system level label.';
$config['error_codes'][38] = 'Label already exists for this account.';
$config['error_codes'][39] = 'Label could not be updated.';

// Tags - Level 60 - 89
$config['error_codes'][60] = 'No tags found.';
$config['error_codes'][61] = 'No tag provided.';
$config['error_codes'][62] = 'Tag could not be added.';

// Password recovery - 90-99
$config['error_codes'][90] = 'Account with given email does not exist.';
$config['error_codes'][91] = 'Invalid password recovery token.';

// Import - 100 - 105
$config['error_codes'][100] = 'No file uploaded.';
$config['error_codes'][101] = 'Invalid file format uploaded. Only JSON files accepted';

// HTTP mimic status codes
// Only to be used if they make sense
$config['error_codes'][403] = 'Forbidden';
$config['error_codes'][404] = 'Not Found';
$config['error_codes'][500] = 'Internal Error';

// System Level Errors - Level 600 - 699
$config['error_codes'][600] = 'Security token is invalid.';
$config['error_codes'][601] = 'Validation Errors';
$config['error_codes'][602] = 'Your password is invalid. Passwords must contain at least one CAPITAL letter, one number and be a minimum of 6 characters.';
$config['error_codes'][603] = 'This email address already exists in the system.';
$config['error_codes'][604] = 'Your email address is invalid.';

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

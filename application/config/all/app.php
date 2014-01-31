<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
- Define application errors and error codes
- This will keep the application lean and the formatErrors method can do easy lookups
- Feel free to add on
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

// System Level Errors - Level 600 - 699
$config['error_codes'][600] = 'Security token is invalid.';
$config['error_codes'][601] = 'Internal Error';
$config['error_codes'][602] = 'Validation Errors';

/*
 - Array of titles & urls to add to all newly registered accounts

 These will be added to every new account so when they register they
 have some links to *do* something with. The label of 'Do' will be added automatically.
*/
$config['new_account_links'] = array(
    'Read Nilai\'s FAQ' => array(
        'url'      => 'http://nilai.co/help/faq',
        'label_id' => '7'
    ),
    'How To Use Nilai'  => array(
        'url'      => 'http://nilai.co/help/how',
        'label_id' => '7'
    )
);
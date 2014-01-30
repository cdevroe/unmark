<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

/*
- Define application errors and error codes
- This will keep the application lean and the formatErrors method can do easy lookups
- Feel free to add on
*/
$config['error_codes'] = array();

// Marks - Level 10
$config['error_codes'][10] = 'Mark could not be archived.';
$config['error_codes'][11] = 'No marks found.';
$config['error_codes'][12] = 'Mark could not be updated.';
$config['error_codes'][13] = 'Mark with this id could not be found for this account.';
$config['error_codes'][14] = 'Mark could not be restored.';
$config['error_codes'][15] = 'Could not add mark.';

// Labels - Level 20
$config['error_codes'][20] = 'No `label_id` was found.';
$config['error_codes'][21] = 'No label found using this label_id for your account.';
$config['error_codes'][22] = 'No labels found for your account.';
$config['error_codes'][23] = 'This type of label could not be found.';
$config['error_codes'][24] = 'Label already exists for this account.';
$config['error_codes'][25] = 'No options found to update for this label.';
$config['error_codes'][26] = 'Label could not be created.';
$config['error_codes'][27] = 'You do not have access to create a system level label.';
$config['error_codes'][28] = 'Label already exists for this account.';
$config['error_codes'][29] = 'Label could not be updated.';

// Tags - Level 30
$config['error_codes'][30] = 'No tags found.';
$config['error_codes'][31] = 'No tag provided.';
$config['error_codes'][32] = 'Tag could not be added.';

// System Level Errors - Level 100
$config['error_codes'][100] = 'Security token is invalid.';
$config['error_codes'][101] = 'Internal Error';
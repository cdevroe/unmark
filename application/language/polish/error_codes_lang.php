<?php

/*
 - Define application errors and error codes
- This will keep the application lean and the formatErrors method can do easy lookups
- Feel free to add on
*/

// Marks - Level 1 - 29
$lang['error_codes_1'] = 'Mark could not be archived.';
$lang['error_codes_2'] = 'No marks found.';
$lang['error_codes_3'] = 'Mark could not be updated.';
$lang['error_codes_4'] = 'Mark with this id could not be found for this account.';
$lang['error_codes_5'] = 'Mark could not be restored.';
$lang['error_codes_6'] = 'Could not add mark.';
$lang['error_codes_7'] = 'Could not delete mark.';
$lang['error_codes_8'] = 'This mark does\'t have a valid URL.';
$lang['error_codes_9'] = 'This mark does\'t have a valid title.';

// Labels - Level 30 - 59
$lang['error_codes_30'] = 'No `label_id` was found.';
$lang['error_codes_31'] = 'No label found using this label_id for your account.';
$lang['error_codes_32'] = 'No labels found for your account.';
$lang['error_codes_33'] = 'This type of label could not be found.';
$lang['error_codes_34'] = 'Label already exists for this account.';
$lang['error_codes_35'] = 'No options found to update for this label.';
$lang['error_codes_36'] = 'Label could not be created.';
$lang['error_codes_37'] = 'You do not have access to create a system level label.';
$lang['error_codes_38'] = 'Label already exists for this account.';
$lang['error_codes_39'] = 'Label could not be updated.';

// Tags - Level 60 - 89
$lang['error_codes_60'] = 'No tags found.';
$lang['error_codes_61'] = 'No tag provided.';
$lang['error_codes_62'] = 'Tag could not be added.';

// Password recovery - 90-99
$lang['error_codes_90'] = 'Account with given email does not exist.';
$lang['error_codes_91'] = 'Invalid password recovery token.';

// Import - 100 - 105
$lang['error_codes_100'] = 'No file uploaded.';
$lang['error_codes_101'] = 'Invalid file format uploaded. Only JSON files accepted';

// HTTP mimic status codes
// Only to be used if they make sense
$lang['error_codes_403'] = 'Forbidden';
$lang['error_codes_404'] = 'Not Found';
$lang['error_codes_500'] = 'Internal Error';

// System Level Errors - Level 600 - 699
$lang['error_codes_600'] = 'Security token is invalid.';
$lang['error_codes_601'] = 'Validation Errors';
$lang['error_codes_602'] = 'Your password is invalid. Passwords must contain at least one CAPITAL letter, one number and be a minimum of 6 characters.';
$lang['error_codes_603'] = 'This email address already exists in the system.';
$lang['error_codes_604'] = 'Your email address is invalid.';
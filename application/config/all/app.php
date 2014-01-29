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
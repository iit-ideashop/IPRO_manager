<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		'Google' => array(
    'client_id'     => '1090699868025.apps.googleusercontent.com',
    'client_secret' => 'KqzESuf6jeNQAab8ndKPQFxC',
    'scope'         => array('userinfo_email', 'userinfo_profile'),
    'prompt'        => 'select_account'
),  

	)

);
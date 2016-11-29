<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

// Main config file to utilise all the functionality of the classes, autoload them and sanitise all input and output.

session_start();			//Start Session so people can login.

$GLOBALS['config'] = array(		// Global variable which is an array OF configuration settings.
	'mysql' => array(
		'host' => '127.0.0.1',		// Use default IP over localhost as when dealing with PDO (PHP Data Objects). We will need an IP as PDO will do a table lookup if localhost is used instead of IP, this slows php page load times.
		'username' => 'root',
		'password' => '',
		'db' => 'login'
	),
	'remember' => array(
		'cookie_name' => 'hash',			// Define what our cookie name will be.
		'cookie_expiry' => 604800			// Set expiry time for our cookie, I have set 604800 which is one week in seconds.
	),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	)
);

/* SPL or Standard PHP Library to streamline our code. It quickly auto loads classes if and when 
required autoloads classes when needed so rather than using require_once in all the classes in different 
places adding extra lines of code to our app we can just use this -> require_once 'core/init.php'; at the top 
of every file page to auto load or register all of the classes to make use of all the config, functionality 
or starting sessions etc. */

spl_autoload_register(function($class){
	require_once 'classes/'.$class.'.php';
});

require_once 'functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){	// Check to see if cookie exists and user is logged in or not and the session exists.
	// echo 'User asked to be remembered';																			// Sanity check to check remember me functionality.
	// echo 'Hash matches, log user in.';																			// Modified the hash in firebug to test for security.
	$hash = Cookie::get(Config::get('remember/cookie_name'));														
	$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));								// Hash check for the session array.

	if($hashCheck->count()){																						// If hash matches log the user in.
		$user = new User($hashCheck->first()->user_id);																// User id that is stored alongside the hash in the database.
		$user->login();
	}
}
?>
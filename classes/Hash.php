<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

/* Class to make hash and salt encryptions for sessions and passwords to give users unique session id's
and encrypted passwords. */

class Hash{
	public static function make($string, $salt= ''){			// 3 methods to make the session salt, hashed password and unique id.
		return hash('sha256', $string . $salt);
	}

	public static function salt($length){
		return mcrypt_create_iv($length);
	}

	public static function unique(){
		return self::make(uniqid());
	}
}
?>
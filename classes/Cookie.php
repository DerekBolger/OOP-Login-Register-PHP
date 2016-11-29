<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

// This cookie class will work similar to the input class in it's functionality.

class Cookie{
	public static function exists($name){							// This method will check whether a cookie actually exists or not.					
		return (isset($_COOKIE[$name])) ? true : false;				// Returns similar to the session method for the name we have defined and see if the cookie has been set.
	}

	public static function get($name){								// Method to get the value of a cookie.
		return $_COOKIE[$name];
	}

	public static function put($name, $value, $expiry){				// This put method just creates the cookie with a name, value and expirey.
		if(setcookie($name, $value, time() + $expiry, '/')){		// Set the cookie and pass the parameters, time plus expirey is set in seconds and the path at the end.
			return true;											// If cookie works return true.
		}
		return false;												// Otherwise return false.
	}

	public static function delete($name){							// Method to delete a cookie as we now have the put functionality above, to delete a cookie we don't just unset it we reset it with a negative value or an empty string.
		self::put($name, '', time() - 1);							// Relace the cookie with an empty string.
	}
}
?>
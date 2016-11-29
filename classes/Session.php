<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

/* In this class we build the put functionality, to see if the particular session actually exists.
anywhere we submit data in the application.  We also add the method which gives us the ability to 
flash data which will be used in the register.php file which will flash a message to a user then on 
refresh it isn't available anymore. Doesn'thave t benamedflashit's just a common name for this functionality*/


class Session{
	public static function exists($name){					// Public method to check if a session exists. If the token is set proceed if not return false.
		return (isset($_SESSION[$name])) ? true : false;
	}

	public static function put($name, $value){				// Public method to put the session name and the session value and return the value of the sesion.
		return $_SESSION[$name] = $value;
	}

	public static function get($name){						// This method gives us the ability to get a particular session value.
		return $_SESSION[$name];
	}

	public static function delete($name){					// In this method we have the ability to delete a token.
		if(self::exists($name)){
			unset($_SESSION[$name]);						// Unset the session if it exists.
		}
	}

	public static function flash($name, $string = ''){		// Public method defines a name for the flash data so on refresh it doesn't display to the user again to avaoid confussion.
		if(self::exists($name)){							// Check if the session exists.
			$session = self::get($name);					// Store the session in this variable.
			self::delete($name);							// Delete the session here.
			return $session;
		}else{												// Otherwise we want to set the data here.
			self::put($name, $string);						// Here we use the put method above to put a specific value to a specific name.
		}

	}
}
?>
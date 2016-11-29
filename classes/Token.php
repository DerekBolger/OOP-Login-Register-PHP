<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

/* Token class to eliminate CSRF (Cross Site Request Forgery) to prevent this we will generate a token to only allow
Data from the form posted back to our own internal php.We need the ability to generate a token and check whether a token
is valid and exists and then delete that token. I t will generate a token for each refresh of the page which only that 
particular page knows, so another user can not direct you to that page as the token will always be checked. Tken will be
generated in the form with an input of hidden and a name of token and a value of this class. We will set the name of the 
token in the session  in the innit.php file. */

class Token{
	public static function generate(){												// Create a method here to generate a token for the session.								
		return Session::put(Config::get('session/token_name'), md5(uniqid()));		// Now we build the session class to utilis this method.
	}

	public static function check($token){											// Mthod to get a token from the session and check if it's the same as the one defined in the form.
		$tokenName = Config::get('session/token_name');								

		if(Session::exists($tokenName) && $token === Session::get($tokenName)){		// Checkif the token supplied by the form is equal to the session token.
			Session::delete($tokenName);
			return true;
		}
		return false;																// Return false if none of the above is true.
	}
}
?>
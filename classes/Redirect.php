<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

// Here we are building the redirect class which will contain only one method to redirect users to a 404 page if they encounter errors.

class Redirect{
	public static function to($location = null){				
		if($location){											// Check ifthe location has been defined.
			if(is_numeric($location)){							// Making use of the php is_numeric function, this will not be numeric if we are directing to a defined path.
				switch ($location) {
					case 404:									// We canalso add additional error codes in this switch statement.
						header('HTTP/1.0 404 Not Found');		// Set the header to link to the 404 error page.
						include 'includes/errors/404.php';		// Include the custom error template found in my includes directory.
						exit();
						break;
				}
			}
			header('Location: ' . $location);
			exit();
		}
	}
}
?>
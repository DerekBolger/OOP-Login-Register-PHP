<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

// This input file will contain a method to check if data exists and a method to find an item.

class Input{
	public static function exists($type = 'post'){			// Static method to see if input exists and define the data we want to get. Default will be the posted data from the form.
		switch ($type) {									// Switch on type and check a few different cases.
			case 'post':									// Need to checkthe post from the input field.
				return (!empty($_POST)) ? true : false;		// Ternary operator
				break;
			case 'get':
				return (!empty($_GET)) ? true : false;
				break;
			
			default:										// Default will be return false because we need to assume we will always be getting some form of data.
				return false;
				break;
		}
	}

	public static function get($item){						// Static method to define which item we want to get/
		if(isset($_POST[$item])){							// Checking here for post data.
			return $_POST[$item];							// If available return that item.
		}else if(isset($_GET[$item])){						
			return $_GET[$item];							// Otherwise get the data.
		}
		return '';											// By default we want to return an empty string, we want to assume this data is always avaiable if not return the empty string.
	}
}
?>
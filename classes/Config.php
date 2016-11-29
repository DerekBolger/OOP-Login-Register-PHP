<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

/* Thisis the config class that will give us the ability to draw on any option that we need from the config in the innit.php 
file in the core folder where we defined the configuration which is set as a GLOBAL. I t will allow us to acces the config in a 
clean way using forward slashes, think of it as a directory seperator. Once built we can access the config from anywhere 
at any level deep inthe directory so whatever is added to the config we can access from anywhere else. It wil contain just
one method */

class Config{											
	public static function get($path = null){			// We don't need to require this config anywhere as we already have a class autoloader built.
		if($path){										// Make sure that path has been passed to this method.					
			$config = $GLOBALS['config'];				// Create a variable to define where the config is coming from.
			$path = explode('/', $path);				// Class is sanity checked in index.php file. Th path is defined by the / and will return an array.
			// print_r($path);							// Sanity checck, this will show us the array of 2 elements 1 mysqli and a host.

			foreach($path as $bit){						// Loop through each element of the array.
				// echo $bit, ' ';						// Sanity check, returns the array.
				if(isset($config[$bit])){				// Check if they are set in the config via language construct.		
					// echo 'Set';						// Sanity check, check is config equal to config and the bit.
					$config = $config[$bit];			// Set config equal to config and the bit.
				}
			}

			return $config;								// Return the above values.
		}

		return false;									// Return false if we don't have anything existing
	}
}
?>
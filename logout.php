<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

// Here we want to create the logout file which basically just gives the user the ability to logout.

require_once 'core/init.php';

$user = new User();				// Create a new user object to utilise the functionality of the user class.
$user->logout();				// We could also check if a user is logged in before they arrive at this page but all we're doing is deleting a session.

Redirect::to('index.php');		// Redirect to index.php using the redirect class helper.

?>
<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

/* This file provides the functionality for when we are on the home page we can click our own name
and be directed to profile.php and if the user doesn't exist we generate a 404 error the functionality of 
which has already been created in the redirect class */

require_once 'core/init.php';

if(!$username = Input::get('user')){				// Check if there is not a username supplied.
	Redirect::to('index.php');
}else{
	$user = new User($username);					// Check if the user exists if not redirect to the error page.
	if(!$user->exists()){
		Redirect::to(404);
	}else{
		$data = $user->data();						// Returns the data that has been stored once we've got the user information.
	}
	?>

<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title>Login Form</title>
    	<link rel="stylesheet" href="style.css">
    </head>

  <body>

	<div class="container">
		<div class="form-container">
			<form action="" method="post" class="login-form">
				<h3 class="title"><?php echo escape($data->username); ?></h3>				
				<p>Full name: <?php echo escape($data->name); ?> </p>	
			</form>
		</div>
	</div>

  </body>

</html>

	<?php
}
?>
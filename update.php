<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

/* This file gives the user the ability to change their profile details.  It will not take much as we already have a 
validation class the ability to check whethera user is signed in or not, form tokens, input helpers and the ability 
to pass the user back to the home page to let them know their details have been updated. */

require_once 'core/init.php';

$user = new User();									// Instantiate a new user object, from this we can find if a user is logged in or not.

if(!$user->isLoggedIn()){							// If user is not logged in redirect them to the home page.
	Redirect::to('index.php');
}

if(Input::exists()){								// Check in input exists and tokens again to reduce the risk of cross site request forgery.
	if(Token::check(Input::get('token'))){			// Check if the token supplied id the same as the session.
		// echo 'OK!';								// Sanity check for the token value.
		
		$validate = new Validate();							// Validate to the same standard as if we registered a user.
		$validation = $validate->check($_POST, array(		// Check the POST data.
			'name' => array(
				'disp_text' => 'Name',
				'required' => true,							// Array of Rules for the validation.
				'min' => 2,
				'max' => 50
				)
			));

		if($validation->passed()){							// Check if the validation has passed.

			try{											// Try catch block to throw an exception inside of the user method if the database operation fails.							

				$user->update(array(						// Pass the element we want to update and the value..
					'name' => Input::get('name')
					));

				Session::flash('home', 'Your details have been updated.');
				Redirect::to('index.php');

			}catch(Exception $e){
				die($e->getMessage());
				//redirect user to another page
			}

		}else{
			foreach($validation->errors() as $error){							// Loop through the errors that have been returned.
				echo $error, '<br/>';											// This will echo out if name is required, password is too short etc.
			}
		}
	}
}
?>

<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title>Update Details</title>
    	<link rel="stylesheet" href="style.css">
    </head>

  <body>

	<div class="container">
		<div class="form-container">
			<form action="" method="post" class="login-form">
				<h3 class="title">Update Details.</h3>
				<label class="label" for="name">Name</label>
				<input class = "username-input"type="text" name="name" value="<?php echo escape($user->data()->name); ?>" id="name">

				<input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>" />
				<input class="update-button" type="submit" value="Update" />
			</form>
		</div>
	</div>

  </body>

</html>
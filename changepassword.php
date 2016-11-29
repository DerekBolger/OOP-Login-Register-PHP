<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

/* As same as it's name this file gives the user the ability to changehis or her password and is more or less identical to 
the update information file, the only difference being we will check the new password and the confirm password are the same.
Make a hash and the salt of the user and compare it against the current password, we generate a new salt everytime they update their password */

require_once 'core/init.php';

$user = new User();													// Instantiate a new user object, from this we can find if a user is logged in or not.

if(!$user->isLoggedIn()){											// If user is not logged in redirect them to the home page.
	Redirect::to('index.php');
}

if(Input::exists()){												// Check in input exists and tokens again to reduce the risk of cross site request forgery.
	if(Token::check(Input::get('token'))){							// Check if the token supplied id the same as the session.

		$validate = new Validate();									// Validate to the same standard as if we registered a user.

		$validation = $validate->check($_POST, array(				// Check the POST data.
			'password_current' => array(
				'disp_text' => 'Current Password',					// Array of Rules for the validation.
				'required' => true,
				'min' => 6
				),
			'password_new' => array(
				'disp_text' => 'New Password',
				'required' => true,
				'min' => 6
				),
			'password_new_again' => array(
				'disp_text' => 'Confirm Password',
				'required' => true,
				'matches' => 'password_new'
				)
			));

		if($validation->passed()){

			if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password){		// Make a hash and append on the current salt of the user as wee need to detect this to let the user know their information input is wrong.
				echo 'Your current password is wrong.';
			}else{
				$salt = Hash::salt(32);																				// Generate a new salt																

				try{

					$user->update(array(																			//  Make a hash and pass the array of things we want to update as in the password and salt.
						'password' => Hash::make(Input::get('password_new'), $salt),
						'salt' => $salt
						));

					Session::flash('home', 'Your password has been changed!');										// Flash the session to the browser for the user to see and redirect the user.
					Redirect::to('index.php');

				}catch(Exception $e){																				// Redirect user to another page
					die($e->getMessage());
				
				}
			}

		}else{
			foreach($validation->errors() as $error){																// Loop through the errors that have been returned.
				echo $error, '<br/>';																				// This will echo out if name is required, password is too short etc.// This will echo out if name is required, password is wrong etc.
			}
		}

	}
}
?>

<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="UTF-8">
    	<title>Change Password</title>
    	<link rel="stylesheet" href="style.css">
    </head>

  <body>

	<div class="container">
		<div class="form-container">
			<form action="" method="post" class="login-form">
				<h3 class="title">Change Password.</h3>
			<div class="current_password">
			<input class = "username-input" placeholder="Current Password" type="password" name="password_current" id="password_current">
			</div>
			<div class="new_password">
			<input class = "username-input" placeholder="New Password" type="password" name="password_new" id="password_new">
			</div>
			<div class="confirm_new_password">
			<input class = "username-input" placeholder="Confirm New Password" type="password" name="password_new_again" id="password_new_again">
			</div>
			<input class="update-button" type="submit" value="Change" />
			<input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>" />
			</form>
		</div>
	</div>

  </body>

</html>
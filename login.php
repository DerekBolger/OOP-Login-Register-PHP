<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

/* This file gives the user the ability to sign in to their account and set a session to signify
they are signed in andthe abiliyty to remember the specific user. */

/*if (Input::exists()) {
	if(Token::check(Input::get('Token'))) {
		$validate = new Validate();							// Sanity check will incorporate thisinto the code to be written below.
		$validation = $validate->check($_POST, array(
				'username' => array('required' => true),
				'password' => array('required' => true),
			));
		echo $error, '<br/>';
	}
}*/

require_once 'core/init.php';

if(Input::exists()){									// Check if input exists, i.e., whether the form hasbeen submitted.
	if(Token::check(Input::get('token'))){				// Check if token is correct and has been supplied by the form.

		$validate = new Validate();						// Here we are checking the array of values for username and password.
		$validation = $validate->check($_POST, array(
			'username' => array(
				'disp_text' => 'Username',
				'required' => true
				),
			'password' => array(
				'disp_text' => 'Password',
				'required' => true
				)
			));

		if($validation->passed()){						// Check if the validation is passed.
			
			$user = new User();							// Instantiate new user object.

			$remember = (Input::get('remember') === 'on') ? true : false;						// Create a remember variable and create a ternary operator to detect if the remember me checkbox has been checked or not.
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);	// The above operator is passed into the login method.

			if($login){
				Redirect::to('index.php');
			}else{
				echo '<p>Sorry, name or password is incorrect.</p>';							// Otherwise echo login has failed due to incorrect input.
			}

		}else{

			foreach($validation->errors() as $error){				// Echo out the error if validation isn't passed.
				echo $error, '<br/>';
			}

		}
	}
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
				<h3 class="title">Login.</h3>
			<div class="login_username">
				<label class="label" for="username">Username</label>
				<input class = "username-input" type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off"></inputt>
			</div>
			<div class="login_password">
				<label class="label" for="password">Password</label>
				<input class = "password-input" type="password" name="password" id="password" autocomplete="off">
			</div>
			<div class="remember_me">
				<input class = "remember-checkbox" type="checkbox" name="remember" id="remember"><p class ="remember-p"> Remember me </p>
			</div>
				<input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>" />
				<input class="login-button" type="submit" value="Log In" />
			</form>
		</div>
	</div>

  </body>

</html>
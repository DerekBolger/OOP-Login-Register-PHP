<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

/* Build a validation class that we can use on any of the pages for when a user is changing the password, updating profile information
or when logging in. It is used for required fields in the form that should match first and secondary registration of the password to 
make sure the user hasn't made an error in their input. It also gives us the ability to check a unique value. This file again could be 
used outside of this login form for reuseability. */

/* if(Input::exists('post')) {				// Sanity check to check if the form has been submitted.
	echo 'Submitted';
} */

/* if(Input::exists('post')) {				// Sanity check to check if the form has been submitted from the second static method in the input file.
	echo  $_POST'['USERNAME'];	
	echo Input::get('username');			// Same as above but using the methods functionality.
} */

/* if(Input::exists('post')) {				
	$validate = new Validate();
	$validation = $validate->check($post, array(			// This array encloses all the rules we need in the validation. Will refer back to this when building the functionality.
		'username' => array('requred => true'),
		'password' => array(),
		'password_again' => array,
		'name' => array();
	));	
} */
// var_dump(Token::check(Input::get('Token')));	//  Sanity check to pass in the token that's been supplied by the form. Returns bool(false)so we now knowthe session has failed andtoken is functional.


require_once 'core/init.php';

if(Input::exists()){												// Check if the input from the formfield exists.
	if(Token::check(Input::get('token'))){
		// echo 'I have been run';									// Sanity check for validation output.
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array(									// To match field name in the database.
				'disp_text' => 'Username',
				'required' => true,									
				'min' => 2,											// Minimum value for the input lenght for username set to 2.
				'max' => 20,										// Max value set to 20.
				'unique' => 'users'									// To avoid having an additional query inside of this code to be unique to the users table, so as not to have to form additional queries..
				),
			'password' => array(
				'disp_text' => 'Password',
				'required' => true,
				'min' => 6											// Make password a little more secure by specifying at least 6 characters.
				),
			'password_again' => array(
				'disp_text' => 'Confirm Password',
				'required' => true,
				'matches' => 'password'								// This is set to match the input or the required password above.
				),
			'name' => array(
				'disp_text' => 'Name',
				'required' => true,
				'min' => 2,											// Minimum value for the input lenght for name set to 2.											
				'max' => 50											// Max value set to 50.
				)
			));

		if($validation->passed()){									// Detect if the validation has passed go ahead and register the user.
			$user = new User();

			$salt = Hash::salt(32);									
			
			try{

				$user->create(array(
					'username' => Input::get('username'),
					'password' => Hash::make(Input::get('password'), $salt),
					'salt' => $salt,
					'name' => Input::get('name'),
					'joined' => date('Y-m-d H:i:s'),
					'group' => 1
					));

				Session::flash('home', 'You have been registered and can now log in!');		// If a user registers correctly redirect to the main index.php file.
				Redirect::to('index.php');													// Define how we want to redirect.

			}catch(Exception $e){
				die($e->getMessage());
				//redirect user to another page
			}
		}else{
			foreach($validation->errors() as $error){
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
    <title>Register Form</title>

		<link rel="stylesheet"  type="text/css" href="style.css">

  </head>

  <body>

    <div class="container">
  	<div class="form-container">
	<form action="" method="post" class="login-form">
		<h3 class="title">Register.</h3>
		<div class="username_register">
			<label for="username"></label>
			<input class = "username-input" placeholder="Username"type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">
		</div>
		<div class="password_register">
			<label for="password"></label>
			<input class = "username-input" placeholder="Password" type="password" name="password" id="password">
		</div>
		<div class="confirm_password">
			<label for="confirm_password"></label>
			<input class = "username-input" placeholder="Confirm Password" type="password" name="password_again" id="password_again">
		</div>
		<div class="name_register">
			<label for="name"></label>
			<input class = "username-input" placeholder="Name" type="text" name="name" value="<?php echo escape(Input::get('name')); ?>" id="name">
		</div>
		<input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>" />
		<input class="login-button" type="submit" class="btn_register" value="Register" />
	</form>
  </div>
</div>

  </body>
</html>
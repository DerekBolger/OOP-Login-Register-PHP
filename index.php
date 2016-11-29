<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

require_once 'core/init.php';

//echo Config::get('mysql/host');  // This should output '127.0.0.1' as I'm accessing host through the GLOBAL array in the init.php file.
/*
// If I want to get a list of all users through the static method in the database file.
$users = DB::getInstance()->query('SELECT username FROM users');
if($users->count()) {
	foreach($users as $user) {			//Returning an object here
		echo $user->username;
	}
}

if(Session::exists('Success')) {					// Sanity check to check if the flash functionality is working correctly.
	echo Session::flash('Success');
}

// echo Session::flash('Success');					// Could also use a return value in the method.

// These are helper methods that can be defined,can be used to insert deleteor update.
$users = DB::getInstance()->get('users', array('username', '=', 'derek'  // Very readable code as we get users from our database named Derek.
	'userername' => 'derk',
	'password' => 'password',
	'salt' => 'salt',
));  

if($users->count()) {
	foreach($users as $user) {
		echo $user->username;
	}
} 
// echo Session::get(Config::get('session/session_name'));  // Sanity checkfor session functionality. It will return the id of the user from the table.

DB::getInstance()->query("SELECT username FROM users WHERE username = ?", array('derek'));

if($user->error()) {			// Ability to query and check whether there is an error or not.
	echo 'No user';				// Use echo $user->results()[0]->username, this only gets the first result. Would be good to use inside a method.
} else{
	echo'ok!;					// echo $user->first()->username; for the cleaner method below.
}
public function first(){
	return $this-> _results;	//This would be a cleaner method. We can now via the methods loop through all theresults or just pick the first.
}
$use = DB::getInstance()->insert('users', array());

$db = new DB(); 				//This won't work I need to call a DB Instance.
db::getInstance(); 				//In the DB file I will create a public method which will check that I have already Instantiated via the object. 

$user = new user();				// Current user.	
$anotheruser = new user(6);			// Another user.
echo $user->data()username;			// Returns current user that has been logged in.
*/

if(Session::exists('home')){
	echo '<p>' . Session::flash('home') . '</p>';
}

$user = new User();

if($user->isLoggedIn()){				// If the user is logged in this will give them the ability to make changes and perform the tasks below like logout, update details or password. 
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
			<div action="" method="post" class="login-form">
				<h3 class="title">Welcome.</h3>

				<p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a> !</p>
				<ul>
					<li><a href="logout.php">Log out</a></li>							
					<li><a href="update.php">Update details</a></li>
					<li><a href="changepassword.php">Change password</a></li>
				</ul>

				<?php
					if($user->hasPermission('admin')){						// Check to see what permissions a user has.
						echo '<p> You are an admin! </p>';
					}

					if($user->hasPermission('moderator')){
						echo '<p> You are a moderator! </p>';
					}

					// if(!$user->hasPermission('admin')){					// Could have the check at the top of every page also.
					// Redirect::to(404);
					// }

					}else{
						echo '<html>
								  <head>
								    <meta charset="UTF-8">
								    <title>Login Form</title>
								 	<link rel="stylesheet" href="style.css">
								   </head>

								  <body>
										<div class="container">
											<div class="form-container">
											<h3 class="title">Login/Register Application</h3>
												<form action="" method="post" class="login-form">
													<h3 class="home">Welcome, please <a href="login.php">log in</a> or <a href="register.php">register</a> .</h3>
												</form>
											</div>
										</div>
									</body>';
							}
						?>
			</div>
		</div>
	</div>

  </body>

</html>
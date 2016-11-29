<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

// Class to give the user various different kinds of functionality such as updating profile details passwords etc.

class User{
	private $_db,
			$_data,											// Property to store data for the find method to be written below.
			$_sessionName,									// Store session as a property to be used in the constructor below.
			$_cookieName,									// Set the cookie name.
			$_isLoggedIn;

	public function __construct($user = null){
		$this->_db = DB::getInstance();

		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		if(!$user){														
			if(Session::exists($this->_sessionName)){					// Check if the session exists andset the fact that the user has been logged in.
				$user = Session::get($this->_sessionName);
				// echo $user;											// Sanity check This will print out user id to the browser.
				if($this->find($user)){									// Check if the useractually exists or not. This will grab the user data from the constructor.
					$this->_isLoggedIn = true;
				}else{
					//process logout
				}
			}
		}else{
			$this->find($user);											// If the user has been defined this allows use to get the data of a user who hasn't logged in.
		}
	}

	public function update($fields = array(), $id = null){				// Update method to take in the fields, by default will be an empty array if it'snot been defined.

		if(!$id && $this->isLoggedIn()){								// If there has been no id defined and the user is logged in.
			$id = $this->data()->id;
		}
		if(!$this->_db->update('users', $id, $fields)){						
			throw new Exception ('There was a problem updating your details.');
		}
	}

	public function create($fields = array()){
		if(!$this->_db->insert('users', $fields)){
			throw new Exception ('There was a problem creating an account.');
		}
	}

	public function find($user = null){														// Method to find a user by id not just the username.
		if($user){
			$field = (is_numeric($user)) ? 'id' : 'username';								// If it's numeric we want the field to be id otherwise we want the field to be username.
			$data = $this->_db->get('users', array($field, '=', $user));					// Data we get back from the table, in the users table this will be an array of users.

			if($data->count()){
				$this->_data = $data->first();
				return true;																// This means the user does exist so we will return a true value.
			}
		}
		return false;
	}

	public function login($username = null, $password = null, $remember = false){			// Build the login method here. We need a username and password to be passed to this.
		if(!$username && !$password && $this->exists()){									// Check whether the username and password has been definedand the user exists and supplied to this method.
			Session::put($this->_sessionName, $this->data()->id);							// Here we utilise the functionality of session.php to set a session.
			// print_r($this->_data);														// Sanity check to return all the user data from the table.							
		}else{
			$user = $this->find($username);                                                 // Using the find method above to define the username.

			if($user){
				if($this->data()->password === Hash::make($password, $this->data()->salt)){		// Here we are checking the submitted password against the password saved to the database.
					Session::put($this->_sessionName, $this->data()->id);						// Here we put the session inside the users id.	

					if($remember){																// Check to see if remember is true or false.
						$hash = Hash::unique();													// Here we generate a unique  hash which will be looked up everytime a user visits the page.
						$hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));	// Check if the hash has been stored in the dtabase. It will store a cookie with a hash value.

						if(!$hashCheck->count()){												// If we don't have a hash in the database.
							$this->_db->insert('users_session', array(							// Insert a record into the database.
								'user_id' => $this->data()->id,									// Insert user into database.
								'hash' => $Hash 												// Insert hash into the database.
								));
						}else{
							$hash = $hashCheck->first()->hash;									// Otherwise we want to set the hash as we don'twant more than one hash being inserted into the database.
						}

						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));	// Store a cookie in the database which is similar to the session method andth name from the config file.
					}
					return true;																		// Return true to signify that this has beena successful login.
				}
			}
		}

		return false;
	}

	public function hasPermission($key){												// This method will give us the ability to define what key we're looking for from key valuepairs in the DB.
		$group = $this->_db->get('groups', array('id', '=', $this->data()->group));		// Get data from the groups table.
		// print_r($group->first());													// Sanity check to see the group of the user. will outputthe group and permissions.
		
		if($group->count()){
			$permissions = json_decode($group->first()->permissions, true);				// We need to decode it as it's a json object using th php json decode function in php and return a php array asindatabase its a javascript object.
																						// Check if the useris in a group or not, by default it will return an object but we want an array so we can pick the key from it.
			if($permissions[$key] == true){	
				return true;
			}
		}
		return false;
	}
	
	public function exists(){															// Method to check whether the data exists in the array and the user and password hasn't been defined.
		return (!empty($this->_data)) ? true : false;
	}

	public function logout(){															// Public method to enable the user to logout.
		$this->_db->delete('users_session', array('user_id', '=', $this->data()->id));	// Here I utilise the session helper to delete the user session and remove it from the database, for security we want to generate the session and cookie each time the user logs in.

		Session::delete($this->_sessionName);											// Delete session and cookie to stop an automatic login after logout.
		Cookie::delete($this->_cookieName);
	}

	public function data(){										// This will return data for the login.
		return $this->_data;
	}

	public function isLoggedIn(){								// Getter method to return login data.
		return $this->_isLoggedIn;
	}
}
?>
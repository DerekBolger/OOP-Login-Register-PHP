<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

/* This is the core of the app where we will access all database information this is basically a database
wrapper which will give me simple functionality. This can also be recycled for further use outside of this 
application by incorporating the database connection functionality into other projects. This app will be working 
with PDO so we can define the type of database we would like to work with that supports PDO. */

class DB{
	private static $_instance = null;					// Singleton pattern to restrict instantiation of the class to one object, we will have a main  method called getInstance below which will allow 
														// me to get an instance of the database if it's already been instantiated so we can use it on the fly to access the database.*/
	private $_pdo,										// A private static object which will store the instance of the database if it's available or if its not I can instantiate the DB object.
			$_query,
			$_error = false,							// Here we have all my private properties within the object that won't be accessible outside of this class.
			$_results,									// The underscore is just a notation for me that they are private properties as I use these for private and protected. properties.
			$_count = 0;								// The count of results is important as I will be providing a method that will allow me to see if therehave been results returned.

	private function __construct(){
		try{
			$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
		}catch(PDOException $e){						//Try catch block which allows me to catch errors inside of the try block.
			die($e->getMessage());						//If I do have an error it will kill the application then ouput the message returned from it.
		}
	}

	public static function getInstance(){
		if(!isset(self::$_instance)){				// Check if the instance is not set.
			self::$_instance = new DB();			// If its not been set, set it here, by default it wont be so create a new DB instance, will run the code in the above constructor.			
		}
		return self::$_instance;					// Here it will return the self instances so I can avail of the functionality of the class.
	}												// By using this I will negate the need to connect to the database again and again.					

	public function query($sql, $params = array()){			// Define a method that will take 2 arguments, the query string and an array of parameters as binded values in PDO.
		$this->_error = false;								// Reset error to false as we can perform multiple queries at a time and I avoid returning an error from previous queries.
		if($this->_query = $this->_pdo->prepare($sql)){		// Check if thequery is prepared then bind the parameters andreturnresults as an object.
			$x = 1;											// Set x to be the counter.
			if(count($params)){								// Checking if anything has been added to the array.
				foreach($params as $param){
					$this->_query->bindValue($x, $param);	// Store the position to the value and bind the parameter.
					$x++;									// Increment x here for each loop.
				}
			}

			if($this->_query->execute()){									// If there are no parameters we still want to execute the query, here we have stored the query then we're executing it.
				//echo 'Success';											// Sanity check to see if it been succesfully executed.
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);	// We cannow access the database securely by binding parameters and removing the possibility of SQL injections.	
				$this->_count = $this->_query->rowCount();					// The line above we store the result set using the fetchAll which is a method of PDO to fetch the object of results.
			}else{															// It doesn't make sense to fetch the array we want an object we want the value of the coulumns in our DB to be returned as an object.
				$this->_error = true;										// Public function to hadle an error by default wil return false.
			}
		}
		return $this;														// Return the object in the query chain, i.e. return the current object we are working with.
	}

	public function action($action, $table, $where = array()){		// Here we create a method called action which will allow me to perform a specific action like a SELECT or Delete. Thise will relate to the where clause in the query we perform.
		if(count($where) === 3){									// Define a list of operators that we are going to allow.
			$operators = array('=', '>', '<', '>=', '<=');

			$field = $where[0];										// Set 3 variables to extract data from the where array, so we know the field, operator and value.
			$operator = $where[1];
			$value = $where[2];

			if(in_array($operator, $operators)){								// Check whether the operator is inside the operators array.
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";	// Question mark is for the query to bind the value on.

				if(!$this->query($sql, array($value))->error()){				// Query method to perform a query and bind on a value. If there is not an error return this otherwise.
					return $this;
				}
			}
		}
		return false;
	}

	public function get($table, $where){						// Build in the functionality to get and delete data. We are getting or deleting from a table and specifying where based on field names.
		return $this->action('SELECT *', $table, $where);		// Return and define every action we are assuming we want everything from the users table so we don't have to constantly define everything we need to define.
	}															// This is basically a shortcut to the action method above.


	public function delete($table, $where){						// Similar functionality as above.
		return $this->action('DELETE', $table, $where);
	}

	public function insert($table, $fields = array()){			// Insert method to define the field.
		$keys = array_keys($fields);							// Fields we want to update andpass in the array.
		$values = '';											// Value will be null to keep track of query values.
		$x = 1;

		foreach($fields as $field){								// Looping through fields defined in the method.
			$values .= '?';
			if($x < count($fields)){							// Check if the counter is less than the above counter fields.
				$values .= ', ';								// Add comma to the values echoed.
			}
			$x++;
		}

		$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})" ;	// Define list of the keys we defined in the tests at the top of the index file. Implode will take the keys of the array.

		if(!$this->query($sql, $fields)->error()){												// Pass in the fields that have been bound to the question marks. If no error return true, otherwise we return false below.
			return true;
		}

		return false;
	}

	public function update($table, $id, $fields){											// Update a particular set of fields. Also a particular table.
		$set = '';																			// Set to equal an empty string.
		$x = 1;

		foreach($fields as $name => $value){												// Foreach loop to bind the values.
			$set .= "{$name} = ?";
			if($x < count($fields)){														// These are the fields that will be updated.
				$set .= ', ';
			}
			$x++;
		}

		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

		if(!$this->query($sql, $fields)->error()){											// If this doesn't return an error we want it to be true. If not return false below.
			return true;
			return true;
		}

		return false;
	}

	public function results(){
		return $this->_results;
	}

	public function first(){
		return $this->results()[0];
	}

	public function error(){
		return $this->_error;
	}

	public function count(){
		return $this->_count;
	}
}
?>
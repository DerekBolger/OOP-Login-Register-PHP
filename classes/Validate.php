<?php
/****************************************************************
* Author: Derek Bolger
* Assignment WE4.0 PHP Web App Assignment, Digital Skills Academy
* Student ID: D14127405
* Date: 2016/02/14
* Ref: https://www.codecourse.com/
*****************************************************************/

/* This validation class checks if the properties have been passed or not, check if there has been any errors so we can output
them to the browser and the ability to create an instance of the database, when we construct the validator below. */

class Validate{
	private $_passed = false,											// 3 private properties: passed which will be false by default.
			$_errors = array(),											// Will come from the passed array.
			$_db = null;

	public function __construct(){										// This constructor will be called when the validated class is instantiated.
		$this->_db = DB::getInstance();									// Set the database to get an instance of database.
	}

	public function check($source, $items = array()){					// Define the check method from register.php file. It's an array so we don't get errors in our foreach loop.
		foreach($items as $item => $rules){								// Loop through the items defined in register.php, rules will be the array that govern that item.
			foreach($rules as $rule => $rule_value){					// Nested foreach loop to
				// echo "{$item} {$rule} must be {$rule_value}";		// Sanity check echos out all the rules and confirmed we have access.
				$value = trim($source[$item]);
				$item = escape($item);
				$disp_text = $rules['disp_text'];

				if($rule === 'required' && empty($value)){				// Defining that something has to be required.
					$this->addError("{$disp_text} is required.");
				}else if(!empty($value)){								
					switch ($rule) {									// Switch the rule and have a case for each of the rules already defined. The switch makes it a little more modular.
						case 'min':
							if(strlen($value) < $rule_value){													
								$this->addError("{$disp_text} must be a minimum of {$rule_value} characters.");	// Check if string lenght is less than the defined and add an error.
							}
							break;
						case 'max':
							if(strlen($value) > $rule_value){
								$this->addError("{$disp_text} must be a maximum of {$rule_value} characters.");	// Same as above except will be greater than.
							}
							break;
						case 'matches':
							if($value != $source[$rule_value]){
								$this->addError("The passwords you entered did not match.");	// Matches for the password so check is the value not equal to the source value. Sorce checks the rule value.
							}
							break;
						case 'unique':
							$check = $this->_db->get($rule_value, array($item, '=', $value));	// Using the database wrapper to get, the constuctor at the top has stored this. Could use username but item is more accurate in this case.
							if($check->count()){												// Count checks the value in the database.
								$this->addError("{$disp_text} has been taken.");				// Outputs the item has been already used.
							}
							break;
						
					}
				}
			}
		}

		/*if($validation-> passed()){
			echo 'Passed';
		} else {
			print_r($validation->errors());
		}*/

		if(empty($this->_errors)){					// Check to see if our errors array is empty.
			$this->_passed = true;
		}

		return $this;
	}

	private function addError($error){				// Method to add a eerror to the errors array.
		$this->_errors[] = $error;
	}

	public function errors(){						// Method which will return the list of errors.
		return $this->_errors;
	}

	public function passed(){						// Method to return passed.
		return $this->_passed;
	}
}
?>
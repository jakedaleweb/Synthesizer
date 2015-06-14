<?php

class Validate {
	
	/*
	This function will receive an array of messages, eg:
	
	$messages[firstNameError] = Please enter your name
	$messages[lastNameError] = Please enter your name
	
	We need to loop through them all to if there are messages, or if it's just a series of blanks
	*/
	public function checkErrors($messages) {
		$errors = false;
		foreach($messages as $message) {
			if($message) {
				// If there is even one message, we have errors
				$errors = true;
			}
		}
		return $errors;
	}
	
	/*
	All of the following functions receive something to validate, and a value to indicate whether or not it is required. If the value is required but is not present, we need to tell the user to enter the value. However, if they enter something, we would still like to validate even if it is not required.
	*/
	
	public function checkName($name, $required) {
		if($required && !$name) {
			return 'This field can not be blank';
		} else {
			$pattern = '/^[a-zA-Z-\'\. ]{2,20}$/';
			if(!preg_match($pattern, $name)) {
				return 'Please enter correctly';
			} else {
				return false;
			}
		}
	}


	public function checkUsername($name, $required) {
		if($required && !$name) {
			return 'This field can not be blank';
		} else {
			$pattern = '/^[a-zA-Z0-9-\'\. ]{2,20}$/';
			if(!preg_match($pattern, $name)) {
				return 'Please enter correctly';
			} elseif (strlen($name) > 20) {
				return 'your username can not be longer than 20 characters';
			} else {
				return false;
			}
		}
	}

	public function checkPassSignUp($password, $passConfirm, $required) {
		if($required && !$password) {
			return 'You must enter the SAME password in BOTH fields';
		} else if($password != $passConfirm) {
			return 'Your passwords must match';
		} else {
			return false;
		}
	}

	public function checkTitlePage($name, $required) {
		if($required && !$name) {
			return 'Please enter a page title';
		} else {
			$pattern = '/^[a-zA-Z-\'\. ]{2,20}$/';
			if(!preg_match($pattern, $name)) {
				return 'Please enter a page title correctly';
			} else {
				return false;
			}
		}
	}

	public function checkTown($town, $required) {
		if($required && !$town) {
			return 'Please enter your town';
		} else {
			$pattern = '/^[a-zA-Z-\'\. ]{2,20}$/';
			if(!preg_match($pattern, $town)) {
				return 'Please enter your town correctly';
			} else {
				return false;
			}
		}
	}
	
	public function checkEmail($emailAddress, $required) {
		if($required && !$emailAddress) {
			return 'Please enter your email address';
		} else {
			$pattern = '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.(([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|museum|name))$/';
			if(!preg_match($pattern, $emailAddress)) {
				return 'Please enter your email address correctly';
			} else {
				return false;
			}
		}
	}

	public function checkRadio($radio, $required) {
		if($required && !$radio) {
			return 'Please select one';
		} else {
			return false;
		}
	}
	
	public function checkProductName($name, $required) {
		if($required && !$name) {
			return 'Please enter a product name';
		} else {
			$pattern = '/^[a-zA-Z-\'\. \"]{2,60}$/';
			if(!preg_match($pattern, $name)) {
				return 'Please enter the product name correctly';
			} else {
				return false;
			}
		}
	}

	public function checkAddress($address, $required) {
		if($required && !$address) {
			return 'Please enter your postal address';
		} else {
			$pattern = '/^[a-zA-Z-\'\. \"]{2,60}$/';
			if(!preg_match($pattern, $address)) {
				if(!$required) {
					return false;
				}
				return 'Please enter your postal address correctly';
			} else {
				return false;
			}
		}
	}

	public function checkDescription($description, $required) {
		if($required && !$description) {
			return 'Please enter the product description';
		} else {
			if(strlen($description) > 1000) {
				if(!$required) {
					return false;
				}
				return 'Your description must be less than 1000 characters';
			} else {
				return false;
			}
		}
	}
	
	public function checkPrice($price, $required) {
		if($required && !$price) {
			return 'Please enter the price';
		} else {
			if(!is_numeric($price)) {
				return 'Please enter a number';
			} else {
				return false;
			}
		}
	}

	public function checkPhone($phone, $required) {
		if($required && !$phone) {
			return 'Please enter your phone number';
		} else {
			if(!is_numeric($phone)) {
				return 'Please enter a number';
			} else {
				return false;
			}
		}
	}
	
	public function checkFile($file, $required) {
		if($required && $file['error'] == 4) {
			return 'Please select a file to upload';
		} else {
			return false;
		}
		// At this point you may also want to check the filename or possible the file type
	}
	
	public function checkPageTitle($title, $required) {
		if($required && !$title) {
			return 'Please enter a title';
		} else {
			$pattern = '/^[a-zA-Z-\'\. ]{2,40}$/';
			if(!preg_match($pattern, $title)) {
				return 'Please enter the page title correctly';
			} else {
				return false;
			}
		}
	}
	
}

?>
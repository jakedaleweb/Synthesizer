<?php

include('databaseClass.php');
include('validateClass.php');

class Model extends database {
	
	// This function checks whether or not the user is an admin
	public function checkAdmin() {
		if($_SESSION['access'] != 'admin') {
			return false;
		} else {
			return true;
		}
	}

	//gets a users rank and returns it, if this is called on the users profile page it will also set whaty rank is in the SESSION
	public function processRank($userID, $loggedIn){
		$ranks = $this -> getRank($userID);
		//combine the numbers
		$ranksTotal = $ranks['webDesign'] + $ranks['webDev'] + $ranks['graphicDesign'] + $ranks['animation'];
		//only set the rank session if the user is logged in
		if($loggedIn){
			if($ranksTotal <= 50){
			$_SESSION['rank'] = 'beginner';
			} else if($ranksTotal >= 50 && $ranksTotal <= 100){
				$_SESSION['rank'] = 'adept';
			} else {
				$_SESSION['rank'] = 'expert';
			}
		}
		return $ranks;
	}
	
	// This function will log out a user, simply by unsetting the relevant elements in the session
	public function processLogout() {
		unset($_SESSION['userID']);
		unset($_SESSION['username']);
		unset($_SESSION['profilePic']);
		unset($_SESSION['userType']);
		unset($_SESSION['access']);
		unset($_SESSION['rank']);
		unset($_SESSION['email']);
	}

	//called from the log in view after a log in attempt has taken place, returns either false or an array of errors
	public function validateLogIn(){
		//instantiate validate object
		$validate = new Validate();
		//get data from the form
		extract($_POST);
		//initialise array to store messages
		$messages = array();
		//validate username(req)
		$messages['userNameError'] = $validate -> checkUsername($username, true);

		$errors = $validate -> checkErrors($messages);
		if($errors) {
			//if there were errors, return the array
			return $messages;
		} else {
			//validate success!
			//proceed 
			return false;
		}
	}

	//called after a  new job has been submitted from a clients profile, returns either false or an array of error msgs
	public function validateAddEditJob(){
		//Instantiate the validate object
		$validate = new Validate();
		//get data from the form inputs
		extract($_POST);
		//Initialise array to store messages
		$messages = array();
		$messages['priceError'] = $validate -> checkPrice($price, true); 
		$messages['nameError'] = $validate -> checkName($name, true);
		$messages['categoryError'] = $validate -> checkRadio($category, true);
		$messages['aboutError'] = $validate -> checkDescription($about, true);
		$messages['rankError'] = $validate -> checkRadio($rank, true);
		$errors = $validate -> checkErrors($messages);
		if($errors) {
			//if there were errors, return the array
			return $messages;
		} else {
			//validate success!
			//proceed 
			return false;
		}
	}


	//called after sign up form has been submitted in the home view, returns either an array of messages or false
	public function validateSignUp() {
		//Instantiate the validate object
		$validate = new Validate();
		//get data from the form inputs
		extract($_POST);
		//Initialise array to store messages
		$messages = array();
		//validate userName(required)
		$messages['userNameError'] = $validate -> checkUsername($username, true);
		//validate passwords (required)
		$messages['passwordError'] = $validate -> checkPassSignUp($password, $passConfirm, true);
		//validate email (required)
		$messages['emailError'] = $validate -> checkEmail($email, true);
		//validate profile picture (required)
		$messages['imageError'] = $validate -> checkFile($_FILES['image'], true);
		$errors = $validate -> checkErrors($messages);
		if($errors) {
			//if there were errors, return the array
			return $messages;
		} else {
			//validate success!
			//proceed 
			return false;
		}
	}

	//called once the refine search button is pressed on the search page, checks to see if both radios have been selected, returns relevant errors or false
	public function validateSearch() {
		//Instantiate the validate object
		$validate = new Validate();
		//get data from the form inputs
		extract($_POST);
		//Initialise array to store messages
		$messages = array();
		$messages['categoryError'] = $validate -> checkRadio($category, true);
		$messages['sortByError'] = $validate -> checkRadio($sortBy, true);
		$errors = $validate -> checkErrors($messages);
		if($errors) {
			//if there were errors, return the array
			return $messages;
		} else {
			//validate success!
			//proceed 
			return false;
		}
	}

	//this function uploads and resizes an image then calls the database function to insert the query, it is called from the home view
	public function processAddUser() {
		//instantiate upload class
		include('uploadClass.php');
		$inputName = 'image'; //name of the input inside the form
		$validTypes = array('image/jpeg', 'image/png', 'image/gif'); //mime types allowed in input file
		$filePath = 'images/users'; //folder file path, not the name of the image itself
		$upload = new Upload($inputName, $validTypes, $filePath);
		//call the isUploaded function
		$uploaded = $upload -> isUploaded();
		//if uploaded resize the image
		if($uploaded['success']){
			include('resizeClass.php');
			//use basename to grab file name
			$uploadedFileName = basename($uploaded['filepath']);
			//create the path for the resized image
			$thumb = $filePath . '/' . $uploadedFileName;
			$resizer = new Resize($uploaded['filepath']);
			$resized = $resizer -> resizeInRatio(400, $thumb);
			echo $resized;
			if($resized){
				$thumb = $filePath . '/thumbnails/' . $uploadedFileName;
				$resized = $resizer -> resizeInRatio(60, $thumb);
				$this -> createUser($uploadedFileName);
			} else {
				return false;
			}
		}
	}


	//sends an email either from the applicant or to the successful applicant
	//recieves the email address to send to and whether or not it is from an applicant or to one(true/false)
	public function emailUser($email_to, $fromApplicant){
		//if this email is from an applicant
		if($fromApplicant){
			$email_subject = 'Someone has applied for your job on Synthesizer:'.$job['title'];
			$email_message = $_SESSION['username'].' has applied for your job on Synthesizer, click to see their <a href="index.php?page=profile&amp;userID='.$_SESSION['userID'].'">profile</a>';
		//if this email is to the successful applicant
		} else {
			$email_subject = 'You have been selected to complete '.$_SESSION['username'].'\'s job on Synthesizer';
			$email_message = $_SESSION['username'].' has selected you to complete their job on Synthesizer, you can now contact them directly at '.$_SESSION['email'].'';
		}
		// create email headers
		$headers = 'From: synthesizer@synthesizer.com';
		$headers .= 'Reply-To: '.$_SESSION['email'].'';
		mail($email_to, $email_subject, $email_message, $headers); 
	}
		
		
	


	
}


?>
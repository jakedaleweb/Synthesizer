<?php
// config.php defines the constants DBHOST, DBUSER, DBPASS and DBNAME
include('../config.php');

class database {
	
	//declare class properties
	private $dbc;
	
	public function __construct() {
		// Attempt to connect to the database
		try {
			$this -> dbc = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
			if(mysqli_connect_errno()) {
				throw new Exception('Could not connect to the database');
			}
		} catch(Exception $e) {
			die($e -> getMessage());
		}
	}
	/* QUERY PROCESSING SECTION */

	//called when a job is clicked on from the search screen, returns an array of info about one job
	public function getJob($jobID) {
		$query = "SELECT jobID, userID, category, title, price, description, timeStamp FROM jobs WHERE jobID='$jobID'";
		$data = $this -> singleSelectQuery($query);
		return $data;
	}

		//called when the search page is first loaded, returns an array of jobs that corresond with the users rank
	public function getJobs(){
		//make life easy
		$rank = $_SESSION['rank'];
		// Query
		$query = "SELECT jobID, userID, category, title, price, description, timeStamp FROM jobs WHERE rank='$rank'";
		$data = $this -> multiSelectQuery($query);
		return $data;
	}

	//called from the profile, shows the jobs posted by that user
	public function getUsersJobs($userID){
		$query = "SELECT jobID, userID, category, title, price, description, timeStamp FROM jobs WHERE userID='$userID'";
		$data = $this -> multiSelectQuery($query);
		return $data;
	}

	//called if the user refines their job search, returns a user customized array of jobs
	public function refinedGetJobs(){
		//extract info from the form
		extract($_POST);
		if($sortBy == 'highestPrice'){
			$orderBy = 'price';
			$ascOrDesc = 'DESC';
		} elseif($sortBy == 'lowestPrice') {
			$orderBy = 'price';
			$ascOrDesc = 'ASC';
		} elseif($sortBy == 'newestJobs') {
			$orderBy = 'timeStamp';
			$ascOrDesc = 'ASC';
		} elseif($sortBy == 'oldestJobs') {
			$orderBy = 'timeStamp';
			$ascOrDesc = 'DESC';
		}

		$query = "SELECT jobID, userID, category, title, price, description, timeStamp FROM jobs WHERE category='$category' ORDER BY $orderBy $ascOrDesc";
		$data = $this -> multiSelectQuery($query);
		return $data;
	}

	//called wherever there is a need for information about a certain user, can gety the information either from being sent the userID or the username
	public function getUser($userInfo) {
		//if userInfo is numeric then it is the userID
		if(is_numeric($userInfo)){
			$query = "SELECT userID, profilePic, username, email FROM users WHERE userID='$userInfo'";
		//else it is a string and will be their username
		} else {
			$query = "SELECT userID, profilePic, username, email FROM users WHERE username='$userInfo'";
		}
		$data = $this -> singleSelectQuery($query);
		return $data;
	}

	//called when the profile page is loaded, returns the intergers relating to the users rank
	public function getRank($userID){
		$query = "SELECT webDesign, graphicDesign, webDev, animation FROM rank WHERE userID='$userID'";
		$data = $this -> singleSelectQuery($query);
		return $data;
	}

	//called wherever the username for a job is needed as it is not stored in the jobs table, returns username
	public function getUserName($userID){
		$query = "SELECT username FROM users WHERE userID='$userID'";
		$data = $this -> singleSelectQuery($query);
		return $data;
	}

	//called on the job page to check if there is already a succesfull applicant
	public function checkForSuccessfulApplicants($jobID){
		$query = "SELECT userID FROM success WHERE jobID='$jobID'";
		$data  = $this -> singleSelectQuery($query);
		return $data;
	}

	//called after succesful validation of the addJob form on a users profile
	public function addJobToDatabase() {
		//get the addProduct form data in the form of key = value (form name = form input)
		$post = $this -> sanitize();
		extract($post);
		$userID = $_SESSION['userID'];
		$timestamp = time();
		//query
		$query = "INSERT INTO jobs VALUES(NULL,'$userID','$category','$name','$price', '$rank', '$about', '$timestamp')";
		$data  = $this -> updateDeleteQuery($query);
		return $data;
	}

	//called after the user has submitted the feedback form on the job page, returns true or false
	public function addFeedbackToDB($userID, $category){
		extract($_POST);
		switch($category){
			case 'Graphic Design':
				$category = 'graphicDesign';
				break;
			case 'Web Design':
				$category = 'webDesign';
				break;
			case 'Web Development':
				$category = 'webDev';
				break;
			case 'Animation':
				$category = 'animation';
				break;
		}
		//get the users Ranks
		$ranks = $this -> getRank($userID);
		//update the rank which corresponds to the job
		$updatedRank = $ranks[$category] + $feedback;
		//insert new rank
		$updateQuery = "UPDATE `rank` SET `$category`=$updatedRank WHERE `userID` = $userID";
		$data = $this -> updateDeleteQuery($updateQuery);
		return $data;
	}

	//called once the job poster has selected their designer on the job page, returns success of query
	public function addSuccessfulApplicantToDB($username, $jobID){
		$getIDQuery = "SELECT userID FROM users WHERE username='$username'";
		$username = $this -> singleSelectQuery($getIDQuery);
		$userID = $username['userID'];
		$query = "INSERT INTO success VALUES(NULL,'$jobID','$userID')";
		$data = $this -> updateDeleteQuery($query);
		return $data;
	}

	//adds a new row to the application table and returns true or false depending on its success
	public function processApplication($jobID){
		$userID = $_SESSION['userID'];
		$query = "INSERT INTO applications VALUES(NULL,'$userID','$jobID')";
		$data  = $this -> updateDeleteQuery($query);
		return $data;
	}

	//called when a new page is loaded, returns all the page info
	public function getPageInfo($page) {
		$query = "SELECT title, description, keywords, actionAnchor, actionImage, page FROM pages WHERE page='$page'";
		$data = $this -> singleSelectQuery($query);
		return $data;
	}

	//called when a new job page is loaded, returns all the applicants for a job
	public function getApplicants($jobID) {
		$query = "SELECT userID FROM applications WHERE jobID='$jobID'";
		$data = $this -> multiSelectQuery($query);
		return $data;
	}

	//called after the user has logged in on the log in page and also after a new user has signed up, sets up the session after the input has been checked against the database
	public function checkLogin() {
		$post = $this -> sanitize();
		extract($post);
		// Hash the password that was entered into the form (if it was correct, it will match the hashed password in the database)
		$password = sha1($password);
		// Query
		$query = "SELECT username, userID, profilePic, access, email FROM users WHERE username='$username' AND password='$password'";
		$data = $this -> singleSelectQuery($query);
		if($data){
			//set up the session
			$_SESSION['userID'] = $data['userID'];
			$_SESSION['username'] = $data['username'];
			$_SESSION['profilePic'] = $data['profilePic'];
			$_SESSION['userType'] = $data['userType'];
			$_SESSION['access'] = $data['access'];
			//redirects to their profile
			header('location: index.php?page=profile&userID='.$_SESSION['userID']);
		} else {
			return false;
		}
		
	}

	//called either once a job has been completed and the work ranked or when a user chooses to delete a job
	public function deleteJobFromDB($jobID) {
		$jobDeleteQuery = "DELETE FROM jobs WHERE jobID='$jobID'";
		$jobDeleted = $this -> updateDeleteQuery($jobDeleteQuery);
		//if the job is deleted
		if($jobDeleted){
			$applicantsDeleteQuery = "DELETE FROM applications WHERE jobID='$jobID'";
			$applicantsDeleted = $this -> updateDeleteQuery($applicantsDeleteQuery);
			if($applicantsDeleted){
				$successDeleteQuery = "DELETE FROM success WHERE jobID='$jobID'";
				$successDeleted = $this -> updateDeleteQuery($successDeleteQuery);
				return $successDeleted;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	//this function is called when the edit job form is submitted, it updates values in the DB and returns its success
	public function editJobInDB($jobID){
		$post = $this -> sanitize();
		extract($post);
		$query = "UPDATE `jobs` SET `category` = '$category', `price` = '$price', `rank` = '$rank', `description` = '$about', `title` = '$name' WHERE `jobID` = $jobID";
		$data = $this -> updateDeleteQuery($query);
		return $data;
	}

	//called after user has signed up on the home page
	public function createUser($profilePic){
		//get the signUp form data in the form of key = value (form name = form input)
		$post = $this -> sanitize();
		extract($post);
		//encrypt password
		$password = sha1($password);
		//query
		$query = "INSERT INTO users VALUES(NULL,'$username','$password','$email', '$profilePic', 'user')";
		$data  = $this -> updateDeleteQuery($query);
		if($data){
			//run query to get the userID of user just created
			$userQuery = "SELECT userID from users WHERE username='$username'";
			//store result in variable, should return only one string so it wont be an array
			$user = $this -> singleSelectQuery($userQuery); 
			$userID = $user['userID'];
			$rankQuery = "INSERT INTO rank VALUES(NULL, '$userID', 1, 1, 1, 1)";
			$result = $this -> updateDeleteQuery($rankQuery);
			return $result;
		} else {
			return false;
		}
	}

	//============================
	
	//called every time a query only needs to retrieve info from one row, returns either the relevant data in an array or false
	public function singleSelectQuery($query) {
		$result = $this -> dbc -> query($query);
		if(!$result) {
			// Something wrong with the query
			die('singleSelect - Query error!');
		} else {
			// Check if rows were returned
			if($result -> num_rows > 0) {
				// Yes.. convert to an array and send back
				$data = $result -> fetch_assoc();
				return $data;
			} else {
				// No... page not found
				return false;
			}
		}
	}	
	
	//called when a query needs to retrieve multiple rows, returns either those rows in a multi-dimensional array or false
	public function multiSelectQuery($query) {
		// Run the query
		$result = $this -> dbc -> query($query);
		if(!$result) {
			// Something wrong with the query
			die('multiSelect - Query error! ');
		} else {
			// Check if rows were returned
			if($result -> num_rows > 0) {
				// Yes.. convert to an master array and send back
				$masterArray = array();
				//add the results into the master array
				while($row = $result -> fetch_assoc()) {
					$masterArray[] = $row;
				}	
				return $masterArray;
			} else {
				// No... page not found
				return false;
			}
		}
	}

	//This function will return a query result - either true or false (affected or not) 
	//this function also holds the operation to add, insert and delete rows within the database
	public function updateDeleteQuery($query) {
		//run the query
		$result = $this -> dbc -> query($query);
		//test the query
		if(!$result) {
			die('updateDeleteQuery - error'.$query);
		} else {
			if($this -> dbc -> affected_rows > 0) {
				return true;
			} else {
				return false;
			}
		}
	}

	//sanitizes input to make SQL query more secure
	public function sanitize(){
		$post = array();
		foreach($_POST as $key => $val){
			if(!get_magic_quotes_gpc()){
				$post[$key] = mysql_real_escape_string($val);
			}
		}
		return $post;
	}





		
}
?>
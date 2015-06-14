<?php

class Delete extends View {
	public function displayContent(){
		//check if the user is admin
		$admin = $this -> model -> checkAdmin();
		//get job details from the get
		$job = $this -> model -> getJob($_GET['jobID']);
		//if user is not logged in
		if(!isset($_SESSION['userID'])){
			//redirect to login
			header('location: index.php?page=login');
		}
		//if the person on this page didnt post and isnt admin the job to be deleted
		if($_SESSION['userID'] != $job['userID'] && !$admin){
			//redirect to their profile
			header('location: index.php?page=profile&userID='.$_SESSION['userID'].'');
		//the logged in user did post this job
		} else {
			$html  = '<div id="content">'. "\n";
			$html .= '<h2>Are you sure you want to delete this job?</h2>';
			//include relevant files
			include('classes/formsClass.php');
			//instantiate object
			$forms = new Forms($this -> model);
			//show decision form
			$html .= $forms -> deleteForm();
			if(isset($_POST['delete']) && $_POST['delete'] == 'Yes'){
				$deleteSuccess = $this -> model -> deleteJobFromDB($job['jobID']);
				if($deleteSuccess){
					$html  = '<div id="content">'. "\n";
					$html  .= '<p>Job Deleted</p>';
				} else {
					$html  = '<div id="content">'. "\n";
					$html .= '<p>Sorry, there was an issue deleting your job</p>';
				}
			} elseif($_POST['delete'] == 'No') {
				//redirect to their profile
				header('location: index.php?page=profile&userID='.$_SESSION['userID'].'');
			}
		}
		$html .= '</div>' . "\n";
		return $html;
	}		
}



?>
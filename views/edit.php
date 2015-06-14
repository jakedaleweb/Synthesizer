<?php

class Edit extends View {
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
			$html  = '<div id="content-right">'. "\n"; 
			//include relevant files
			include('classes/formsClass.php');
			//instantiate object
			$forms = new Forms($this -> model);
			//if form hasnt been submitted
			if(!isset($_POST['editJob'])){
				//show edit job form
				$html .= $forms -> 	addEditJob('edit');
			} else {
				//validate form
				$messages = $this -> model -> validateAddEditJob();
				//if there are any errors
				if($messages){
					//display form again with messages
					$html .= $forms -> addEditJob('edit', $messages);
				} else {
					$result = $this -> model -> editJobInDB($job['jobID']);
					if($result){
						$html .= '<p>Job succesfully edited!</p>';
					} else {
						$html .= '<p>Sorry, either there was a problem editing that job, or you did not change anything</p>';
					}
				}
			}
			
		}
		$html .= '</div>' . "\n";
		$html .= '</div>' . "\n";
		return $html;
	}		
}



?>
<?php

class Search extends View {

	public function displayContent(){
		//check if the current user is admin
		$admin = $this -> model -> checkAdmin();
		$html  = '<div id="content" class="cf">' . "\n";
		$html .= '<div id="content-left" class="profile">' . "\n";
		//include relevant files
		include('classes/formsClass.php');
		//instantiate object
		$forms = new Forms($model);
		if(!isset($_POST['jobSearch'])){
			$html .= $forms -> jobSearch();
			//$jobs is set to default values based on rank
			$jobs = $this -> model -> getJobs();
		} else {
			//validate
			$messages = $this -> model -> validateSearch();
			//if there are messages from validation
			if($messages){
				//display form again with messages
				$html .= $forms -> jobSearch($messages);
				//jobs is set again to default values
				$jobs = $this -> model -> getJobs();
			//no messages
			} else {
				$html .= $forms -> jobSearch();
				//$jobs is set to the values set by the user in the form
				$jobs = $this -> model -> refinedGetJobs();
			}
		}
			
		$html .= '</div>' . "\n";
		$html .= '<div id="content-right">' . "\n";
		$html .= '<h2>Available Jobs</h2>' . "\n";
		if($jobs){
			foreach($jobs as $job){
				//check that the job has no succesful applicants
				$jobTaken = $this -> model -> checkForSuccessfulApplicants($job['jobID']);
				if(!$jobTaken){
					//get the username for the job
					$username = $this -> model -> getUserName($job['userID']);
					$html .= '<div class="job">' . "\n";
					$html .= '<div class="price">' . "\n";
					$html .= '<p><span class="dollar">$</span>'.$job['price'].'</p>	' . "\n";
					$html .= '</div>' . "\n";
					$html .= '<div class="jobInfo">' . "\n";
					$html .= '<a href="index.php?page=job&amp;job='.$job['jobID'].'"><h3>'.$job['title'].'</h3></a> ' . "\n";
					$html .= '<p class="date">'.date("d-m-Y", $job['timeStamp']).'</p>' . "\n";
					$html .= '<h4>'.$username['username'].'</h4>' . "\n";
					$html .= '<ul>' . "\n";
					$html .= '<li>'.$job['category'].'</li>' . "\n";
					$html .= '</ul>' . "\n";
					$html .= '</div>' . "\n";
					//if user is admin displauy delete button
					if($admin){
						$html .= '<a class="deleteButton" href="index.php?page=delete&amp;jobID='.$job['jobID'].'" />Delete |</a>';
						$html .= '<a class="deleteButton" href="index.php?page=edit&amp;jobID='.$job['jobID'].'" />| Edit</a>';
					}
					$html .= '</div>' . "\n";
				}
				
			}
		} elseif(!isset($_POST['jobSearch'])) {
			$html .= '<h3>No jobs available right now matching your rank, please use the advance search options to the left to continue your search.</h3>';
		} else {
			$html .= '<h3>No jobs available right now matching your search.</h3>';
		}
		
		
		$html .= '</div>' . "\n";
		$html .= '</div>' . "\n";
		return $html;
	}
		
}


?>
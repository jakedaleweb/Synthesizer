<?php

class Job extends View {

	public function displayContent(){
		//check if current user is admin
		$admin = $this -> model -> checkAdmin();
		//get the information aout the specific job from the get superglobal
		$job = $this -> model -> getJob($_GET['job']);
		if(!$job){
			header('location: index.php?page=search');
		}
		//get the user info for the job
		$user = $this -> model -> getUser($job['userID']);
		//get the rank info for said user
		$ranks = $this -> model -> processRank($job['userID'], false);
		//work out the total rank for this user
		$ranksTotal = $ranks['webDesign'] + $ranks['webDev'] + $ranks['graphicDesign'] + $ranks['animation'];
		if($ranksTotal <= 50){
			$userRank = 'beginner';
		} else if($ranksTotal >= 50 && $ranksTotal <= 100){
			$userRank = 'adept';
		} else {
			$userRank = 'expert';
		}
		$html  = '<div id="content" class="cf">' . "\n";
		//show info about the person that posted the job
		$html .= '<div id="content-left" class="profile">' . "\n";
		$html .= '<h2>Posted By:</h2>' . "\n";
		$html .= '<div id="profilePic">' . "\n";
		$html .= '<a href="index.php?page=profile&amp;userID='.$user['userID'].'" ><img src="images/users/'.$user['profilePic'].'" alt="profile picture"></a>' . "\n";
		$html .= '</div>' . "\n";
		$html .= '<div id="profileRank">' . "\n";

		//determine which rank banner to display
		if($userRank == 'expert'){
			$html .= '<img src="images/pages/expert.png" alt="user rank"/>' . "\n";
		} else if($userRank == 'adept'){
			$html .= '<img src="images/pages/adept.png" alt="user rank"/>' . "\n";
		} else if($userRank == 'beginner'){
			$html .= '<img src="images/pages/beginner.png" alt="user rank"/>' . "\n";
		}
		$html .= '</div>' . "\n";
		$html .= '<div id="ranksContainer">' . "\n";
		$html .= '<h2>'.$user['username'].'</h2>' . "\n";
		$html .= '<div class="scores">' . "\n";
		$html .= '<p>'.$ranks['graphicDesign'].'</p>' . "\n";
		$html .= '<p>Graphic Design</p>' . "\n";
		$html .= '</div>' . "\n";
		$html .= '<div class="scores">' . "\n";
		$html .= '<p>'.$ranks['webDesign'].'</p>' . "\n";
		$html .= '<p>Web Design</p>' . "\n";
		$html .= '</div>' . "\n";
		$html .= '<div class="scores">' . "\n";
		$html .= '<p>'.$ranks['webDev'].'</p>' . "\n";
		$html .= '<p>Web Development</p>' . "\n";
		$html .= '</div>' . "\n";
		$html .= '<div class="scores">' . "\n";
		$html .= '<p>'.$ranks['animation'].'</p>' . "\n";
		$html .= '<p>Animation</p>' . "\n";
		$html .= '</div>' . "\n";
		$html .= '</div>' . "\n";
		$html .= '</div>' . "\n";
		$html .= '<div id="content-right">' . "\n";
		
		//display job info
		$html .= '<div class="job">' . "\n";
		$html .= '<div class="price">' . "\n";
		$html .= '<p><span class="dollar">$</span>'.$job['price'].'</p>	' . "\n";
		$html .= '</div>' . "\n";
		$html .= '<div class="jobInfo">' . "\n";
		$html .= '<h3>'.$job['title'].'</h3>' . "\n";
		$html .= '<p class="date">'.date("d-m-Y", $job['timeStamp']).'</p>' . "\n";
		$html .= '<h4>'.$user['username'].'</h4>' . "\n";
		$html .= '<ul>' . "\n";
		$html .= '<li>'.$job['category'].'</li>' . "\n";
		$html .= '</ul>' . "\n";
		$html .= '<p>'.$job['description'].'</p>';
		$html .= '</div>' . "\n";//<---------end job info
		if($_SESSION['userID'] == $user['userID'] || $admin){
			$html .= '<a class="deleteButton" href="index.php?page=delete&amp;jobID='.$job['jobID'].'" />Delete |</a>';
			$html .= '<a class="deleteButton" href="index.php?page=edit&amp;jobID='.$job['jobID'].'" />| Edit</a>';
		}
		$html .= '</div>' . "\n";//<---------end job
		//get applicants
		$applicants = $this -> model -> getApplicants($job['jobID']);
		//if there are applicants
		if($applicants){
			//set a variable to true or false depending on if the user has applied for this job already
			foreach($applicants as $applicant){
				if($_SESSION['userID'] == $applicant['userID']){
					$alreadyApplied = true;
				} else {
					$alreadyApplied = false;
				}
			}
		}
		//include relevant files
		include('classes/formsClass.php');
		//instantiate object
		$forms = new Forms($this -> model);
		//if the viewer is logged in
		if(isset($_SESSION['userID'])){
			//if the user did not post this job
			if($_SESSION['userID'] != $job['userID']){
				//check for succesful applicants
				$jobTaken = $this -> model -> checkForSuccessfulApplicants($job['jobID']);
				//if there is already a successful applicant
				if($jobTaken){
					$html .= '<p>Sorry someone has already started working on this job</p>';
				//if the apply button is pushed
				}elseif(isset($_POST['apply'])){
					//process apply for job
					$applied = $this -> model -> processApplication($job['jobID'], true);
					//send an email alerting the job poster that they have an applicant if processing was a success
					if($applied){
						$this -> model -> emailUser($user['email'], true);
					}
					$html .= '<h2>You have applied for this job</h2>';
				//if its not pushed but the user IS in the array display a message saying you have already applied
				} elseif($alreadyApplied){
					$html .= '<h2>You have already applied for this job</h2>';
				//if the button has not been pushed and the user is not in the applicants array display the form
				} else {
					$html .= $forms -> applicationForm();
				}
			//if the user did post this job
			} else {
				//find if a designer has been chosen
				$jobTaken = $this -> model -> checkForSuccessfulApplicants($job['jobID']);
				//if the job hasnt been taken
				if(!$jobTaken && !isset($_POST['applicantChoose'])){
					//display the applicants
					$html .= $forms -> applicantsForm($applicants, $job);
				//if the form has been submitted
				} elseif (isset($_POST['applicantChoose'])) {
					//add successful applicant to db
					$addUserSuccess = $this -> model -> addSuccessfulApplicantToDB($_POST['applicantChoose'], $job['jobID']);
					//if adding the user to the db worked
					if($addUserSuccess){
						//get successful applicant for this job
						$successfulApplicant = $this -> model -> getUser($_POST['applicantChoose']);
						//display their username and a link to their profile
						$html .= '<h2>Successful Applicant</h2>' . "\n";
						$html .= '<a class="successfulApplicant" href="index.php?page=profile&amp;userID='.$successfulApplicant['userID'].'">'.$successfulApplicant['username'].'</a>';
						$html .= $forms -> feedbackForm();
					} else {
						$html .= '<p>Sorry there was an error processing the selected user, please refresh and try again</p>';
					}
				//if the job is taken
				} elseif ($jobTaken){
					//get successful applicant for this job
					$successfulApplicant = $this -> model -> getUser($jobTaken['userID']);
					//display their username and a link to their profile
					$html .= '<h2>Successful Applicant</h2>' . "\n";
					$html .= '<a class="successfulApplicant" href="index.php?page=profile&amp;userID='.$successfulApplicant['userID'].'">'.$successfulApplicant['username'].'</a>';
					//display an input form to send feedback on this users work
					$html .= $forms -> feedbackForm();
					//use feedback to update the successful applicants rank
					if(isset($_POST['sendFeedback'])){
						$successfullFeedback = $this -> model -> addFeedbackToDB($successfulApplicant['userID'], $job['category']);
						//if feedback is successfully posted
						if($successfullFeedback){
							//delete job from DB
							$jobDeleted = $this -> model -> deleteJobFromDB($job['jobID']);
							if($jobDeleted){
								//redirect to the users profile
								header('location: index.php?page=profile&userID='.$_SESSION['userID'].'');
							} else {
								$html .= '<p>there was a problem deleting this job</p>';
							}
							
						}
					}
				}
			}
		} else {
			$html .= '<p>Sorry, you need to <a href="index.php?page=login">login</a> to apply for this job</p>';
		}
		
		$html .= '</div>' . "\n";//<---------end content right
		$html .= '</div>' . "\n";//<---------end content
		return $html;
	}
		
}


?>
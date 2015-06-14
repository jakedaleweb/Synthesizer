<?php

class Profile extends View {
	public function displayContent(){
		//if the user is not logged in go to the login page
		//check if the current user is admin
		$admin = $this -> model -> checkAdmin();
		if(!isset($_SESSION['username'])){
			header('Location: index.php?page=login');
		} else {
			//get the information aout the specific user from the get superglobal
			$user = $this -> model -> getUser($_GET['userID']);
			//get the rank info for said user
			if($_SESSION['userID'] == $user['userID']) {
				//if this profile is the user's set up their rank in the session
				$ranks = $this -> model -> processRank($user['userID'], true);
			} else {
				//otherwise just retrieve the numbers
				$ranks = $this -> model -> processRank($user['userID'], false);
			}
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
			$html .= '<h2>Profile</h2>' . "\n";
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
			//include relevant files
			include('classes/formsClass.php');
			//instanitate forms object
			$forms = new Forms($model);
			//if this profile is the person's who is logged in
			if($_SESSION['userID'] == $user['userID']){
				$html .= $forms -> displayContentForm();
				//if the user hasnt decided what content to see, or has opted to see the form show them the form
				if(!isset($_POST['showContent']) || $_POST['showContent'] == 'Add Job'){
					//if submit hasnt been pressed
					if(!isset($_POST['postJob'])){
					//display form
					$html .= $forms -> addEditJob('add');
					//if submitted
					} else {
						//validate form
						$messages = $this -> model -> validateAddEditJob();
						//if there are any errors
						if($messages){
							//display form again with messages
							$html .= $forms -> addEditJob('add', $messages);
						} else {
							$result = $this -> model -> addJobToDatabase();
							if($result){
								$html .= '<h4>Job succesfully added!</h4>';
							} else {
								$html .= '<h4>Sorry, there was a problem creating that job, please try again.</h4>';
							}
						}
					}
				//if the user chose to show jobs 
				} elseif($_POST['showContent'] == 'My Jobs'){
					$html .= '<h2>My Jobs</h2>';
					//get their jobs
					$jobs = $this -> model -> getUsersJobs($user['userID']);
					//display them along with a delete button
					if($jobs){
						foreach($jobs as $job){
						$html .= '<div class="job">' . "\n";
						$html .= '<div class="price">' . "\n";
						$html .= '<p><span class="dollar">$</span>'.$job['price'].'</p>	' . "\n";
						$html .= '</div>' . "\n";
						$html .= '<div class="jobInfo">' . "\n";
						$html .= '<a href="index.php?page=job&amp;job='.$job['jobID'].'"><h3>'.$job['title'].'</h3></a> ' . "\n";
						$html .= '<p class="date">'.date("d-m-Y", $job['timeStamp']).'</p>' . "\n";
						$html .= '<h4>'.$user['username'].'</h4>' . "\n";
						$html .= '<ul>' . "\n";
						$html .= '<li>'.$job['category'].'</li>' . "\n";
						$html .= '</ul>' . "\n";
						$html .= '</div>' . "\n";
						$html .= '<a class="deleteButton" href="index.php?page=delete&amp;jobID='.$job['jobID'].'" />Delete |</a>';
						$html .= '<a class="deleteButton" href="index.php?page=edit&amp;jobID='.$job['jobID'].'" />| Edit</a>';
						$html .= '</div>' . "\n";
						}
					} else {
						$html .= '<p>You havent posted any jobs yet, why not try now?</p>';
						$html .= $forms -> addEditJob('add');
					}
				}
			//this is not the profile of the person logged in 
			} else {
				//get their jobs
				$html .= '<h2>'.$user['username'].'\'s Jobs</h2>';
				$jobs = $this -> model -> getUsersJobs($user['userID']);
				//if the have posted jobs show them here
				if($jobs){
					foreach($jobs as $job){
					$html .= '<div class="job">' . "\n";
					$html .= '<div class="price">' . "\n";
					$html .= '<p><span class="dollar">$</span>'.$job['price'].'</p>	' . "\n";
					$html .= '</div>' . "\n";
					$html .= '<div class="jobInfo">' . "\n";
					$html .= '<a href="index.php?page=job&amp;job='.$job['jobID'].'"><h3>'.$job['title'].'</h3></a> ' . "\n";
					$html .= '<p class="date">'.date("d-m-Y", $job['timeStamp']).'</p>' . "\n";
					$html .= '<h4>'.$user['username'].'</h4>' . "\n";
					$html .= '<ul>' . "\n";
					$html .= '<li>'.$job['category'].'</li>' . "\n";
					$html .= '</ul>' . "\n";
					$html .= '</div>' . "\n";
					if($admin){
						$html .= '<a class="deleteButton" href="index.php?page=delete&amp;jobID='.$job['jobID'].'" />Delete |</a>';
						$html .= '<a class="deleteButton" href="index.php?page=edit&amp;jobID='.$job['jobID'].'" />| Edit</a>';
					}
					$html .= '</div>' . "\n";
					}
				//if they havent posted any jobs
				} else {
					$html .= "<p>".$user['username']." hasn't posted any jobs yet</p>";
				}
			}
			
			
			$html .= '</div>' . "\n";
			$html .= '</div>' . "\n";
		
			return $html;
		}
	}
		
}



?>
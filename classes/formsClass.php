<?php

class Forms {
	
	// Some forms may require access to the database, so it could be handy to the have the model available
	public function __construct($model) {
		$this -> model = $model;
	}

	//displays a button for a user to apply for a job
	public function applicationForm(){
		$form  = '<div>';
		$form .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">' ."\n";
		$form .= '<input type="submit" name="apply" value="apply for this job">' ."\n";
		$form .= '</form>' ."\n";
		$form .= '</div>';
		return $form;
	}

	//displays an input to rate work out of 5
	public function feedbackForm($messages=''){
		if(is_array($messages)) {
			extract($messages);
		}
		$form  = '<form method="post" id="feedbackForm" action="'.$_SERVER['REQUEST_URI'].'">' . "\n";
		$form .= '<label for="feedback">Please rate the work when it is completed:</label>' . "\n";
		$form .= '<input type="number" id="feedback" name="feedback" min="1" max="5" value="1">' . "\n";
		$form .= '<span>'.$feedbackError.'</span>';
  		$form .= '<input type="submit" class="chooseContentButton" name="sendFeedback" value="Rate">' . "\n";
  		$form .= '</form>' . "\n";
  		return $form;
	}

	//called from the logIn view, returns the log in form which can contain error messages also
	public function logIn($messages=''){
		if(is_array($messages)) {
			extract($messages);
		}
		$form  = '<div id="logInForm">' . "\n";
		$form .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">' . "\n";
		$form .= '<input type="text" id="username" name="username" placeholder="username" value="'. htmlentities(stripslashes($_POST['username']), ENT_QUOTES).'" />' . "\n";
		$form .= '<span>'.$userNameError.'</span>';
		$form .= '<input type="password" id="password" name="password" placeholder="password" />' . "\n";
		$form .= '<span>'.$passwordError.'</span>';
		$form .= '<input type="submit" name="logIn" id="logIn" value="Log In" />' . "\n";
		$form .= '</form>' . "\n";
		$form .= '</div>' . "\n";
		return $form;
	}

	//display advanced search options
	public function jobSearch($messages=''){
		if(is_array($messages)) {
			extract($messages);
		}
		$form  = '<form id="jobSearch" method="post" action="'.$_SERVER['REQUEST_URI'].'">' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<h2>Job Type</h2>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="radio" id="graphicDesign" name="category"	value="Graphic Design" />' . "\n"; 
		$form .= '<label for="graphicDesign">Graphic Design</label>' . "\n";
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="radio" id="webDesign" name="category"	value="Web Design" />' . "\n";
		$form .= '<label for="webDesign">Web Design</label>' . "\n";
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="radio" id="webDev" name="category"	value="Web Development" />' . "\n";
		$form .= '<label for="webDev">Web Development</label>' . "\n";
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="radio" id="animation" name="category"	value="Animation" />' . "\n";
		$form .= '<label for="animation">Animation</label>' . "\n";
		$form .= '</div>' . "\n";
		$form .= '<span>'.$categoryError.'</span>';
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<h2>Sort By</h2>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="radio" id="lowestPrice" name="sortBy" value="lowestPrice" />' . "\n";
		$form .= '<label for="lowestPrice">Lowest Price</label>' . "\n";
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="radio" id="highestPrice" name="sortBy" value="highestPrice" />' . "\n";
		$form .= '<label for="highestPrice">Highest Price</label>' . "\n";
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="radio" id="newestJobs" name="sortBy" value="newestJobs" />' . "\n";
		$form .= '<label for="newestJobs">Newest Jobs</label>' . "\n";
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="radio" id="oldestJobs" name="sortBy" value="oldestJobs" />' . "\n";
		$form .= '<label for="oldestJobs">Oldest Jobs</label>' . "\n";
		$form .= '</div>' . "\n";
		$form .= '<span>'.$sortByError.'</span>';
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="submit" name="jobSearch" value="Search" />' . "\n";
		$form .= '</div>' . "\n";
		$form .= '</form>' . "\n";
		return $form;
	}
	
	// This function returns the sign up form, and optionally receives an array of error messages. it is called from the home view
	public function signUp($messages=''){
		if(is_array($messages)) {
			extract($messages);
		}
		$form  = '<form id="signUpForm" method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">' . "\n";
		$form .= '<h2>Sign Up</h2>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<label for="username">User Name: </label>' . "\n";
		$form .= '<input type="text" id="username" name="username" value="'. htmlentities(stripslashes($_POST['username']), ENT_QUOTES).'"/>' . "\n";
		$form .= '<span>'.$userNameError.'</span>';
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<label for="password">Password: </label>' . "\n";
		$form .= '<input type="password" id="password" name="password" />' . "\n";
		$form .= '<span>'.$passwordError.'</span>';
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<label for="passConfirm">Confirm Password: </label>' . "\n";
		$form .= '<input type="password" id="passConfirm" name="passConfirm" />' . "\n";
		$form .= '<span>'.$passwordError.'</span>';
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<label for="email">Email: </label>' . "\n";
		$form .= '<input type="email" id="email" name="email" value="'.htmlentities(stripslashes($_POST['email']), ENT_QUOTES).'"/>' . "\n";
		$form .= '<span>'.$emailError.'</span>';
		$form .= '</div>' . "\n";
		$form .= '<div>' ."\n";
		$form .= '<input type="hidden" name="MAX_FILE_SIZE" value="10000000">';
		$form .= '<p><label for="image">Profile Picture: </label>';
		$form .= '<input type="file" name="image" value="image" />';
		$form .= '<p>*For best results, upload a square image</p>';
		$form .= '<span class="error">'.$imageError.'</span>'."\n";
		$form .= '</div>' ."\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="submit" id="signUp" value="Sign Me Up!" name="signUp" />' . "\n";
		$form .= '</div>' . "\n";
		$form .= '</form>' . "\n";
		return $form;
	}

	//displays two buttons to choose what content a user sees on their profile
	public function displayContentForm(){
		$form  = '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">' . "\n";
		$form .= '<input class="chooseContentButton" type="submit" name="showContent" value="My Jobs">' . "\n";
		$form .= '<input class="chooseContentButton" type="submit" name="showContent" value="Add Job">' . "\n";
		$form .= '</form>' . "\n";
		return $form;
	}

	//dislays yes or no option to delete a job
	public function deleteForm(){
		$form  = '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">' . "\n";
		$form .= '<input class="chooseContentButton" type="submit" name="delete" value="Yes">' . "\n";
		$form .= '<input class="chooseContentButton" type="submit" name="delete" value="No">' . "\n";
		$form .= '</form>' . "\n";
		return $form;
	}
		
	//this function returns the form needed to add jobs to the databas, it is caled from a clients profile
	public function addEditJob($mode, $messages=''){
		if(is_array($messages)) {
			extract($messages);
		}
		// If this is to be an edit form, we want the current (coming from the database) values to appear in the form
		if($mode == 'edit' && !isset($_POST['editJob'])) {
			$job = $this -> model -> getJob($_GET['jobID']);
			$priceValue 	 	= $job['price'];
			$titleValue 		= $job['title'];
			$descriptionValue	= $job['description'];
		} else {
			// If the form is for adding a product, or if the form 	has been submitted, the form should be blank/sticky
			$priceValue 		= $_POST['price'];
			$titleValue 		= $_POST['name'];
			$descriptionValue	= $_POST['about'];
		}
		if($mode == 'add'){
			$form  = '<h2>Add a new Job</h2>' . "\n";
		} else {
			$form = '<h2>Edit this Job</h2>' . "\n";
		}
		
		$form .= '<form id="newJobForm" method="post" action="'.$_SERVER['REQUEST_URI'].'">' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="text" id="jobPrice" name="price" maxlength="2" placeholder="$00" value="'.htmlentities(stripslashes($priceValue), ENT_QUOTES).'"/>' . "\n";
		$form .= '<span class="error">'.$priceError.'</span>'."\n";
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<label for="jobName">Job Title</label>' . "\n";
		$form .= '<input type="text" id="jobName" name="name" placeholder="Your job title" value="'.htmlentities(stripslashes($titleValue), ENT_QUOTES).'"/>' . "\n";
		$form .= '<span class="error">'.$nameError.'</span>'."\n";
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="radio" name="category" id="graphicDesignRadio" value="Graphic Design" />' . "\n";
		$form .= '<label for="graphicDesignRadio">Graphic Design</label>' . "\n";
		$form .= '<input type="radio" name="category" id="webDesignRadio" value="Web Design" />' . "\n";
		$form .= '<label for="webDesignRadio">Web Design</label>' . "\n";
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="radio" name="category" id="webDevRadio" value="Web Development" />' . "\n";
		$form .= '<label for="webDevRadio">Web Development</label>' . "\n";
		$form .= '<input type="radio" name="category" id="animationRadio" value="animation" />' . "\n";
		$form .= '<label for="animationRadio">Animation</label>' . "\n";
		$form .= '<span class="error">'.$categoryError.'</span>'."\n";
		$form .= '</div>' . "\n";
		$form .= '<label for="aboutJob">About this job</label>' . "\n";
		$form .= '<div>' ."\n";
		$form .= '<textarea cols="60" rows="10" id="aboutJob" name="about" placeholder="Describe your job">'.htmlentities(stripslashes($descriptionValue), ENT_QUOTES).'</textarea>' . "\n";
		$form .= '<span class="error">'.$aboutError.'</span>'."\n";
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="radio" name="rank" id="beginnerRadio" value="beginner" />' . "\n";
		$form .= '<label for="beginnerRadio">Beginner</label>' . "\n";
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="radio" name="rank" id="adeptRadio" value="adept" />' . "\n";
		$form .= '<label for="adeptRadio">Adept</label>' . "\n";
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		$form .= '<input type="radio" name="rank" id="expertRadio" value="expert" />' . "\n";
		$form .= '<label for="expertRadio">Expert</label>' . "\n";
		$form .= '<span class="error">'.$rankError.'</span>'."\n";
		$form .= '</div>' . "\n";
		$form .= '<div>' . "\n";
		//if page is edit show different button
		if($mode == 'edit'){
			$form .= '<input type="submit" value="Edit Job" name="editJob" />' . "\n";
		} else {
			$form .= '<input type="submit" value="Post Job" name="postJob" />' . "\n";
		}
		$form .= '</div>' . "\n";
		$form .= '</form>' . "\n";
		return $form;
	}

	//displays each applicant for a job as a submit button with their relevant experience
	public function applicantsForm($applicants, $job){
		$form  = '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">' . "\n";
		$form .= '<h2>Applicants</h2>' . "\n";
		if($applicants){
			$form .= '<p>(Clicking on a name will choose that user to complete your job, please choose carefully)</p>' ."\n";
			foreach($applicants as $applicant){
				//get info on each applicant
				$applicantDetails = $this -> model -> getUser($applicant['userID']);
				//get users Rank
				$usersRank = $this -> model -> getRank($applicantDetails['userID']);
				//display the names of each applicant which link to their profile plus radio to choose one
				$form .= '<input type="submit" name="applicantChoose" class="chooseContentButton" value="'.$applicantDetails['username'].'"/>' . "\n";
				switch($job['category']){
					case 'Graphic Design':
						$form .= '<p>Graphic Design: '.$usersRank['graphicDesign'].'</p>' . "\n";
						break;
					case 'Web Design':
						$form .= '<p>Web Design: '.$usersRank['webDesign'].'</p>' . "\n";
						break;
					case 'Web Development':
						$form .= '<p>Web Development: '.$usersRank['webDev'].'</p>' . "\n";
						break;
					case 'Animation':
						$form .= '<p>Animation: '.$usersRank['animation'].'</p>' . "\n";
						break;
				}
			}
		} else {
			$form .= '<p>(There are currently no applicants for this job)</p>';
		}
		
		$form .= '</form>';
		return $form;
	}
}

?>
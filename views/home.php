<?php

class Home extends View {

	//called from the base class returns html
	protected function displayContent() {
		$html  = '<div id="content" class="cf">' . "\n";
		$html .= '<div id="content-left" >' . "\n";
		//include relevant files
		include('classes/formsClass.php');
		//instantiate forms class
		$forms = new Forms($this -> model);
		//if sign up hasnt been pressed
		if(!isset($_SESSION['userID'])){
			if(!isset($_POST['signUp'])){
				//display the form
				$html .= $forms -> signUp();
			//if it has been pressed
			} else {
				//validate
				$messages = $this -> model -> validateSignUp();
				//if there are messages from validation
				if($messages){
					//display form again with messages
					$html .= $forms -> signUp($messages);
				//no messages
				} else {
					//this function adds user to the databse and resizes their profile pic
					$this -> model -> processAddUser();
					//sign in the new user
					$this -> model -> checkLogin();
				}
			}
		}
		$html .= '</div>' . "\n";
		$html .= '<div id="content-right" class="home">' . "\n";
		$html .= '<h2>Welcome</h2>' . "\n";
		$html .= '<p>Synthesizer is the number one website to start networking today. <br />
			Sign up today to start completing jobs for cash! <br />
			or post the jobs that you need done now to find yourself a design expert!</p>' . "\n";

		$html .= '<h2>News</h2>' . "\n";
		$html .= '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>' . "\n";
		$html .= '</div>' . "\n";
		$html .= '</div>' . "\n";
		return $html;
	}

}

?>
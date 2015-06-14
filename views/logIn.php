<?php

class LogIn extends View {

	//called from the base class returns html
	protected function displayContent() {
		if(isset($_SESSION['userID'])){
			header('location: index.php?page=profile&userID='.$_SESSION['userID'].'');
		} else {
			//include relevant files 
		include('classes/formsClass.php');
		//instantiate forms class
		$forms = new Forms($this -> model);
		//if log in hasnt been pressed
		if(!isset($_POST['logIn'])){
			//display form
			$html = $forms -> logIn();
		//if it has been pressed
		} else {
			//validate
			$messages = $this -> model -> validateLogIn();
			//if there are errors
			if($messages){
				//show the form again with errors
				$html = $forms -> logIn($messages);
			//if it has been pressed and no errors			
			} else{
				$success = $this -> model -> checkLogin();
				if(!$success){
					$html = $forms -> logIn();
				}
			}
		}
		return $html;
		}
	}

}

?>
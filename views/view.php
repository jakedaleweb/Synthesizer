<?php

/*
This class is responsible for building the page, using parts that are common to all pages in the site.
It receives page information that was read from the database on index.php
*/

abstract class View {
	
	//declare class propertiea
	protected $pageInfo;
	protected $model;

	// Receive page info and the model object
	public function __construct($info, $model) {
		// Assign values to the class properties
		$this -> pageInfo = $info;
		$this -> model = $model;
		// Run the function responsible for building the page
		$this -> displayPage();
	}
	
	// This function will build the page by calling 3 functions
	protected function displayPage() {
		$html  = $this -> displayHead();
		$html .= $this -> displayHeader();
		$html .= $this -> displayContent();	// Defined in a sub class
		$html .= $this -> displayFooter();
		echo $html;
	}
	
	// This function is called by the constructor in this class and returns the html for the header
	private function displayHead() {
		$html  = '<!DOCTYPE html>' . "\n";
		$html .= '<html>' . "\n";
		$html .= '<head>' . "\n";
		$html .= '<title>'.$this -> pageInfo['title'].'</title>' . "\n";
		$html .= '<meta charset="UTF-8">' . "\n";
		$html .= '<meta name="keywords" content="'.$this -> pageInfo['keywords'].'"/>' . "\n";
		$html .= '<meta name="description" content="'.$this -> pageInfo['description'].'"/>' . "\n";
		$html .= '<link rel="stylesheet" type="text/css" href="css/default.css" />' . "\n";
		$html .= '<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />' . "\n";
		$html .= '<link href="http://fonts.googleapis.com/css?family=Roboto:500,400italic,100,700italic,300,700,500italic,100italic,300italic,400" rel="stylesheet" type="text/css" />' . "\n";
		$html .= '</head>' . "\n";
		$html .= '<body>' . "\n";
		return $html;
	}

	//this function is called in the construct and returns the html to display the head
	private function displayHeader(){
		//SOMETHING WRONG HERE.
		$html  = '<header class="cf">' . "\n";
		$html .= '<div id="logo">' . "\n";
		$html .= '<a href="index.php?page=home"><img src="images/pages/logoSmall.png" alt="Synthesizer" />' . "\n";
		$html .= '<h1>Synthesizer</h1></a> ' . "\n";
		$html .= '</div>' . "\n";
		//if the user is signed in, create the droip down menu
		if(isset($_SESSION['userID'])){
			$html .= '<ul id="mobile-list" class="hidden">' . "\n";
			$html .= '<li><a href="index.php?page=profile&amp;userID='.$_SESSION['userID'].'">Profile</a></li>' . "\n";
			$html .= '<li><a href="index.php?page=search">Search</a></li>' . "\n";
			$html .= '<li><a href="index.php?page=logout">Sign Out</a></li>' . "\n";
			$html .= '</ul>' . "\n";
		}
		$html .= '<div id="userInfo">' . "\n";
		$html .= '<div id="user">' . "\n";
		//if the user a user is signed in..
		if(isset($_SESSION['username'])){
			//if the length of their username is less than 10
			if(strlen($_SESSION['username']) < 10){
				//show their username in the appropriatte place
				$html .= '<p>'.$_SESSION['username'].'</p>' . "\n";
			} else {
				//show their username up to 6 characters and add '..' to show it has been shortenned here
				$html .= '<p>'.substr($_SESSION['username'], 0, 6).'..</p>' . "\n";
			}
			$html .= '<p id="display-button">&#9660;</p>' . "\n";
		//if the user is not logged in
		} else {
			//instead of a drop down menu display a link to the login page
			$html .= '<p><a href="index.php?page=login">Log in</a></p>' . "\n";
		}
		$html .= '</div>' . "\n";
		$html .= '<div id="userImage">' . "\n";
		//if user is signed in
		if(isset($_SESSION['profilePic'])){
			//display their profile pic
			$html .= '<a href="index.php?page=profile&amp;userID='.$_SESSION['userID'].'"><img src="images/users/thumbnails/'.$_SESSION['profilePic'].'" alt="profile picture" /></a>' . "\n";
		}
		$html .= '</div>' . "\n";
		$html .= '<div id="userRank">' . "\n";
		if(isset($_SESSION['rank'])){
			if($_SESSION['rank'] == 'expert'){
				$html .= '<img src="images/pages/expert.png" alt="user rank"/>' . "\n";
			} else if($_SESSION['rank'] == 'adept'){
				$html .= '<img src="images/pages/adept.png" alt="user rank"/>' . "\n";
			} else if($_SESSION['rank'] == 'beginner'){
				$html .= '<img src="images/pages/beginner.png" alt="user rank"/>' . "\n";
			}
		}
		$html .= '</div>' . "\n";
		$html .= '</div>' . "\n";
		$html .= '</header>' . "\n";
		$html .= '<div id="action-link">' . "\n";

		//manipulating anchor link to take to custom page (actionAnchor within the DB)
		//adding condition to add syntax: $_SESSION['userID'] - alternative to DB entry
		if($this->pageInfo['page'] == 'search') {
			$html .= '<a href="'.$this -> pageInfo['actionAnchor'].'&amp;userID='.$_SESSION['userID'].'" ><i class="'.$this -> pageInfo['actionImage'].'"></i></a>';
		} else {
			$html .= '<a href="'.$this -> pageInfo['actionAnchor'].'"><i class="'.$this -> pageInfo['actionImage'].'"></i></a>';
		}
		//$html .= '<a href="'.$this -> pageInfo['actionAnchor'].'"><i class="'.$this -> pageInfo['actionImage'].'"></i></a>' . "\n";
		$html .= '</div>' . "\n";
		return $html;
	}
	
	// This function must be present in a sub class, it is the reason this class is abstract, if there is an abstract function the class MUST be abstract
	abstract protected function displayContent();
	
	// This function is called by the constructor in this class and returns the html for the footer
	private function displayFooter() {
		$html  = '<footer>' . "\n";
		$html .= '<ul>' . "\n";
		$html .= '<li><a href="index.php?page=help">help</a></li>' . "\n";
		$html .= '<li><a href="index.php?page=contact">contact</a></li>' . "\n";
		$html .= '<li><a href="http://www.facebook.com"><i class="fa fa-facebook"></i></a></li>' . "\n";
		$html .= '<li><a href="http://www.twitter.com"><i class="fa fa-twitter"></i></a></li>' . "\n";
		$html .= '<li><a href="http://www.instagram.com"><i class="fa fa-instagram"></i></a></li>' . "\n";
		$html .= '</ul>' . "\n";
		$html .= '</footer>' . "\n";
		$html .= '<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>' . "\n";
		$html .= '<script src="js/burger.js"></script>' . "\n";
		$html .= '</body>' . "\n";
		$html .= '</html>' . "\n";
		return $html;
	}
	
}






?>
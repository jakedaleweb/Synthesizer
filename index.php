<?php

//removes notice messages from PHP internal compiler
error_reporting(E_ALL ^ E_NOTICE);

// Begin the session
session_start();

// Include common files
include('views/view.php');
include('classes/modelClass.php');


// Instantiate the model class (this will, by extension, connect to the database)
$model = new Model();

// Determine what page we are on. If no page specified, set it to home
if(isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = 'home';
}

// Get page info from database
$pageInfo = $model -> getPageInfo($page);
// If no page found, we should quit
if(!$pageInfo) {
	header('HTTP/1.0 404 Not Found');
	die('Page not found!');
}

// Create the appropriate view based on the value of $page
switch($page) {
	// EG: If the page is 'home', instantiate the 'home view' class and send it the page info and model
	case 'home':
		include('views/home.php');
		new Home($pageInfo, $model);
		break;
	case 'login':
		include('views/logIn.php');
		new LogIn($pageInfo, $model);
		break;
	case 'logout':
		include('views/logOut.php');
		new LogOut($pageInfo, $model);
		break;
	case 'profile':
		include('views/profile.php');
		new Profile($pageInfo, $model);
		break;
	case 'search':
		include('views/search.php');
		new Search($pageInfo, $model);
		break;
	case 'job':
		include('views/job.php');
		new Job($pageInfo, $model);
		break;
	case 'delete':
		include('views/delete.php');
		new Delete($pageInfo, $model);
		break;
	case 'edit':
		include('views/edit.php');
		new Edit($pageInfo, $model);
		break;
}

?>
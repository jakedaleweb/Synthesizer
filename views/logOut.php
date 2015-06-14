<?php

class LogOut extends View {

	//called from the base class returns html
	protected function displayContent() {
		$this -> model -> processLogout();
		header('Location: index.php?page=home');
	}

}

?>
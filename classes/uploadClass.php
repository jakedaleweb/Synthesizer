<?php

	//this class will recieve the name of a file input, an array of valid file types and a path to a folder where the uploaded file should be stored
	//the function isUploaded will return true or false depeding on whether or not the file was uploaded so we need to return a message as well, we will return an array that will unclude the result and an error message
	class Upload {

		//class properties
		private $inputName;
		private $validTypes;
		private $filepPath;

		function __construct($inputName, $validTypes, $folder) {
			$this -> inputName 		= $inputName;
			$this -> validTypes 	= $validTypes;
			$this -> folder 		= $folder;	
		}

		public function isUploaded(){
			$result = array(); //this array will collect info throughout the function

			//check for errors
			if($_FILES[$this -> inputName]['error']){
				switch($_FILES[$this -> inputName]['error']) {
					case 1:
						$result['message'] = 'Your file is too large for PHP';
						break;
					case 2:
						$result['message'] = 'Your file is too large for the form (max 10mb)';
						break;
					case 3:
						$result['message'] = 'Sorry your file was only partially uploaded';
						break;
					case 4:
						$result['message'] = 'no file uploaded';
						break;
				}
				$result['success'] = false;
			} else {
				//check if the file is the right file type
				$valid = in_array($_FILES[$this -> inputName]['type'], $this -> validTypes);
				if($valid){
					//check if the file reached its temporary location
					$uploaded = is_uploaded_file($_FILES[$this -> inputName]['tmp_name']);
					if($uploaded){
						//specify a file path
						$filepath = $this -> folder.'/'.uniqid().$_FILES[$this -> inputName]['name'];
						//move from the temp location to the specified file path
						$moved = move_uploaded_file($_FILES[$this -> inputName]['tmp_name'], $filepath);
						if($moved) {
							//double check if the file exists where it was moved to
							$exists = file_exists($filepath);
							if($exists) {
								$result['message'] = 'The file has succesfully been uploaded! <br/>';
								$result['success'] = true;
								$result['filepath'] = $filepath;
							} else {
								$result['message']  = 'Sorry the file did not reach the destination folder <br/>';
								$result['success'] = false;
							}
						} else {
							$result['message']  = 'Sorry, the file could not be moved <br/>';
							$result['success'] = false;
						}
					} else {
						$result['message'] = 'Sorry the file did not reach the server <br/>';
						$result['success'] = false;
					}
				} else {
					$result['message'] ='please select an IMAGE file (.jpg, .png or .gif)';
					$result['success'] = false;
				}
			}
			return $result;
		}


		public function displayForm(){
			//enctype!
			$form  = '<form method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">';
			//include a maximum file size(10mb)
			$form .= '<p><label for="image">Upload an image </label>';
			$form .= '<input type="file" name="image" id="image"></p>';
			$form .= '<input type="hidden" name="MAX_FILE_SIZE" value="10000000">';
			$form .= '<input type="submit" name="upload" value="upload">';
			$form .= '</form>';
			return $form;
		}

	}
?>
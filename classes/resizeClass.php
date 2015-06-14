<?php
	class Resize {
		//declare class properties
		private $originalFile;

		public function __construct($originalFile){
			$this -> originalFile = $originalFile;
		}

		public function resizeInRatio($dimension, $destination){
			//does the file exist on the server
			$exists = file_exists($this -> originalFile);
			if(!$exists){
				return false;
			}

			//get the dimensions of the original image
			$info = getimagesize($this -> originalFile);

			//get the original image dimensions
			$originalWidth	= $info[0];
			$originalHeight = $info[1];

			//calculate the dimensions of the new image
			// if($originalHeight > $originalHeight){
			// 	//dimension is sent when this function is calleds
			// 	$newHeight 	= $dimension;
			// 	$newWidth 	= ($originalWidth * $newHeight)/$originalHeight;
			// } else {
			// 	$newWidth 	= $dimension;
			// 	$newHeight 	= ($originalHeight * $newWidth)/$originalWidth;
			// }

			$newHeight = $dimension;
			$newWidth = $dimension;
			//go ahead and resize/save
			$resized = $this -> saveImage($newWidth, $newHeight, $originalWidth, $originalHeight, $info, $destination);

			if($resized){
				return true;
			} else {
				return false;
			}
		}

		//this functin does the resizing/saving of the images
		public function saveImage($newWidth, $newHeight, $originalWidth, $originalHeight, $info, $destination) {
			//create a canvas using the new dimensions
			$image = imagecreatetruecolor($newWidth, $newHeight);
			//open up the image with the appopriatte function
			switch ($info['mime']){
				case 'image/jpeg';
					$originalImage = imagecreatefromjpeg($this -> originalFile);
					break;
				case 'image/png';
					$originalImage = imagecreatefrompng($this -> originalFile);
					break;
				case 'image/gif';
					$originalImage = imagecreatefromgif($this -> originalFile);
					break;
			}
			//copy the original image to the new image, imagecopyresampled(canvas, paint, x, y(where to place on cavnas), x, y(which point to place), etc)
			imagecopyresampled($image, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
			//run the appropriatte save function based on the image type
			switch ($info['mime']){
				case 'image/jpeg';
					$resized = imagejpeg($image, $destination);
					break;
				case 'image/png';
					$resized = imagepng($image, $destination);
					break;
				case 'image/gif';
					$resized = imagegif($image, $destination);
					break;
			}
			//clear the memory
			imagedestroy($image);
			//return result
			if($resized){
				return true;
			} else {
				return false;
			}
		}




	}



?>
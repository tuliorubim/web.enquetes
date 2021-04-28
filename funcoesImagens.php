<?php
trait ImageFunctions {
	/*
	The function Thumb() creates a resized copy of a given image file. The new image size is always less than or equal to its original size.
	
	The parameter $file receives $_FILES['fieldname'], that is, it receives a file sent from a form input whose name is filename.
	The parameter $tsrc is the server address where the original image is stored added to its name. The name of this image is $_FILES['fieldname']['name'].
	The parameter $tdest is the server address where the resized (thumbnail) image will be stored added to its name.
	$n_width is the width of the thumbnail image that will be generated by the function below. */
	public function Thumb($file, $tsrc, $tdest, $n_width){
		/* The variable $ret is the variable that will be returned by this function. It receives TRUE if the thumbnail image was successfully created and FALSE otherwise. */
		$ret = FALSE;
		// The variable $image_type gets the substring of $file["type"] that is after '/', that is, it gets the extension of the image sent through a form.	
		$bar_pos = strpos($file["type"], '/');
		$image_type = substr($file["type"], $bar_pos+1);
		/* So that a tumbnail image can be created by this function, the original file must be a jpeg, a png or a gif image, otherwise, no thumbnail image will be crated. Also, the original image must exist in the server before the execution of this function, so that it can work.*/ 
		if (file_exists($tsrc) && in_array($image_type, array("jpeg", "png", "gif"))) {
			// The function below creates an image to be used by PHP from the original image file, according to its extension.
			eval('$im=imagecreatefrom'.$image_type.'($tsrc);');
			$width = imagesx($im); // $width receives the original picture width.
			$height = imagesy($im); // $height receives the original picture height.
			/* The new picture width stored in the parameter $n_width cannot be bigger than the original picture width. If a bigger new width was provided to this function, then $n_width must be equal to the original width. */
			if ($n_width > $width) {
				$n_width = $width;
			}	
			/* The new image height must be smaller than the original image height in the same proportion the new image width is smaller than the original image width. */
			$n_height = $height*$n_width/$width;
			$newimage = imagecreatetruecolor($n_width,$n_height); // The variable $newimage receives a new PHP image with the new sizes.
			/* Below, $newimage receives a resized copy of the original image $im, and its new sizes are $n_width and $n_height. $ret receives TRUE if the function imagecopyresized() works successfully and FALSE if it fails.*/
			$ret = imagecopyresized($newimage,$im,0,0,0,0,$n_width,$n_height,$width,$height); 
			//header("Content-type: image/".$image_type);
			// The new image is saved below in the destination file whose name and address are in $tdest, according to its extension.
			eval('image'.$image_type.'($newimage, $tdest);');
			// The function below change the permissions of the file $tsrc so that anybody can execute, write and read it.
			chmod("$tsrc",0777);
		}// end of thumbnail creation
		return $ret;
	}
	public function cut_image ($file, $filedest, $x, $y, $newwidth, $newheight) {
		$ret = FALSE;	
		$file = ''.$file; // Is $file is not a string, it is turned into a string here.
		$file = strtolower($file); // All the chars of $file that are lower case letters become upper case.
		/* The variable $image_type gets the three last chars of $file if the char before them is a dot, but if the dot is before the four last chars os $file, they will be assigned to $image_type. This way, $image_type gets the extension of the file whose name is $file.*/
		$image_type = '';
		$dot_pos1 = strlen($file) - 4;
		$dot_pos2 = strlen($file) - 5;
		if ($file[$dot_pos1] === '.') $image_type = substr($file, strlen($file)-3);
		elseif ($file[$dot_pos2] === '.') $image_type = substr($file, strlen($file)-4);
		// The variable $image_extensions receives an array of the most common image extensions.
		$image_extensions = array('jpg', 'jpeg', 'jpe', 'jfif', 'gif', 'png');
		$jpeg_extensions = array('jpg', 'jpeg', 'jpe', 'jfif');
		if (file_exists($file) && in_array($image_type, $image_extensions)) {
			// The function below creates an image to be used by PHP from the original image file, according to its extension.
			if (in_array($image_type, $jpeg_extensions)) $source=imagecreatefromjpeg($file);
			else eval('$source=imagecreatefrom'.$image_type.'($file);');
			$thumb = imagecreatetruecolor($newwidth, $newheight);
			
			// Resize
			$ret = imagecopy($thumb, $source, 0, 0, $x, $y, $newwidth, $newheight);
			if (in_array($image_type, $jpeg_extensions)) $source=imagejpeg($thumb, $filedest);
			else eval('image'.$image_type.'($thumb, $filedest);');
			chmod("$file",0777);
		}
		return $ret;
	}
	public function exibir_imagem ($logo, $max_width) {
		global $status;
		$size = '';
		$image_type = '';
		for ($i = strlen($logo)-1; $logo[$i] !== '.'; $i--) 
			$image_type = $logo[$i].$image_type;
		$image_type = strtolower($image_type);
		if (in_array($image_type, array('jpg', 'jfif', 'pjpeg', 'pjp'))) $image_type = 'jpeg';
		if (function_exists("imagecreatefrom$image_type")) {
			eval('$im=imagecreatefrom'.$image_type.'($logo);');
			if (imagesx($im) > $max_width) $size = "width='$max_width'";
			echo "<img src='$logo' $size>";
		} else {
			$status = "N&atilde;o h&aacute; suporte para imagens do tipo $image_type.";
		}
	}
}
?>
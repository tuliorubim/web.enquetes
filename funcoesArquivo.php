<?php
//PODE DAR ERRO O COMANDO ABAIXO
trait FileFunctions {	
	// FILE FUNCTIONS
	
	/* The function save_file() is used to facilitate the creation of new text files or their updating. The parameter $content is a text that will be saved in the current file by this function. The parameter $name is the file name, that is, its name added to its server address, and $mode is the way the current file will be accessed by the function fopen(), which opens it if it exists. The default mode is 'a', which opens a file with the only porpose of writing a text at its end, and tries to create it if it doesn't exist.  */
	public function save_file ($content, $name, $mode='a') {
		$dest = $name;
		/* If the mode is 'x' or 'x+' and if the file whose name is $name exists, it will be deleted, because fopen() fails to access a file if it exists and the access mode to it is 'x' or 'x+'. This procedure is performed to avoid this failure.  */
		if ($mode[0] === 'x' && file_exists($dest)) {
			unlink($dest);
		} 
		$handle = fopen ($dest, $mode); // $handle receives the open file whose name is $dest ($name).
		$ret = fwrite ($handle, $content); // The content is written in the open file, according to the mode of access to it.
		fclose($handle); // Finally the open file is closed.
		return $ret; // This function returns the result of the file writing execution, that is, the number of bytes written or FALSE, if the execution fails.	
	}
	
	/* The function open_file() opens a text file and returns its content. This content will be taken from the text file whose name and server address are in the parameter $name. The parameter $mode is the way how the file whose name is $name will be accessed. The default mode is 'r', which states that the current file will be opened only for reading.*/
	public function open_file($name, $mode='r') {
		global $status;
		/* If the file doesn't exist in the server, a failure message will be shown through the global variable $status and this function will do nothing else. */
		if (!file_exists($name)) {
			$status = "There is no file named '$name'.";
			return;
		}
		$dest = "$name"; // The file name (with its server address) is assigned to the variable $dest.
		$h = NULL; 
		$h = fopen ($dest, $mode); // The variable $h variable receives the open file.
		$content = fread($h, filesize($dest)); // The function fread() reads the content from the open file and assigns it to the variable $content.
		fclose($h); // The file is closed.
		return $content; // The file content is returned.
	}
	
	/* The function below deletes a file whose name and server address are in $name. It tests if the file exists. If not, an error message will be shown, because it's not possible to delete a file that doesn't exist. */
	public function delete_file($name) {
		global $status;
		if (file_exists($name)) {
			unlink($name);	
		} else {
			$status = "There is no file named '$name'.";
		}
	}
	
	/* The function below returns true if the file whose name is $s is an image file, and returns false otherwise. */
	public function is_image($s) {
		$is_image = false; // This is the boolean variable that will be returned by this function.
		$s = ''.$s; // Is $s is not a string, it is turned into a string here.
		$s = strtoupper($s); // All the chars of $s that are lower case letters become upper case.
		/* The variable $ext_s gets the three last chars of $s if the char before them is a dot, but if the dot is before the four last chars os $s, they will be assigned to $ext_s. This way, $ext_s gets the extension of the file whose name is $s.*/
		$ext_s = '';
		$dot_pos1 = strlen($s) - 3;
		$dot_pos2 = strlen($s) - 4;
		$dot_pos3 = strlen($s) - 5;
		if ($s[$dot_pos1] === '.') $ext_s = substr($s, strlen($s)-2);
		elseif ($s[$dot_pos2] === '.') $ext_s = substr($s, strlen($s)-3);
		elseif ($s[$dot_pos3] === '.') $ext_s = substr($s, strlen($s)-4);
		// The variable $image_extensions receives an array of the most common image extensions.
		$image_extensions = array('BMP', 'DIB', 'JPG', 'JPEG', 'JPE', 'JFIF', 'GIF', 'TIF', 'TIFF', 'PNG');
		// If $ext_s is one of the image extensions in $image_extensions, then the current file is an image file, and $is_image receives true.
		if (in_array($ext_s, $image_extensions)) {
			$is_image = true;
		}
		return $is_image;
	}
	
	// FUNCTIONS FOR USE IN AN AREA THIS FRAMEWORK DOESN'T FOCUS ON.
	
	//$char = '&';
	
	public function abrirArquivo($name) {
		$dest = $name;
		$h = NULL;
		$h = fopen ($dest, 'r'); 	
		$content = fread($h, filesize($dest));
		fclose($h);
		$mat = array();
		$mat = explode(' ', $content);
		$i = 0;
		$cellsConteudo = array();
		while ($mat[$i] != NULL) {
			$cell = substr($mat[$i], 0, strpos($mat[$i], '&'));
			$conteudo = substr($mat[$i], strpos($mat[$i], '&')+1);
			$indCell = criaNum2($cell);
			$cellsConteudo[$indCell] = $conteudo;
			$i++;
		}	
		return $cellsConteudo;
	}
	
	public function Novo($q, $cellsConteudo, $cellsCalculo) {
		$q = 0;
		$cellsConteudo = array();
		$cellsCalculo = array();
		$ret = array($q, $cellsConteudo, $cellsCalculo);
		return $ret;
	}
/*function save_file($name, $post, $cellsVariaveis, $q) {
	$i = 0;
	$content = '';
	while ($i < $q) {
		$cell = $cellsVariaveis[$i];
		$content .= $cell.$char.$post[$cell]." ";
		$i++; 
	}	
	$src = "tabelas/base.txt";
	$dest = "tabelas/".$name.".txt";
	if (!file_exists($dest)) {
		$handle = fopen ($dest, 'w'); 
		fwrite ($handle, $content); 
		fclose($handle);	
	} else echo "Já existe um arquivo com o nome '$dest'";
}*/
}
?>
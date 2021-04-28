<?php
	
	
	
	/*FUNгуES GERAIS*/
	
	/*function isLetter ($s) {
		$ret = TRUE;
		try {
			$s[0] = 0;
		} catch (Exception $e) {
			$ret = FALSE;
		}
		return $ret;
	}*/
trait Functions {	
	public function write_status($class='st') {
		global $status;
		if ($status != '') {
			if ($class === 'st')
				echo "<span style='text-align:center; color:#FF0000; font-size:18px;'>$status</span>";
			else echo "<span class='$class' id='$class'>$status</span>";
		}
	}
	public function apaga($s, $indice, $contador){
		if ($s!=""){
			if ((strlen($s) == $contador) &&($indice == 0))
				$s = "";
			else if (($indice > 0) && ($indice < strlen($s)-$contador))
				$s = substr($s, 0, $indice).substr($s, $indice+$contador);
			else if ($indice == 0)
				$s = substr($s, $contador);
			else if ($indice == strlen($s)-$contador)
				$s = substr($s, 0, $indice);
		}
		return $s;
	}
	public function insere ($s, $subS, $indice){
		if ($indice == 0)
			$s = $subS . $s;
		else if (($indice > 0) && ($indice < strlen($s)))
			$s = substr($s, 0, $indice) . $subS . substr($s, $indice);
		else if ($indice == strlen($s))
			$s = $s . $subS;
		return $s;
	}
	public function substitui ($s, $subS, $i, $cont) {
		$s = $this->apaga($s, $i, $cont);
		$s = $this->insere($s, $subS, $i);
		return $s;
	}
	public function encode($s) {
		for ($i = 0; $i < strlen($s); $i++) {
			switch ($s[$i]) {
				case 'А':   
					$s = $this->substitui($s, '$a', $i, 1);
				break;
				case 'Б':   
					$s = $this->substitui($s, '$b', $i, 1);
				break;
				case 'Ц':   
					$s = $this->substitui($s, '$c', $i, 1);
				break;
				case 'Ю':   
					$s = $this->substitui($s, '$d', $i, 1);
				break;
				case 'И':   
					$s = $this->substitui($s, '$e', $i, 1);
				break;
				case 'Й':   
					$s = $this->substitui($s, '$f', $i, 1);
				break;
				case 'М':   
					$s = $this->substitui($s, '$i', $i, 1);
				break;
				case 'С':   
					$s = $this->substitui($s, '$o', $i, 1);
				break;
				case 'Т':   
					$s = $this->substitui($s, '$p', $i, 1);
				break;
				case 'У':   
					$s = $this->substitui($s, '$q', $i, 1);
				break;
				case 'З':   
					$s = $this->substitui($s, '$u', $i, 1);
				break;
				case 'Г':   
					$s = $this->substitui($s, '$t', $i, 1);
				break;
			}
		}
		return $s;
	}
	public function decode($s) {
		for ($i = 0; $i < strlen($s); $i++) {
			if ($s[$i] == '$') {
				switch ($s[$i+1]) {
					case 'a':   
						$s = $this->substitui($s, 'А', $i, 2);
					break;
					case 'b':   
						$s = $this->substitui($s, 'Б', $i, 2);
					break;
					case 'c':   
						$s = $this->substitui($s, 'Ц', $i, 2);
					break;
					case 'd':   
						$s = $this->substitui($s, 'Ю', $i, 2);
					break;
					case 'e':   
						$s = $this->substitui($s, 'И', $i, 2);
					break;
					case 'f':   
						$s = $this->substitui($s, 'Й', $i, 2);
					break;
					case 'i':   
						$s = $this->substitui($s, 'М', $i, 2);
					break;
					case 'o':   
						$s = $this->substitui($s, 'С', $i, 2);
					break;
					case 'p':   
						$s = $this->substitui($s, 'Т', $i, 2);
					break;
					case 'q':   
						$s = $this->substitui($s, 'У', $i, 2);
					break;
					case 'u':   
						$s = $this->substitui($s, 'З', $i, 2);
					break;
					case 't':   
						$s = $this->substitui($s, 'Г', $i, 2);
					break;
				}
			}	
		}
		return $s;
	}
	public function html_encode($s) {
		for ($i = 0; $i < strlen($s); $i++) {
			switch ($s[$i]) {
				case 'А':   
					$s = $this->substitui($s, '&aacute;', $i, 1);
				break;
				case 'Б':   
					$s = $this->substitui($s, '&acirc;', $i, 1);
				break;
				case 'Ц':   
					$s = $this->substitui($s, '&atilde;', $i, 1);
				break;
				case 'Ю':   
					$s = $this->substitui($s, '&agrave;', $i, 1);
				break;
				case 'И':   
					$s = $this->substitui($s, '&eacute;', $i, 1);
				break;
				case 'Й':   
					$s = $this->substitui($s, '&ecirc;', $i, 1);
				break;
				case 'М':   
					$s = $this->substitui($s, '&iacute;', $i, 1);
				break;
				case 'С':   
					$s = $this->substitui($s, '&oacute;', $i, 1);
				break;
				case 'Т':   
					$s = $this->substitui($s, '&ocirc;', $i, 1);
				break;
				case 'У':   
					$s = $this->substitui($s, '&otilde;', $i, 1);
				break;
				case 'З':   
					$s = $this->substitui($s, '&uacute;', $i, 1);
				break;
				case 'Г':   
					$s = $this->substitui($s, '&ccedil;', $i, 1);
				break;
				case 'а':   
					$s = $this->substitui($s, '&Aacute;', $i, 1);
				break;
				case 'б':   
					$s = $this->substitui($s, '&Acirc;', $i, 1);
				break;
				case 'ц':   
					$s = $this->substitui($s, '&Atilde;', $i, 1);
				break;
				case 'ю':   
					$s = $this->substitui($s, '&Agrave;', $i, 1);
				break;
				case 'и':   
					$s = $this->substitui($s, '&Eacute;', $i, 1);
				break;
				case 'й':   
					$s = $this->substitui($s, '&Ecirc;', $i, 1);
				break;
				case 'м':   
					$s = $this->substitui($s, '&Iacute;', $i, 1);
				break;
				case 'с':   
					$s = $this->substitui($s, '&Oacute;', $i, 1);
				break;
				case 'т':   
					$s = $this->substitui($s, '&Ocirc;', $i, 1);
				break;
				case 'у':   
					$s = $this->substitui($s, '&Otilde;', $i, 1);
				break;
				case 'з':   
					$s = $this->substitui($s, '&Uacute;', $i, 1);
				break;
				case 'г':   
					$s = $this->substitui($s, '&Ccedil;', $i, 1);
				break;
			}
		}
		return $s;
	}
	public function str_positions($substr, $str){
		//echo '  hcd  '.$url;
		//$url = apaga($url, 0, strpos($url, $pasta));
		$pos = array();
		$j = strpos($str, $substr);
		$i = 0;
		$l = strlen($substr);
		//echo ($substr.$j);
		while ($j !== FALSE){
			//echo ($substr);
			$str = substr($str, $j+$l);
			$pos[$i] = $j; 
			$j = strpos($str, $substr);
			$i++;
		}
		return $pos;
	}
	
	/*
	stringToArray transforma uma string numa matriz, onde $x И o caractere que separa as colunas, e $y И o que separa as linhas
	*/
	public function stringToArray ($endereco, $x, $y){
		$aux = $endereco;
		$mat = array();
		$i = 0;
		while ($l !== FALSE) {
			$l = strpos($aux, $y);
			$s = substr($aux, 0, $l);
			$mat[$i] = explode($x, $s);
			$aux = substr($aux, $l+1, strlen($aux)-$l-1);
			$i++;
		}	
	}
	
	public function upload($arquivo, $endereco) {
		$destino = $endereco.'/'.$arquivo['name'];
		if($arquivo['error'] != 0) {
			echo '<p><b>Erro no Upload do arquivo<br>';
			switch($arquivo['erro']) {
				case UPLOAD_ERR_INI_SIZE:
				echo 'O Arquivo excede o tamanho mАximo permitido';
				break;
				case UPLOAD_ERR_FORM_SIZE:
				echo 'O Arquivo enviado И muito grande';
				break;
				case UPLOAD_ERR_PARTIAL:
				echo 'O upload nЦo foi completo';
				break;
				case UPLOAD_ERR_NO_FILE:
				echo 'Nenhum arquivo foi informado para upload';
			break;
			}
			echo '</b></p>';
			exit;
		} 	
		if(copy($arquivo['tmp_name'],$destino)) {
			echo 'Enviado corretamente.';
		} else echo 'Ocorreu um erro no envio do arquivo.';
	}
	public function mkdirs ($endereco){
		$aux = $endereco;
		$pasta = '';
		$l = TRUE;
		while ($l !== FALSE) {
			$l = strpos($aux, '/');
			if ($l !== FALSE) {
				$pasta .= substr($aux, 0, $l+1);	
			} else {
				$pasta .= $aux;
			}
			if (!file_exists($pasta)) {
				mkdir($pasta, 0700);
			}
			if ($l !== FALSE)
				$aux = substr($aux, $l+1, strlen($aux)-$l-1);
		}
	}
	
	public function transposed_matrix($matriz) {
		$return = array();
		$j = 0;
		for ($i = 0; $matriz[$i][$j] !== NULL; $i++) {
			for ($j = 0; $matriz[$i][$j] !== NULL; $j++) {
				$return[$j][$i] = $matriz[$i][$j];	
			}
			$j = 0;
		}
		return $return;
	}
	
	public function tab($str, $tamanho, $direct) {
		$n = strlen($str); 
		echo strlen($str).' ';
		if ($tamanho > $n) {
			for ($i = $n; $i <= $tamanho; $i++) {
				if ($direct != "") {
					$str = "&nbsp;".$str;
				} else $str .= "&nbsp;";
			} 	
		}
		echo strlen($str).', ';
		return $str;
	}
}
?>
<?php
require_once "funcoes.php";



function criaNum ($v) {
	try {
		$l2 = 0;
		$bl2 = 1;
		$n2 = 0; 
		$bn2 = 1;
		$r = array();
		$v = substr($v, 1);
		for ($k = strlen($v)-1; $k >= 0; $k--) {
			$ch = ord($v[$k]);
			if ($ch >= 65 && $ch <= 90) {
				$l2 += ($ch-64)*$bl2;
				$bl2 *= 26;
			} else {
				$n2 += ($ch-48)*$bn2;
				$bn2 *= 10; 
			}
		}
		$r[0] = $l2;
		$r[1] = $n2;
		//echo $l2." ".$n2;
		return $r;
	} catch (Exception $e) {
		$status = $e->getMessage();
	}	
}

//criaNum("\$G3");

function criaNum2($cell){
	try {
		$i = array();
		$i = criaNum($cell);
		$indCell = $i[0]+26*($i[1]-1)-1;
		return $indCell;
	} catch (Exception $e) {
		$status = $e->getMessage();
	}		
}


function isFloat ($s) {
	try {
		$i = 0;
		$float = true;
		if ($s == '') {
			$float = false;
		}
		//document.getElementById("vars").innerHTML = $s;
		while ($i < strlen($s)) {
			$c = $s[$i];
			if (!(ord($c) >= 48 && ord($c) <= 57 || $c == '.' || $c == '-')) {
				$float = false;
				break;
			}
			$i++;
		}
		//echo $float;
		return $float;
	} catch (Exception $e) {
		$status = $e->getMessage();
	}
}

function criaLetras ($i) {
	try {
		$l = '';
		$n = $i % 26;
		while ($n > 0) {
			$l = chr($n+64).$l;
			$i = (int)$i/26;
			$n = $i % 26;
		}
		return $l;
	} catch (Exception $e) {
		$status = $e->getMessage();
	}		
}

function getFirstVar($s) {
	try {
		$i = 0;
		$k = 0;
		$eh= (ord($s[$i]) >= 65 && ord($s[$i]) <= 90);
		$v = '';
		if ($s != '') {
			while (!$eh && $i < strlen($s)) {
				$i++;
				$eh= (ord($s[$i]) >= 65 && ord($s[$i]) <= 90);
			}
			while ($eh && $i < strlen($s)) {
				$v .= $s[$i];
				$i++;
				$ch = ord($s[$i]);
				$eh= ($ch >= 65 && $ch <= 90 || $ch >= 48 && $ch <= 57);
			}
			$s = substr($s, $i);
		}	
		$ret = array();
		$ret[0] = "\$".$v;
		$ret[1] = $s;
		return $ret;
	} catch (Exception $e) {
		$status = $e->getMessage();
	}		
}

function criaVariaveis($s) {
	try {
		global $cellsCalculo;
		$interv = false;
		$j = 0;
		$vars = array();
		$vs = array();
		while (true) {
			$vs = getFirstVar($s);
			if ($vs[0] == "\$") break;
			$v = $vs[0];
			$s = $vs[1];
			$n = criaNum2($v);
			$vars[$j] = $v;
			$int1 = array();
			$int2 = array();
			if ($s[0] == ':') {
				$vs = getFirstVar($s);
				if ($vs[0] == '') break;
				$v = $vs[0];
				$s = $vs[1];
				$int1 = criaNum($vars[$j]);
				$int2 = criaNum($v);
				$l1 = $int1[0];
				$n1 = $int1[1];
				$l2 = $int2[0];
				$n2 = $int2[1];
				
				for ($m = $n1; $m <= $n2; $m++) {
					for ($k = $l1; $k <= $l2; $k++) {
						$v = "\$".criaLetras($k).$m;
						$n = criaNum2($v);
						$vars[$j] = $v;
						$j++;
					}
				}
				$j--;
			}
			$j++;
		}
	} catch (Exception $e) {
		$status = $e->getMessage();
	}	
	return $vars;
}
function poeAspas($contCell) {
	$StrVars = FALSE;
	try {
		for ($i = 0; $i < strlen($contCell); $i++) {
			//$c = ord($contCell[$i-1]);
			$HaDoisPontos = (strpos($contCell, ':', $i) < strpos($contCell, ')', $i));
			$HaDoisPontos = $HaDoisPontos && (strpos($contCell, ':', $i) !== FALSE);
			if ($contCell[$i] == '(' && $HaDoisPontos ) {
				$contCell = Insere($contCell, "'", $i+1);	
				$StrVars = TRUE;
			} else if ($contCell[$i] == ')' && $StrVars) {
				$contCell = Insere($contCell, "'", $i);	
				$i++;
				$StrVars = FALSE;
			}	
		}
	} catch (Exception $e) {
		$status = $e->getMessage();
	}
	return $contCell;
}
function calculaCelulas ($table2, $post) {
	$i = 0;
	while ($table2[$i] != NULL) {
		$linha = array();
		$linha = explode('&', $table2[$i]);
		$valor = $linha[1];
		if ($valor[0] == '=') {
			$j = 0;
			while (strlen($valor) > 0) {
				$v = array();
				$v = getFirstVar($valor);
				$var = $v[0];
				$valor = $v[1];
				if ($post[$var][0] == '=') {
					
				} 
				$j++;
			}
		}
		$i++;
	}
}
?>

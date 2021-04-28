<?
require_once "funcoesArquivo.php"; 
require_once "funcoesBD.php";
require_once "funcoesCelulas.php";
require_once "funcoesImagens.php";
require_once "funcoesDataHora.php";


/*
$args é uma matriz bidimensional que contem uma tabela que é resultado da execução de um comando select do SQL 

$quant define a quantidade de colunas de $args que serão escritas na função, a partir da última coluna

Esta função acrescenta a possibilidade de se escrever tabelas em matriz bidimensional, onde a variável $n tão somente definirá quantas colunas a matriz terá.


Se $img é true então a coluna $indiceLink será uma imagem, caso $indiceLink for maior ou igual a 0. Se $indiceLink for menor do que zero e $img for true, então todas as colunas da tabela serão imagens cujo endereço é o conteúdo da coluna.

$estilo define todos os atributos html da tabela que será escrita contendo o resultado de uma consulta sql
*/

function escreveTabela ($args, $quant, $n, $img, $estilo) {
	global $quantTabelas;
	$quantTabelas++;
	$table = "<table '$estilo'><tr>";
	$i = 0;
	while ($args[0][$i] != NULL) {
		$i++;
	}
	$quant = (int) $quant;
	$i = $i - $quant;
	$k = 0;
	while ($args[$k][$i] != NULL) {
		while ($args[$k][$i] != NULL) {
			$table .= "<td align='center'>";
			if ($img) {
				if (strpos($args[$k][$i], "[a]") !== FALSE) {
					$aux = substr($args[$k][$i], 3);
					$table .= "<a href='".$args[$k][$i-1]."'><img src='".$aux."' width='90'/></a>";
				} else	$table .= "<img src='".$args[$k][$i]."'/>";
			} else $table .= $args[$k][$i];
			$table .= "</td>";
			$i++;
		}
		$i = $i - $quant;
		$k++;
		if ($k % $n == 0) {
			$table .= "</tr><tr>";
		}
	}
	$table .= "</tr></table>";
	echo $table;
}
/*
$args é uma matriz bidimensional que contem uma tabela que é resultado da execução de um comando select do SQL

$links contem uma página php para a qual o site encaminhará quando for clicado um link correspondente a alguma coluna da tabela que será escrita.

$indiceLink é a coluna da tabela que irá se exibida como um link que levará à página contida em links. Tal link irá conter o parâmetro código, na coluna zero de $args

$quant define a quantidade de colunas de $args que serão escritas na função, a partir da última coluna

Esta função acrescenta a possibilidade de se escrever tabelas em matriz bidimensional, onde a variável $n tão somente definirá quantas colunas a matriz terá.

Se $img é true então a coluna $indiceLink será uma imagem, caso $indiceLink for maior ou igual a 0. Se $indiceLink for menor do que zero e $img for true, então todas as colunas da tabela serão imagens cujo endereço é o conteúdo da coluna.

$estilo define todos os atributos html da tabela que será escrita contendo o resultado de uma consulta sql
*/
function escreveTabela2 ($args, $links, $indiceLink, $quant, $n, $img, $estilo) {
	global $quantTabelas;
	$quantTabelas++;
	$table = "<table '$estilo'><tr>";
	$i = 0;
	while ($args[0][$i] != NULL) {
		$i++;
	}
	$quant = (int) $quant;
	$i = $i - $quant;
	$k = 0;
	//echo $i;
	while ($args[$k][$i] != NULL) {
		while ($args[$k][$i] != NULL) {
			$table .= "<td>";
			if ($img) {
				if ($i == $indiceLink) {
					$aux = $args[$k][$i];
					$table .= "<a href='".$links."?ide=".$args[$k][0]."'><img src='".$aux."' width='90'/></a>";
				} else	$table .= "<img src='".$args[$k][$i]."'/>";
			} else {
				if ($i == $indiceLink) {
					$aux = $args[$k][$i];
					$table .= "<a href='".$links."?ide=".$args[$k][0]."'>".$aux."</a>";
				} else	$table .= $args[$k][$i];
				//$table .= $args[$k][$i];
			}	
			$table .= "</td>";
			$i++;
		}
		$i = $i - $quant;
		$k++;
		if ($k % $n == 0) {
			$table .= "</tr><tr>";
		}
		
	}
	$table .= "</tr></table>";
	echo $table;
}

/*
$args é uma matriz que contem uma tabela que é resultado da execução de um comando select do SQL

$links contem uma página php para a qual o site encaminhará quando for clicado um link correspondente a alguma coluna da tabela que será escrita.

$indiceLink é a coluna da tabela que irá se exibida como um link que levará à página contida em links. Tal link irá conter o parâmetro código, na coluna zero de $args

$quant define a quantidade de colunas de $args que serão escritas na função, a partir da última coluna

Se $img é true então a coluna $indiceLink será uma imagem, caso $indiceLink for maior ou igual a 0. Se $indiceLink for menor do que zero e $img for true, então todas as colunas da tabela serão imagens cujo endereço é o conteúdo da coluna.

$estilo define todos os atributos html da tabela que será escrita contendo o resultado de uma consulta sql

$estilo2 define todos os atributos html de cada célula da tabela tabela que será escrita contendo o resultado de uma consulta sql

Esta função acrescenta a possibilidade de se escrever tabelas em matriz bidimensional, onde a variável $n tão somente definirá quantas colunas a matriz terá.
*/

function escreveTabela3 ($args, $links, $indiceLink, $quant, $n, $img, $estilo, $estiloCell) {
	global $quantTabelas;
	$quantTabelas++;
	$table = "<table '$estilo'><tr>";
	$i = 0;
	while ($args[0][$i] != NULL) {
		$i++;
	}
	$quant = (int) $quant;
	$i = $i - $quant;
	$k = 0;
	//echo $i;
	while ($args[$k][$i] != NULL) {
		while ($args[$k][$i] != NULL) {
			$table .= "<td ".$estiloCell.">";
			if ($img) {
				if ($i == $indiceLink) {
					$aux = $args[$k][$i];
					$table .= "<a href='".$links."?ide=".$args[$k][0]."'><img src='".$aux."' width='90'/></a>";
				} else	$table .= "<img src='".$args[$k][$i]."'/>";
			} else {
				if ($i == $indiceLink) {
					$aux = $args[$k][$i];
					$table .= "<a href='".$links."?ide=".$args[$k][0]."'>".$aux."</a>";
				} else	$table .= $args[$k][$i];
				//$table .= $args[$k][$i];
			}	
			$table .= "</td>";
			$i++;
		}
		$i = $i - $quant;
		$k++;
		if ($k % $n == 0) {
			$table .= "</tr><tr>";
		}
	}
	$table .= "</tr></table>";
	echo $table;
}

function designSite ($top, $left, $width, $height) {

	echo "<script language='javascript'>";
	echo "document.all('site').style.top = $top*screen.height/100;";
	echo "document.all('site').style.left = $left*screen.width/100;";
	echo "document.all('site').style.width = $width*screen.width/100;";
	echo "document.all('site').style.height = $height*screen.height/100;";
	echo "</script>";
}

function designEstilosJV($file, $PixelOuPorcent, $props){
	/*$props = array("top", "left", "width", "height");
	for ($i = 0; $estilo[$i] != NULL; $i++) {
		$props[$i+4] = $estilo[$i];
	}*/
	$h = fopen ($file, 'r'); 
	$matriz = fread($h, filesize($file));
	$parentese = strpos($matriz, "(");
	$estilos = " ";
	$k = 1;
	while ($parentese !== FALSE){
		$j = $parentese+1;
		$posdim = array();
		$i = 0;
		$num = '';
		$estilos .= "#pos$k {\n ";
		while ($matriz[$j-1] != ')') {
			if ($matriz[$j] != ' ' && $matriz[$j] != ')'){
				$num .= $matriz[$j];
			} else if ($props[$i] != NULL){
				if ($PixelOuPorcent == '%' && $i < 4) {
					$num.='%';
				} else if ($i < 4) $num.='px';
				$estilos .= $props[$i].": ".$num."; " ;
				$num = '';
				$i++;
			}
			$j++;
		}
		$estilos .= "}";
		$matriz = substr($matriz, $j);
		$parentese = strpos($matriz, "(");
		$k++;
	}
	$estilos .= "";
	return $estilos;
}
function designEstilosPonto($file, $ehTag, $estilo){
	$props = array("font-family", "color", "font-size");
	for ($i = 0; $estilo[$i] != NULL; $i++) {
		$props[$i+3] = $estilo[$i];
	}
	$h = fopen ($file, 'r'); 
	$matriz = fread($h, filesize($file));
	$parentese = strpos($matriz, "(");
	$estilos = " ";
	$k = 1;
	while ($parentese !== FALSE){
		$j = $parentese+1;
		$posdim = array();
		$i = 0;
		$num = '';
		$nomeEstilo = substr($file, 0, strlen($file)-4);
		if (!$ehTag){ 
			$estilos .= ".estilo$k {";
		} else {
			$estilos .= "$nomeEstilo {";
		}
		while ($matriz[$j-1] != ')') {
			if ($matriz[$j] != ' ' && $matriz[$j] != ')'){
				$num .= $matriz[$j];
			} else if ($props[$i] != NULL){
				$estilos .= $props[$i].": ".$num."; " ;
				$num = '';
				$i++;
			}
			$j++;
		}
		$estilos .= "}";
		$matriz = substr($matriz, $j);
		$parentese = strpos($matriz, "(");
		$k++;
	}
	$estilos .= "";
	return $estilos;
}

function designTable($conteudoTabela, $estiloTabela, $arquivoOuEstilos, $estilo, $num_cols) {
	global $quantTabelas;
	$quantTabelas++;
	if (strpos($arquivoOuEstilos, '.txt') != FALSE || strpos($arquivoOuEstilos, '.rtf') != FALSE) {
		$props = array("align", "width", "height");
		for ($i = 0; $estilo[$i] != NULL; $i++) {
			$props[$i+3] = $estilo[$i];
		}
		$h = fopen ($arquivoOuEstilos, 'r'); 
		$matriz = fread($h, filesize($arquivoOuEstilos));
		$estilosCelulas = array();
		$estilosCelulas = explode("\n", $matriz);
		$i = 0;
		while ($estilosCelulas[$i] != NULL) {
			$estiloCelula = array();
			$estiloCelula = explode(" ", $estilosCelulas[$i]);
			$j = 0;
			$estilosCelulas[$i] = '';
			while ($estiloCelula[$j] != NULL) {
				$estiloCelula[$j] = $props[$j].'="'.$estiloCelula[$j].'" ';
				$estilosCelulas[$i] .= $estiloCelula[$j];
				//echo $estilosCelulas[$i]."<br>";
				$j++;
			}
			$i++;
		}
	} 
	
	$tabela = "<table $estiloTabela><tr id='Row$quantTabelas$i'>\n";
	for ($i = 0; $conteudoTabela[$i] != NULL; $i++) {	
		if (strpos($arquivoOuEstilos, '.txt') !== FALSE || strpos($arquivoOuEstilos, '.rtf') !== FALSE) {
			$tabela .= "<td ".$estilosCelulas[$i].">".$conteudoTabela[$i]."</td>";
		}
		else $tabela .= "<td $arquivoOuEstilos>".$conteudoTabela[$i]."</td>";
		if ($i % $num_cols == $num_cols-1) {
			$tabela .= "</tr>\n<tr id='Row$quantTabelas$i'>";
		}
		//echo $i;
	}
	//echo $i;
	$tabela .= "</tr></table>";
	return $tabela;
}
function designTable2($conteudoTabela, $estiloTabela, $arquivoOuEstilos, $estilo) {
	global $quantTabelas;
	$quantTabelas++;
	if (strpos($arquivoOuEstilos, '.txt') != FALSE || strpos($arquivoOuEstilos, '.rtf') != FALSE) {
		$props = array("align", "width", "height");
		for ($i = 0; $estilo[$i] != NULL; $i++) {
			$props[$i+3] = $estilo[$i];
		}
		$h = fopen ($arquivoOuEstilos, 'r'); 
		$matriz = fread($h, filesize($arquivoOuEstilos));	
		$estilosCelulas = array();
		$estilosCelulas = explode("\n", $matriz);
		$i = 0;
		while ($estilosCelulas[$i] != NULL) {
			$estiloCelula = array();
			$estiloCelula = explode(" ", $estilosCelulas[$i]);
			$j = 0;
			$estilosCelulas[$i] = '';
			while ($estiloCelula[$j] != NULL) {
				$estiloCelula[$j] = $props[$j].'="'.$estiloCelula[$j].'" ';
				$estilosCelulas[$i] .= $estiloCelula[$j];
				$j++;
			}
			$i++;
		}
	} 
	$tabela = "<table $estiloTabela>";
	$k = 0;
	$i = 0;
	foreach ($conteudoTabela as $linha) {
		$tabela .= "<tr id='Row$quantTabelas$i'>";	
		foreach ($linha as $cell) {
			if (strpos($arquivoOuEstilos, '.txt') !== FALSE || strpos($arquivoOuEstilos, '.rtf') !== FALSE) {
				$tabela .= "<td ".$estilosCelulas[$k].">".$cell."</td>";
				$k++;
			}
			else $tabela .= "<td $arquivoOuEstilos>".$cell."</td>";
			//echo $conteudoTabela[$i][$j];
		}
		//echo $conteudoTabela[$i][$j];
		$tabela .= "</tr>";
		$i++;
	}
	$tabela .= "</table>";
	return $tabela;
}

function insereImagens($enderecos, $props) {
	for ($i = 0; $enderecos[$i] != NULL; $i++) {
		$enderecos[$i] = "<img src='".$enderecos[$i]."' $props>";
	}
	return $enderecos;
}

function insereLinks($enderecos, $links, $props) {
	for ($i = 0; $enderecos[$i] != NULL; $i++) {
		//echo $enderecos[$i];
		$links[$i] = "<a href='".$enderecos[$i]."' $props>".$links[$i]."</a>";
		
	}
	return $links;
}

function insereLista($conteudo, $tipo) {
	$lista = "<ul type='$tipo'>";
	for ($i = 0; $conteudo[$i] != NULL; $i++) {
		$lista .= "<li>".$conteudo[$i]."</li>";
	}
	$lista .= "</ul>";
	return $lista;
}

function formarSlides ($portfolio, $quantPorSlides, $num_cols, $estiloTabela) {
	$table = '';
	for ($i = 0; $portfolio[$i] != NULL; $i += $quantPorSlides) {
		$slide = array();
		for ($j = $i; $j < $i+$quantPorSlides; $j++) {
			$slide[$j] = $portfolio[$j];	
		}
		$id = " id='tabela$i'";
		$invisivel = '';
		if ($i > 0) {
			$invisivel = " style='display:none;'";
		}
		$estiloTabela2 = $estiloTabela.$id.$invisivel;
		echo designTable($slide, $estiloTabela2, $arquivoOuEstilos, $estilo, $num_cols);
		if ($i == 0) {
			//$table = designTable($slide, $estiloTabela, $arquivoOuEstilos, $estilo, $num_cols);
		}
		//echo $table;
	}
	return $table;
}

$menu = '';
$child = FALSE;
function get_menu($exclude, $parent) {
	$sql = "select t.term_id, t.name from wp_terms t inner join wp_term_taxonomy tt on t.term_id = tt.term_id where tt.parent = $parent order by t.term_id";
	global $menu;
	global $child;
	$rs = mysqli_query($con, $sql);
	if (!$child) {
		$menu .= "<ul class='sf-menu'>\n";
	} else $menu .= "<ul class='children'>\n";	
	while ($row = mysqli_fetch_array($rs)){
		$menu .= "<li><a href='".get_bloginfo("url")."/?cat=".$row["term_id"]."'>".$row["name"]."</a>";
		$rs2 = mysqli_query($con, "select tt.term_taxonomy_id from wp_term_taxonomy tt where tt.parent = ".$row["term_id"]);
		if ($row2 = mysqli_fetch_array($rs2)) {
			//$menu .= $row["term_id"];
			$child = TRUE;
			get_menu($exclude, $row["term_id"]);
		}
		$menu .= "</li>\n";
	}
	$menu .= "</ul>\n";
	$ret = $menu;
	return $ret;
}
/*
Parâmetros

banco: informa o banco de dados de onde serão extraídos os dados
fields: informa os campos a serem pesquisados e suas respectivas tabelas
filtro: informa as restrições do código sql formado
tag: informa a tag onde os dados entrarão
properties: informa propriedades das tags
linkagem: estabelece regras de criação de links entre campos
*/
function designBlocks ($params) {
	$sql = "show tables from ".$params["banco"];
	$rs = mysqli_query($con, $sql);
	//echo mysqli_num_rows($rs).'<br>';
	$i = 0;
	$tables = array();
	$tables_abrev = array();
	$k = 0;
	$campos = '';
	$fields2 = array();
	//$keys = array_keys($fields);
	$temId = false;
	if (is_array($params["fields"])) {
		if (!is_array($params["fields"][0])) {
			while ($row = mysqli_fetch_array($rs, MYSQL_NUM)) {
				//echo $row[0].'<br>';
				$rs2 = mysqli_query($con, 'show fields from '.$row[0]);
				$j = 0;
				$achou = false;
				while ($row2 = mysqli_fetch_array($rs2, MYSQL_NUM)) {
					if (in_array($row2[0], $fields)) {
						$field = $row2[0];
						$table[$k] = $row[0];
						$tables_abrev[$k] = substr($row[0], 0, 3);
						$campos .= $tables_abrev[$k].".".$field.", ";
						$achou = true;
						$fields2[$i] = $field;
						$i++;
					}
				}
				if ($achou)	$k++;
			}
		} else {
			$k = 0;
			$j = 0;
			foreach ($params["fields"] as $field) {
				if ($j == 0 || $field[1] != $table[$j-1]) {
					$table[$j] = $field[1];
					$tables_abrev[$j] = substr($field[1], 0, 3);
					$j++;
				}
				if (!empty($field[0])) {
					$campos .= $tables_abrev[$j-1].".".$field[0].", ";
					$fields2[$k] = $field[0];
					$k++;	
				}
			}
		}
		$campos = substr($campos, 0, strlen($campos)-2);
		$sql = "select $campos from ".$table[0]." ".$tables_abrev[0];
		for ($i = 1; $i < $j; $i++) {
			$sql .= " inner join ".$table[$i]." ".$tables_abrev[$i]." on ";
			$rs = mysqli_query($con, "show create table ".$table[$i]);	
			$row = mysqli_fetch_array($rs, MYSQL_NUM);
			$s = $row[1];
			$inicio = strpos($s, "FOREIGN");
			$fim  = strpos($s, "ENGINE")-2; 
			$s2 = substr($s, $inicio, $fim-$inicio);
			//echo $s2.'<br>';
			$inicio = strpos($s2, "(`")+2;
			$fim  = strpos($s2, "`)"); 
			$campo1 = substr($s2, $inicio, $fim-$inicio);
			$inicio = strpos($s2, "(`", $fim+2)+2;
			$fim  = strpos($s2, "`)", $fim+2); 
			$campo2 = substr($s2, $inicio, $fim-$inicio);
			//echo '<br>'.$s.'<br>';
			//echo "<br>$fim<br>".$s2[$fim+1].'<br>';
			$offset = strpos($s2, "`)")+2; 
			$inicio = strpos($s2, "`", $offset)+1;
			$fim  = strpos($s2, "`", $inicio+1); 
			$reftable = substr($s2, $inicio, $fim-$inicio);
			$sql .= $tables_abrev[$i].".".$campo1." = ".substr($reftable, 0, 3).".".$campo2;
		}
		if (!empty($params["filtro"])) {
			$sql .= " where ".$params["filtro"];
			$temId = true;
		}
	} elseif (is_string($params["fields"])) {
		$sql = $params["fields"];
		if (strpos($sql, "where") !== FALSE) {
			$temId = true; 
		}
	}
	
	//SELEÇÃO DOS VALORES NAS TABELAS
	
	$args = array();
	//echo $sql."<br>";
	$args = selecionar($sql, $fields2, $temId, -1);
	/*if (is_string($params["fields"])) {
		$rs = mysqli_query($con, $sql);	
		$fields = array();
		while ($row = mysqli_fetch_array($rs)) {
			array_keys(
		}
	}*/
	$html = '';
	$exclude = array();
	$i = 0;
	$linkFoto = FALSE;
	//echo is_array($params['linkagem']).'....';
	if (is_array($params['linkagem'])) {
		foreach ($params['linkagem'] as $link) {
			if (is_array($link) && strpos($link[1], "Reduzida") !== FALSE) {
				$linkFoto = TRUE;	
				break;	
			}
		}
	}
	foreach ($args as $arg) {
		$j = 0;
		foreach ($arg as $a) {
			if (strpos($a, ".jpg") !== FALSE || strpos($a, ".gif") !== FALSE || strpos($a, ".JPG") !== FALSE || strpos($a, ".GIF") !== FALSE){
				if (!$linkFoto) {
					$args[$i][$j] = "<img src='".$a."' border=0 />\n";
				} elseif (strpos($a, "Thumb") !== FALSE){
					$args[$i][$j] = "<img src='".$a."' border=0 />\n";
				}
			}
			$j++;
		}
		$i++;
	}
	if (!empty($params["linkagem"])) {
		if (is_array($params["linkagem"][0])) {
			$k = 0;
			foreach ($params["linkagem"] as $link) {
				$index1 = array_keys($fields2, $link[0]);	
				$index2 = array_keys($fields2, $link[1]);
				$i1 = $index1[0];	
				$i2 = $index2[0];
				for ($i = 0; $args[$i][0] != NULL; $i++) {
					/*if (strpos($args[$i][$i2], ".jpg") !== FALSE || strpos($args[$i][$i2], ".gif") !== FALSE || strpos($args[$i][$i2], ".JPG") !== FALSE || strpos($args[$i][$i2], ".GIF") !== FALSE) {
						$args[$i][$i2] = "<img src='".$args[$i][$i2]."' border=0/>";
					}*/
					$args[$i][$i2] = "<a href='".$args[$i][$i1]."' target='_blank'>".$args[$i][$i2]."</a>";	
				}
				$exclude[$k] = $i1;
				$k++;
			}
		}
		else {
			for ($k = 1; $params["linkagem"][$k] != NULL; $k++) {
				$index1 = array_keys($fields2, $params["linkagem"][$k][0]);	
				$index2 = array_keys($fields2, $params["linkagem"][$k][1]);
				$i1 = $index1[0];	
				$i2 = $index2[0];
				for ($i = 0; $args[$i][0] != NULL; $i++) {
					if (strpos($args[$i][$i2], ".jpg") !== FALSE || strpos($args[$i][$i2], ".gif") !== FALSE) {
						$args[$i][$i2] = "<img src='".$args[$i][$i2]."' border=0/>";
					}
					$args[$i][$i2] = "<a href='".$params["linkagem"][0]."?id=".$args[$i][$i1]."' target='_blank'>".$args[$i][$i2]."</a>";	
				}
				$exclude[$k] = $i1;	
				$k++;
			}
		}
	}
	
	//ESCRITA DOS VALORES EM HTML
	
	if ($params["tag"] == 'div') {
		for ($i = 0; $args[$i][0] != NULL; $i++) {
			$html .= "<div class='post' id='post'>";
			$j = 0;
			 
			foreach ($params["fields"] as $field) {
				if (!in_array($j, $exclude)) {
					if (is_array($params["properties"])) {
						$html .= "<div class='post".($j+1)."' id='post".($j+1)."' ".$params["properties"][$j]." ><span class='label".($j+1)."'>".$params["labels"][$j]."</span>".$args[$i][$j]."</div>";
					} else {
						$html .= "<div class='post".($j+1)."' id='post".($j+1)."' ".$params["properties"]."><span class='label".($j+1)."'>".$params["labels"][$j]."</span>".$args[$i][$j]."</div>";
					}	
				}
				$j++;
			}
			$html .= "</div>";
		}
	}
	elseif ($params["tag"] == "table") {
		$html .= "<table><th>";
		$j = 0;
		foreach ($params["labels"] as $label) {
			if (!in_array($j, $exclude)) {
				$html .= "<td>".$label."</td>";	
			}
			$j++;	
		}
		$i = 0;
		foreach ($args as $linha) {
			$html .= "<tr>";
			$j = 0;
			foreach ($linha as $celula) {
				if (!in_array($j, $exclude)) {
					if (is_array($params["properties"])) {
						if (is_array($params["properties"][$i])) {
							$html .= "<td ".$params["properties"][$i][$j].">".$celula."</td>";
						} else {
							$html .= "<td ".$params["properties"][$i].">".$celula."</td>";
						}	
					} else $html .= "<td ".$params["properties"].">".$celula."</td>";
				}
				$j++;
			}
			$html .= "</tr>";
			$i++;
		}
		$html .= "</table>";
	}
	elseif ($params["tag"] == 'select') {
		$html = "<select ".$params["properties"].">";
		foreach ($args as $arg) {
			//echo $arg[0]."<br>";
			$html .= "<option value='".$arg[0]."'>".$arg[0]."</option>";	
		}
		$html .= "</select>";
	} elseif ($params["tag"] == 'ul') {
		if (is_array($params["properties"])) {
			$html .= "<ul ".$params["properties"][0].">\n";
		} else $html .= "<ul ".$params["properties"].">\n";	
		for ($i = 0; $args[$i][0] != NULL; $i++) {
			if (is_array($params["properties"])) {
				$html .= "<ul ".$params["properties"][$i+1].">\n";
			} else $html .= "<ul ".$params["properties"].">\n";	
			$j = 0;
			foreach ($params["fields"] as $field) {
				if (!in_array($j, $exclude)) {
					if (is_array($params["properties"])) {
						$html .= "<span class='label".($j+1)."'>".$params["labels"][$j]."</span>"."<span class='post".($j+1)."' >".$args[$i][$j]."</span>";
					} else {
						$html .= "<span ".$params["properties"]."><span class='label".($j+1)."'>".$params["labels"][$j]."</span>".$args[$i][$j]."</span>";
					}	
				}
				$j++;
			}
			$html .= "</li>\n";
		}
		$html .=  "</ul>\n";
	}
	return $html;
}
?>
<?php
function incluiConteudo($conteudo, $class, $antes_depois){
	if ($antes_depois == 'depois') {
		if (is_string($conteudo)) {
?>
		<script language="javascript">
			document.getElementById("<?php echo $class;?>").innerHTML += "<?php echo $conteudo;?>";
		</script>		
<?php
		}
		elseif (is_array($conteudo)) {
			$i = 0;
			foreach ($conteudo as $c) {
?>
			<script language="javascript">
				document.getElementById("<?php echo $class[$i];?>").innerHTML += "<?php echo $c;?>";	
			</script>	
<?php
				$i++;
			}
		}
	}
	elseif ($antes_depois == 'antes') {
		if (is_string($conteudo)) {
?>
		<script language="javascript">
			document.getElementById("<?php echo $class;?>").innerHTML = "<?php echo $conteudo;?>"+document.getElementById("<?php echo $class;?>").innerHTML;	
		</script>	
<?php
		}
		elseif (is_array($conteudo)) {
			$i = 0;
			foreach ($conteudo as $c) {
?>
			<script language="javascript">
				document.getElementById("<?php echo $class[$i];?>").innerHTML = "<?php echo $c;?>"+document.getElementById("<?php echo $class[$i];?>").innerHTML;
			</script>		
<?php
				$i++;
			}
		}
	}
}
?>
</script>
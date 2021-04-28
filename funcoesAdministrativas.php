<?php 
require_once "funcoesBD.php";
require_once "funcoesCelulas.php";
class AdminFunctions extends DBFunctions {
	//const STAR = "<font color=red>*</font>";
	
	public $idSession = 'user';
	public $cookie_url = 'localhost';
	public $cookie_https = TRUE;
	public $willSave = true;
	
	public function contagem ($table, $condition, $cd_usuario=0, $page) {
		$con = $this->con;
		mysqli_query($con, "create table if not exists $table (
		codigo integer not null auto_increment, dataHora dateTime, cd_usuario integer not null default -1, page integer not null default 0, primary key (codigo))");
		if ($condition) {
			$date = date('Y-m-d H:i:s');
			mysqli_query($con, "insert into $table (dataHora, cd_usuario, page) value ('$date', $cd_usuario, $page)");
			echo mysqli_error($con);
		}	
	}
	
	public function contagem2 ($table, $condition, $from) {
		$con = $this->con;
		mysqli_query($con, "create table if not exists $table (
		codigo integer not null auto_increment,
		dataHora dateTime, click_from varchar(5) not null default '',
		primary key (codigo))");
		if ($condition) {
			$date = date('Y-m-d H:i:s');
			mysqli_query($con, "insert into $table (dataHora, click_from) value ('$date', '$from')");
			echo mysqli_error($con);
		}	
	}
	
	public function mostraContagem ($table) {
		$con = $this->con;
		$rs = mysqli_query($con, "select * from $table limit 1");
		$row = mysqli_fetch_array($rs);
		$dataInicio = $row["dataHora"];
		$dataInicio = $this->FormatDataPadrao($dataInicio);
		$rs = mysqli_query($con, "select count(codigo) from $table");
		$row = mysqli_fetch_array($rs);
		$cont = $row['count(codigo)'];	
		echo "Visitantes desde $dataInicio: $cont"; 
	}
	public function sendEmail ($POST, $recipients, $auth=NULL, $names=array('email', 'name', 'subject', 'message'), $host='localhost') {  
		global $status;
		include("PHPMailer-master/src/PHPMailer.php");
		$mail = new PHPMailer();
		
		//$mail->Port = 25;
		$mail->IsSMTP(); //ENVIAR VIA SMTP    fsource_PHPMailer__
		$mail->Host = $host; //SERVIDOR DE SMTP, USE smtp.SeuDominio.com OU smtp.hostsys.com.br
		if (is_array($auth)) {
			$mail->SMTPAuth = true; //ATIVA O /SMTP AUTENTICADO
			$mail->Username = $auth[0]; //EMAIL PARA SMTP AUTENTICADO (pode ser qualquer conta de email do seu domínio)
			$mail->Password = $auth[1]; //password DO EMAIL PARA SMTP AUTENTICADO
		} else $mail->SMTPAuth = false;
		$from = $names[0];
		$from_name = $names[1];
		$subject = $names[2];
		$body = $names[3];
		$mail->From = $POST[$from]; //E-MAIL DO REMETENTE
		$mail->FromName = $POST[$from_name]; //NOME DO REMETENTE
		
		//$mail->AddReplyTo("tfrubim@yahoo.com.br"," Suporte Hostsys "); //UTILIZE PARA DEFINIR OUTRO EMAIL DE RESPOSTA (opcional)
		$mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
		$mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
		$mail->Subject = $POST[$subject]; //ASSUNTO DA MENSAGEM
		$mail->Body = $POST[$body]; //MENSAGEM NO FORMATO HTML
		//$mail->AltBody = "Teste de envio via PHP"; //MENSAGEM NO FORMATO TXT
		for ($i = 0; $recipients[$i] != NULL; $i++) {
			$mail->AddAddress($recipients[$i], "Recipient"); //E-MAIL DO DESINATÁRIO, NOME DO DESINATÁRIO
		}
		if(!$mail->Send()) {
			$status = "Your message couldn't be sent. Error: " . $mail->ErrorInfo;
			return;
		} else $status = "Your message was sent successfully!";
	//echo $mail->Body;
	}
	
	public function login($POST, $table, $names=array('user', 'password', 'enter')) {
		$con = $this->con;
		global $status;
		$idSession = $this->idSession;
		$cookie_url = $this->cookie_url;
		$cookie_https = $this->cookie_https;
		$enter = $names[2];
		if ($POST[$enter] !== NULL) {
			$login = $names[0];
			$sn = $names[1];
			$user = mysqli_real_escape_string($con, $POST[$login]);
			$password = mysqli_real_escape_string($con, $POST[$sn]);	
			$p = array();
			$p = $this->GetPrimarykeys($table);
			$pr_key = $p[0][0];
			$sql = "select $pr_key from $table where $login = '$user' and $sn = '$password'";
			$rs = mysqli_query($con, $sql);
			$msg = '';
			if ($row = mysqli_fetch_array($rs)) {
				$_SESSION[$idSession] = $row[$pr_key];
				setcookie($idSession, '', time()-10, '/', $cookie_url, $cookie_https);
			}
			else {
				$status = 'O usu&aacute;rio ou a senha est&atilde;o incorretos.';
			}
		}
	}
	public function login2($POST, $table, $names=array('user', 'password', 'register_date', 'enter')) {
		$con = $this->con;
		global $status;
		$idSession = $this->idSession;
		$enter = $names[3];
		$erro = false;
		if ($POST[$enter] !== NULL) {
			$login = $names[0];
			$sn = $names[1];
			$register_date = $names[2];
			$user = mysqli_real_escape_string($con, $POST[$login]);
			$password = mysqli_real_escape_string($con, $POST[$sn]);	
			$data = date('Y-m-d H:i:s');
			$p = array();
			$p = $this->GetPrimarykeys($table);
			$pr_key = $p[0][0];
			$sql = "select $pr_key from $table where $login = '$user' and $sn = '$password'";
			$rs = mysqli_query($con, $sql);
			$msg = '';
			$row = mysqli_fetch_array($rs);
			if (!$row && !empty($user) && !empty($password)) {
				$sql = "insert into $table ($login, $sn, $register_date) values ('$user', '$password', '$data')";
				//echo $sql;
				mysqli_query ($sql);
				$sql = "select max($pr_key) from $table";
				$rs = mysqli_query($con, $sql);
				$row = mysqli_fetch_array($rs);	
				$pr_key = "max($pr_key)";
			} elseif (!$row) {
				$erro = true;
				$status = 'Non-registered user.';
				$this->write_status();
			}
			if (!$erro) {
				$_SESSION[$idSession] = $row[$pr_key];
			}
		}
		return $erro;
	}
	
	public function logout () {
		$idSession = $this->idSession;
		$_SESSION[$idSession] = NULL;
		session_destroy();
	}
	public function desmembraPaginas ($page, $pages, $i) {
		$pages[$i] = $page;
		$os = 0;
		$arq = open_file($page);
		$pos = strpos($arq, "include", $os);
		while ($pos !== FALSE) {
			$ini = $pos+9;
			$cont = strpos($arq, ";", $ini) - $ini - 1;
			$p = substr($arq, $ini, $cont);
			$i++;
			$pages = $this->desmembraPaginas ($p, $pages, $i);
			$os = $ini+$cont;
			$pos = strpos($arq, "include", $os);
		}
		return $pages;
	}
	public function procura ($palavras, $pages, $excluir) {
		$con = $this->con;
		$variaveis1 = array("codigo", "palavras");
		$types1 = array("integer", "varchar");
		$maxlengths1 = array("", "512");
		$table1 = "pesquisas";
		$formTable1 = array($variaveis1, $types1, array(), array(), array(), $table1,	$maxlengths1);
		$this->createTable($formTable1, 1);
		mysqli_query($con, "insert into pesquisas (palavras) values ('$palavras')");
		$palavras2 = array();
		$palavras2 = explode(" ", $palavras);
		$offset = 0;
		$i = 0;
		$paragrafo = array();
		$args = array();
		$j = 0;
		$i = 0;
		$goto = array();
		foreach ($pages as $page) {
			$pages = array();
			$pages = $this->desmembraPaginas($page, $pages, 0);
			foreach ($pages as $page) {
				if (!in_array($page, $excluir)) {
					$arq = $this->open_file($page);
					if (strpos($arq, "<p>") !== FALSE){
						$paragrafos = array();
						$paragrafos = explode("</p>", $arq);
						foreach ($paragrafos as $p) {
							$inicio = strpos($p, '<p>');
							$cont = strlen($p)-$inicio;
							$paragrafo[$i] = substr($p, $inicio, $cont);
							$goto[$i] = "<a href='$page' target='_blank'>Ver mais</a></p>" ;
							$i++;
						}
					}
					$OS = 0;
					$pos = strpos($arq, "\"select ", $OS);
					while ($pos !== FALSE) {
						$ini = $pos+1;
						$cont = strpos($arq, "\"", $ini)-$ini;
						$sql = substr($arq, $ini, $cont);
						$args = $this->select($sql);
						foreach ($args as $arg) {
							foreach ($arg as $a) {
								if (strpos($a, "<p>") === FALSE)	continue;
								$paragrafos = array();
								$paragrafos = explode("</p>", $a);
	//echo $a;
								foreach ($paragrafos as $p) {
									$inicio = strpos($p, '<p>');
									$cont = strlen($p)-$inicio;
									$paragrafo[$i] = substr($p,
									$inicio, $cont);
									$goto[$i] = "<a href='$page' target='_blank'>Ver mais</a></p>" ;
	//echo $paragrafo[$i];
									$i++;
								}
							}
						}
						$OS = $ini+$cont;
						$pos = strpos($arq, "mysqli_query", $OS);
					}
				}
			}
		}
		$result = "";
		$achou = FALSE;
		$i = 0;
		$maisusadas = array('a', 'e', 'o', 'as', 'os', 'à', 'às', 'de', 'em', 'da', 'das', 'do', 'dos', 'na', 'nas', 'no', 'nos', 'com');
		foreach ($paragrafo as $p) {
			foreach ($palavras2 as $w) {
				if (!in_array(strtolower($w), $maisusadas)) {
					while ($offset !== FALSE) {
						if ($offset < strlen($p) ) {
							$pos = strpos (strtoupper($p), strtoupper($w), $offset);
							if ($pos !== FALSE) {
								$achou = TRUE;
								$p = $this->insere($p, "</b></font>",
								$pos+strlen($w));
								$p = $this->insere($p, "<font color='green'><b>", $pos);
	//echo "$p, $w, $offset<br>";3
							}
						}
						else break;
	//echo $pos."<br>";
						$offset = $pos;
						if ($offset !== FALSE) {
							$offset += 35;
						}
					}
				}
				$offset = 0;
			}
	//echo $achou;
			if ($achou) {
				$result .= $p.$goto[$i];//."&nbsp;<a href='".$args[$i][1]."'>Ir para apágina deste conteúdo</a>";
			}
			$achou = FALSE;
			$i++;
		}
	//echo $result;04w4
		return "<span style='margin: 15px;'>".$result."</span>";
	}
	
	
	
	
	//PROBLEMA DETECTADO: A FUNÇÃO NÃO TRABALHA COM A VARIÁVEL $_FILES
	
	
	/*
	Índices de $formTabelas e suas correspondências
	
	0 - campos de tabelas e nomes de imputs
	1 - Tipos de dados no BD
	2 - Títulos dos inputs
	3 - Os inputs do formulário
	4 - Endereços para onde arquivos vão
	5 - Nome da tabela
	6 - Tamanhos máximos dos campos e inputs
	*/
	
	public function codeGenerator() {
		$code = '';
		$c = '';
		for ($i = 0; $i < 12; $i++) {
			$Aa0 = rand(0, 2);
			switch ($Aa0) {
				case 0:
					$c = chr(rand(65, 90));
					break;
				case 1:
					$c = chr(rand(97, 122));
					break;
				case 2:
					$c = chr(rand(48, 57));
					break;
			}
			$code .= $c;
		}
		return $code;
	}
	
	public function validateForm ($POST, $conditions) {
		$status = '';
		$keys = array_keys($POST);
		$valid = true;	
		for ($i = 0; $i < count($keys); $i++) {
			$k = $keys[$i];
			$value = $POST[$k];
			$value = addslashes($value);
			$cond = $conditions[$k];
			$notRegExp = array("dd/mm/yyyy", "hh:mm");
			if (!empty($cond)) {
				if (!in_array($cond, $notRegExp)) {
					if (is_int($cond) && strlen($value) < $cond) {
						$status = "O campo $k requer no m&iacute;nimo $cond caracteres v&aacute;lidos.";
						$valid = false;
						break;
					} elseif (is_string($cond)) {
						switch ($cond) {
							case 'url' : 
								$cond = '/^(https?:\/\/)?(w{2,3}\d\.)?(([a-z\d]([a-z\d-]*[a-z\d])*)\.)+([a-z]{2,})(\.[a-z]{2})?(\/[-a-z\d%_.~+]*)*(\?[a-z\d%_.;&~+=-]*)?(\#[-a-z\d_]*)?$/'; 
								$value = strtolower($value);
							break;
							case 'email' : 
								$cond = '/^([a-z\d]([a-z_.\d-]*[a-z\d])*)@(([a-z\d]([a-z\d-]*[a-z\d])*)\.)+[a-z]{2,}(\.[a-z]{2})?$/';
								$value = strtolower($value);
							break;
							case 'number' :
								$comma_pos = strpos($value, ',');
								if ($comma_pos !== -1) {
									$value = $this->substitui($value, '.', $comma_pos, 1);
								}
								$nan = !is_int($value) && !is_float($value);
							break;
						}
						if (!preg_match($cond, $value) || $nan) {
							$status = "Forne&ccedil;a um $k v&aacute;lido.";
							$valid = false;
							break;
						}
					}
				} elseif ($cond == "dd/mm/yyyy") {
					$cond = '/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/[12][0-9]{3}$/';
					$DMY = explode('/', $value);
					$error = false;
					$months30 = array('04', '06', '09', '11');
					if (!preg_match($cond, $value)) {
						$error = true;
					} elseif (in_array($DMY[1], $months30) && ($DMY[0] > 30))
						$error = true;
					elseif ($DMY[1] == 2) {
						if (($DMY[0] > 28) && (($DMY[2]%4) != 0))
							$error = true;
						if (($DMY[0] > 29)&& (($DMY[2]%4) == 0))
							$error = true;
					}
					if ($error) {
						$status = "Forne&ccedil;a uma data v&aacute;lida para $k";
						$valid = false;
						break;
					}
				} elseif ($cond == "hh:mm") {
					$cond = '/^([01][0-9]|2[0-3]):([0-5][0-9])$/';
					if (!preg_match($cond, $value)) {
						$status = "Forne&ccedil;a uma hora v&aacute;lida para $k";
						$valid = false;
						break;
					}
				}
			}
		}
		return ($valid) ? $valid : $status;
	}
	/*With the function adminPage(), you can create forms for various purposes, such as updating web content and signing up, and at the same time, process the data of such forms to be inserted into the database, updated, or deleted from it, both text data and binary data (files). This same function also fills forms with data coming from the database so they can be updated or deleted. Also, it creates automatically the tables of the databases of a website project and their relationships. 
	
	$POST receives the PHP variable $_POST as argument.
	$FILES receives the PHP variable $_FILES as argument.
	$SESSION receives the PHP variable $_SESSION as argument.
	$formTable1, $formTable2 and $formTable3 contain the data used to build the form and the database information that is related to the form. The table of $formTable1 is related to the table of $formTable1 in a 1 x N relation if $formTabela3 is null, or in a relation M x N if $formTable3 contains data. The table of $formTable3 is the relation M x N of the tables 1 and 2.
	$select contains the SQL code and other information necessary to get data from the database.
	$public is a boolean variable that is true if the form is defined to be a public form. It is false if the form is a restricted access form.
	$JSIndex is used to index Javascript variables, so that the function adminPage can be used more than once to create one only form.
	*/
	
	public function adminPage ($POST, $FILES, $SESSION, $formTable1, $formTable2, $formTable3, $public=FALSE) {
		$con = $this->con;
		//global  $star; //the content of the variable $star is a red star, used to identify required fields to be filled in the form.
		$PK = $this->GetPrimaryKeys($formTable1[5]); // $PK receives the number of primary keys of table 1 and also their names.
		$args = $formTable1[0]; //$args receives $formTable1[0], which contains the names of the fields of the database table 1. These names are also the names of this form fields.
		$values = array(); //$values receives all the data coming from de database as a result of running the given SQL code. If no SQL code is run then $values gets the form data that is in $POST. 
		global $code; //$code is used for database transactions involving the value of the primary key of the table 1
		$code = 0;
		$i = 0;
		//The 5th element of the array $formTableN is the name of the table N.
		$table2 = $formTable2[5]; 
		$table3 = $formTable3[5];
		
		/*The function adminPage can be used more than once to create a form and its relation to a database. If we have the inputs of $formTable1 repeated in the form for N times, which means that adminPage() will be called N times on the page, then there must be an index that is related to the current instance of these inputs, and this index is the 8th element of the array $formTable1, and the varable $indTab1 will receibe this value.*/
		$indTab1 = $formTable1[8];
		if (empty($indTab1) || $indTab1 == 0) {$indTab1 = '';}
		$datetime_exists1 = false;
		
		while ($args[$i] != NULL) {
			eval("global \$".$args[$i].";"); //The values contained in $args will be transformed into PHP global variables to be used for the form. 
			$s = $args[$i].$indTab1;
			//The code snippet below fills $values with the values acquired from the submitted form and relative only to the table 1.
			if ($POST[$s] !== NULL || $FILES[$s] !== NULL) {
				if ($formTable1[3][$i] != 'file') {
					$values[0][$i] = $POST[$s];
				} else {
					$values[0][$i] = $FILES[$s];
				}
			}
			$i++;
		}
		$values2 = array(); /*$values2 gets only the data relative to the table 2. This variable is necessary for saving operation in table 2. The variable $values also gets the data that belong to table 2, and all its values are used to fill the form again after it is submitted for a database operation. */
		$args2 = $formTable2[0]; 
		$secondTablePK = $i; //This variable will be used as an index that refers to the primary key of the second table in the array $values.
		$k = $i;
		/*If there is a second table, which will be linked to the first one by a foreign key, the code snippet below feeds $values2 with the values of that second table, coming from the submitted form, which can receive multiple data records, as shown below. Note that the $values variable also receives the values of table 2, filling the columns that were not filled with values. Also note that the $k offset, which causes $values to be filled in unfilled places, is added with the value of $auxK variable. This is because each recorded image generates a thumbnail of itself, which is why $auxK needed to be incremented every time an image was detected in the previous code snippet. The usefulness of this will be better understood later on. The variables $values and $values2 will receive values related to the table 2 from the submitted form only if they won't receive values from the database. This happens if $POST['butPres'] equalt to "Select". 
		*/
		$datetime_exists2 = false;
		$amountTable2 = $formTable2[8]; /* This is the number of times the inputs of table two are repeated on the page in order to create multiple records of it associated with one record of table 1.*/
		if (empty($amountTable2)) $amountTable2 = 0;
		if ($args2 != NULL) {
			$i = 0;
			while ($args2[$i] != NULL) {
				eval("global \$".$args2[$i].";"); //The values contained in $args2 will be transformed into PHP global variables to be used for the form.
				$s = $args2[$i];
				$j = 0;
				$s .= ''.$indTab1;
				/*If the current form is repeated for N times on the page, then $indTab1 is not empty, and an index of $POST related to the second table will be something like name1_3.*/
				if (!empty($indTab1)){
					$s .= "_";
				} 
				/*The indexes of $POST related to the table 2 are ended by a number. The index $s.'0' won't be NULL only if the values coming from the form were taken from a database to fill it up. If $POST[$s.'0'] is NULL, it's because the input whose name attribute is equal to $s.'0' does not extist.*/
				if ($POST[$s.'0'] === NULL && $FILES[$s.'0'] === NULL) {
					$j = 1;
				}
				/*Now if the table 2 is repeated for N times, the arrays $values and $values2 get the values of each instance of the current form input.*/
				if ($formTable2[3][$i] !== 'file') {
					if ($formTable2[3][$i] !== "radio") {
						while ($j <= $amountTable2) {
							if (!empty($POST[$s.$j])) {
								$values[$j][$i+$k] = $POST[$s.$j];
								$values2[$j][$i] = $POST[$s.$j];
							}
							$j++;	
						} 
					/*When you have checkboxes or radios in the form, you have to select from them one or more than one values among a certain amount of choices. These choices are data stored in a database. We have N choices for one form. This is a relation 1 x N, which means that the data that will be the choices in a radio or a checkbox system must come from the table 2, which have a relation N x 1 with the table 1, as seen previously. If the type of the current form input is radio, we have multiple choices with the same name for their name attribute, and so we get one only value from this radio system, as shown below.*/
					} elseif ($POST[$s] !== NULL && $POST[$s] !== '') {
						$j = 0;
						if ($POST[$s.'0'] === NULL) $j = 1;
						$values[$j][$i+$k] = $POST[$s];
						$values2[$j][$i] = $POST[$s];
					} 
				} else {
				// If the current input type is file, then we get the data from the variable $_FILES, and not from $_POST.
					while ($j <= $amountTable2) {
						if (!empty($FILES[$s.$j]['name'])) {
							$values[$j][$i+$k] = $FILES[$s.$j];
							$values2[$j][$i] = $FILES[$s.$j];
						} 
						/* There may be situations where the value being saved is a file, but the form field containing its name is not of the type file. In this case, the value is a string containing a file name, and this is not a case where a file will actually be saved, but only its name in the database. The code snippet below is a solution for saving in the database records that contain files whose names didn't come from a file form input. */
						elseif (!empty($POST[$s.$j])) {
							$values[$j][$i+$k]['name'] = $POST[$s.$j];
							$values2[$j][$i]['name'] = $POST[$s.$j];
						}
						$j++;
					}
				}
				$i++;
			}
		}
		$sel = $args[1];
		
		/*For the PHP variable $_SESSION, an index must be defined. The global variable $idSession contains the name of this index, and one only form can work with more than one sessions. If $idSession is not defined in the PHP file that calls the function adminPage(), then its value will be 'user'.*/
		$idSession = $this->idSession;
		$Ses = !empty($SESSION[$idSession]);
		$button = 'butPres';
		global $status; //The global variable $status is used to notify the result of a certain form operation.
		$status ='';
		$table = $formTable1[5]; //$formTable1[5] contains the name of the table 1.
		//The 'if' structure below is used to define whether the form is or isn't in the editing mode, that is, if the data that fills it will update a record in the database or insert a new record into it. If the form button pressed is the one used to select data from the database then $is_editing will be true in a further code snippet.
		$edit = $this->editingMode ($table, $values[0], $formTable1[1]);
		$is_editing = $edit[0];
		$it1 = ($indTab1 === '') ? '' : $indTab1.'_';
		
		//IMPLEMENTATION OF DATABASE ADMINISTRATION BUTTONS
		if ($POST[$button] != NULL) {
			
			
			//SAVE
			
			//The saving operation will be allowed to happen if the form is public or if the one who uses it to save data logged in the site before this operation.
			
			if ($POST[$button] == 'Save' && ($Ses || $public)) {
				$willSave = $this->willSave;
				/*When we have to save a new password through the form, we have to type it twice. The 'for' structure below tests if the new password and the confirmation password match. If not, a message of error is issued.*/
				for ($j = 0; $formTable1[3][$j] != NULL; $j++) {
					if ($formTable1[3][$j] == "password") {
						if ($values[0][$j] != $values[0][$j+1]) {
							echo "ERRO: Sua senha e sua senha de confirma&ccedil;&atilde;o s&atilde;o diferentes.";
							$willSave = false;				
						}
						break;
					}
				}
				$code = 0;
				if ($willSave) {
					$insert = true;
					/*$code is the primary key value of table 1, and the 'if' structure below indicates that there will be an updating in this write operation, instead of an inserting, if $is_editing is true, that is, if the form is in the editing mode.*/
					if ($is_editing) {
						$code = $values[0][0];
						$insert = false;
					}
					$fields = $args;
					$types = $formTable1[1]; //You must supply the function save(), below, with the data types of the table where data will be saved.
					$addresses = $formTable1[4]; //$formTable1[4] will receive the vector of the addresses of the photos that there could be to be saved in the database.
					/*The function save() will write to the database the form data, whether it came from the database or provided to be inserted, and it will return an array with a value that says if the form is or isn't in the editing mode and with the value of the first primary key that is generated from the insert or update SQL operations. Is there is no user session, then no data can be updated in the database, but they can be inserted into it if the form is public.*/
					if ($Ses || $insert) {
						$status = "O registro foi salvo com sucesso.";
						/*The if below contains a code that is a remedy to prevent a form from saving empty values for passwords, since a password can only be saved if it contains characters. This code runs only if there is a password input among the form inputs.*/
						if (is_array($formTable1[3]) && in_array("password", $formTable1[3])) {
							$cont = 0; 
							while ($formTable1[3][$cont] !== "password")
								$cont++;
							/*If no password was provided in the password input, the 'if' below causes the field, type and value related to the password to desapear, so that no empty value will be saved for it.*/
							if (empty($values[0][$cont])) {
								$inc = 2;
								if ($formTable1[3][$cont+1] !== "password") $inc = 1;
								while ($fields[$cont] !== NULL){
									$fields[$cont] = $fields[$cont+$inc];
									$types[$cont] = $types[$cont+$inc];
									$values[0][$cont] = $values[0][$cont+$inc];
									$cont++;
								}
							}
						}
						
						$edit = $this->save($table, $fields, $types, $values[0], $addresses);//here we have the save operation itself.
						$is_editing = $edit[0]; //$is_editing stores a value that says if the form is os is not in the editing mode.
						/* If $values[0][0] is an empty value, it means that table 1 has one only primary key and that it is auto-increment. Also, it means that the primary key of the current record was generated in the saving operationm */
						if (empty($values[0][0])) 
							$values[0][0] = $edit[1]; 
						if (mysqli_error($con)) $status = "Error on saving: ".mysqli_error($con);	
						$code = $values[0][0];
						/*If the form data has been saved successfully, then a session is automaticly created in the code snippet below, if the form is public, whose value is the value of the first primary key related to the data saved. If the form is restricted access, it makes no sense creating a session, because such forms can be handled only within a session created previously.*/
						if ($public) {
							$_SESSION[$idSession] = $code;
						}
						//If table 2 exists, there will be a save operation in it.
						if ($table2 != NULL) {
							/*Below, we have variables being supplied with the values to be used in the function save() for table 2, which can receive more than one data record.*/
							$fields2 = $args2; 
							$types2 = $formTable2[1]; 
							$addresses2 = $formTable2[4]; 
							$value = array();
							$value[0] = $values[0][$k];
							/*The editing mode cannot be checked for the whole form at once. We can only check if the form is in the editing mode in relation to one only table. The code snippet below checks if the form inputs related to the table 2 are in the editing mode.*/	
							$edit2 = $this->editingMode ($table2, $value, $formTable2[1]);
							$is_editing2 = $edit2[0];
							/*The index $i starts at 1 if the inputs of the table 2 are not editing data, and not at 0. This is because when the table 2 inputs are in the editing mode, the names of the inputs of the first instance of table 2 end with the number 0, instead of 1. This will be better understood later.*/
							$i = 0;
							if (!$is_editing2) {
								$i = 1;
							}
							/*The structure below, including the while, will write the data in table 2 and in table 3 if it exists, which creates a relation m X n between tables 1 and 2. Note that when there is a table 2, both tables 1 and 2 have only one primary key each, even though the function save() allows that the table where data will be saved through it has more than one primary key. If there is a table 3, its first two data will be foreign keys referring to the primary keys of tables 1 and 2, but if there is no table 3, table 2 will have a foreign key referring to the primary key of table 1, which will be necessarily its second field. $q is the index of the column of $values2 that contains the values of the main table 2 field.*/
							$q = 1;
							if ($table3 == NULL) {
								$q = 2;
								$values2[$i][1] = $code;
								$values[$i][$k+1] = $code;
							} 
							$field = $values2[$i][$q];
							/* Below, the data taken from the form and related to table 2 will be stored in the database only when the table 2 main visible value ($field) is not empty.*/
							while ($i <= $amountTable2) {
								if (!empty($field)) {
									$edit2 = $this->save($table2, $fields2, $types2, $values2[$i], $addresses2); //as seen previously, $values2 is used for saving operation in table 2.
									$values2[$i][0] = $edit2[1];	
									$code2 = $values2[$i][0]; //$code2 is the primary key of the table 2, which will always have only one auto-increment PK.
									$values[$i][$k] = $code2;
									/*If there is a table 3, then the relation between tables 1 and 2 is m X n, and not 1 X n. The save operation in the table 3 will not be done with the function save(), but with the PHP function mysqli_query($con, ).*/
									if (!empty($table3)){
										$fields3 = $formTable3[0]; //$fields3 receives the names of the table 3 fields.
										$c = array();
										/*The SQL code to insert into table 3 the relation between table 1 and table 2 is now created. Its first fields are the two primary keys, which are foreign keys referring tho the primary ones of table 1 and 2. If the table 3 have other fields, they are taken from $formTable3[0] to build the SQL code as shown below. */
										$sql2 = "insert into $table3 (".$fields3[0].", ".$fields3[1];
										for ($j = 2; $formTable3[0][$j] !== NULL; $j++) {
											$c[$j] = $formTable3[0][$j];
											$sql2 .= ", ".$c[$j];
										}	
										/*Now we get the data to be inserted into table 3. The data that are not the values of its primary keys are taken from $POST, and the data which are varchar or date types must be surrounded by '' in the SQL code.*/
										$sql2 .= ") values ($code, $code2"; 		
										for ($j = 2; $formTable3[0][$j] !== NULL; $j++) {
											$c2 = $c[$j];
											$v = $POST[$c2.$it1.$i];
											if ($formTable3[1][$j] === "varchar" || strpos($formTable3[1][$j], "date") !== FALSE) $v = "'".mysqli_real_escape_string($con, $v)."'";
											$sql2 .= ", $v";
										}	
										$sql2 .=	")";
										mysqli_query($con, $sql2);			
									}
								}
								$i++;  
								$field = $values2[$i][$q]; 
								/* If there is no table 3, the relation between tables 1 and 2 will be 1 X n, and the columns of $values and $values2 that contain the foreign key of table 2 must be filled with the value of the current table 1 primary key, so that the records of table 2 can be correctly stored.*/
								if (empty($table3)) {
									$values2[$i][1] = $code;
									$values[$i][$k+1] = $code;
								}
							}
						}//end table2 storing
					}//end table2 test	
				}//end storing				
			}//end saving
			
			//Delete operation
			
			elseif ($POST[$button] == "Delete" && $Ses) {
				$table1 = $formTable1[5]; // $table1 receives the first table.
				$table2 = $formTable2[5]; // $table2 receives the second table, associated with $table1 in a relation 1 x N of M x N.
				$table3 = $formTable3[5]; // $tabl3 will exist to create the relation M x N between $table1 and $table2, if they are related this way.
				/* $PKfields receives an array with the names of the fields used to define the records that will be deleted from each table. Its first value is the name of the unique or the first primary key of table 1. This primary key will be used in the Delete operation anyway. */
				$PKfields = array($formTable1[0][0]);
				$types = array($formTable1[1][0]); // $types contains the types of the fields cited previously.
				$PK0 = $PKfields[0];
				$values = array($POST[$PK0.$indTab1]); // $values contains the values of the fields cited previously, taken from the variable $_POST.
				/* If only the table 1 exists, then it can have more than one primary keys, and all the fields whose names are in $PKfields will be these primary keys. Also, $types will contain the types of these fields, and $values will contain the values assigned to them.*/
				if (empty($table2) && empty($table3)) {
					for ($i = 1; $i < $PK[1]; $i++) {
						$PKfields[$i] = $PK[0][$i];
						$types[$i] = $formTable1[1][$i];
						$PKi = $PKfields[$i];
						$values[$i] = $POST[$PKi.$indTab1];
					}
					/* The function delete() will create the SQL code that deletes the current record of table 1, which is associated with all the form in this case. The current record is defined by the values of the primary keys of $table1. The function delete() also delete the files whose names added to their addresses on the server are among the data of the record that is being deleted, if they exist.*/
					$this->delete ($table1, $PKfields, $types, $values);
				/* If at least the table 2 also exists, then the first table must have only one primary key, which is related to one of the foreign keys of $table2 or one of the foreign keys of $table3. */
				} else {
					/* If only the first and the second table exist, then the primary key of table 1 will be related to one of the foreign keys of table 2, and all its records associated with the current record of table 1 will be deleted.*/
					if (empty($table3)) {
						/* $PKfields[1] receives the foreign key of table 2, that refferences the primary key of table 1. */
						$PKfields[1] = $formTable2[0][1]; 
						$this->delete ($table2, $PKfields[1], $types[0], $values[0]);
					/* If the table 3 exists, it will be the relation M x N between $table1 and $table2, and each of these two tables will have only one primary key, which will be related to foreign keys of table 3. */
					} else {
						// In this case, the second value of $PKfields will be the name of the primary key of $table2.
						$PKfields[1] = $formTable2[0][0];
						/* $PKfields[2] and $PKfields[3] receive names of foreign keys of table 3, which are related to the primary keys of table 1 and table 2.*/
						$PKfields[2] = $formTable3[0][0];
						$PKfields[3] = $formTable3[0][1];
						/* The values that will be assigned to the foreign keys above in one of the delete queries are the current values of the primary keys of tables 1 and 2. Then, $values[1] receives the current value of the primary key of table 2, and its type is assignet to $types[1].*/
						$types[1] = $formTable2[1][0];
						$PK2 = $PKfields[1];
						$values[1] = $POST[$PK2.$it1.'0'];
						/* The first thing to do is to delete the relation between the current records of tables 1 and 2 stored in $table3.*/
						$this->delete ($table3, array($PKfields[2], $PKfields[3]), array($types[0], $types[1]), array($values[0], $values[1]));
						/* Then we delete a record from $table2 where its primary key equals its current value.*/
						$this->delete ($table2, $PKfields[1], $types[1], $values[1]);
					}
					/* Finally we delete a record from $table1 where its unique primary key equals its current value.*/
					$this->delete ($table1, $PKfields[0], $types[0], $values[0]);
				}
				/* After deleting a record and the records related to it, the form must be empty for the insertion of a new record. This means that the same code executed when the New button is pressed must be executed here too.*/
				if (!mysqli_error($con)) {
					$status = "Este registro foi exclu&iacute;do com sucesso.";
				} else $status = "Error on deleting: ".mysqli_error($con);
				$i = 0;
				$values = array();
				while ($args[$i] != NULL) {
					$i++;
				}
			}//end delete
			elseif (!$public && !$Ses) {
				$status = "Fa&ccedil;a o login para operar este formul&aacute;rio.";
			}
		} //end buttons
		
		/*When a table 2 exists, as has been said, the form can repeat its fields several times to make multiple records in this table. The structure below serves to delete a single record from table 2 through a Delete button associated with each of these records. Now the $secondTablePK variable is used to index the primary key of this table in the resultset below. The value of this primary key will be assigned to $code and the name of each of these Delete buttons will be 'delete'.$code. The code snippet below identifies which of them was pressed, if this happened.*/
		if (!empty($table2)) {
			$i = 0;
			while ($values[$i][$k] !== NULL) {
				try {
					$code = $values[$i][$k];    
					if ($POST['delete'.$code] != NULL) {
						$this->delete ($table2, $formTable2[0][0], $formTable2[1][0], $code);
						//mysqli_query($con, "delete from ".$formTable2[5]." where ".$formTable2[0][0]." = $code");
						$status = "Registro de $table2 foi exclu&iacute;do com sucesso.";
						break;
					}
				} catch (Exception $e) {
					$status = $e->getMessage();
				} 
				$i++;
			}
		}
		//return $status;	
	}
	
	/*
	Índices de $formTabelas e suas correspondências
	
	0 - campos de tabelas e nomes de imputs
	1 - Tipos de dados no BD
	2 - Títulos dos inputs
	3 - Os inputs do formulário
	4 - Endereços para onde arquivos vão
	5 - Nome da tabela
	6 - Tamanhos máximos dos campos e inputs
	*/
	
	public function adminPageAjax ($POST, $FILES, $SESSION, $formTable1, $formTable2, $formTable3, $select, $public=FALSE, $JSIndex=NULL) {
		$con = $this->con;
		$args = $formTable1[0]; //$args receives $formTable1[0], which contains the names of the fields of the database table 1. These names are also the names of this form fields related to table 1.
		$args2 = $formTable2[0]; //$args2 receives $formTable2[0], which contains the names of the fields of the database table 2. These names are also the names of this form fields related to table 2.
		$table = $formTable1[5]; //$formTable1[5] contains the name of the table 1.
		$table2 = $formTable2[5]; 
		$table3 = $formTable3[5];
		if (!empty($formTable1)) $this->createTable ($formTable1, 1); 
		//If the tables used in this form of database manipulation do not exist, they will be created, as well as their foreign keys
		if (!empty($table2) && empty($table3)) {
			$this->createTable ($formTable2, 1);
			addForeignKey ($table2, $formTable2[0][1], $formTable1[5], $formTable1[0][0]);
		} elseif (!empty($table3)) {
			$this->createTable ($formTable2, 1);
			$this->createTable ($formTable3, 2);	
			$this->addForeignKey ($table3, $formTable3[0][0], $formTable1[5], $formTable1[0][0]);
			$this->addForeignKey ($table3, $formTable3[0][1], $table2, $formTable2[0][0]);
		}
		/*The function adminPage can be used more than once to create a form and its relation to a database. If we have the inputs of $formTable1 repeated in the form for N times, which means that adminPage() will be called N times on the page, then there must be an index that is related to the current instance of these inputs, and this index is the 8th element of the array $formTable1, and the varable $indTab1 will receibe this value.*/
		$indTab1 = $formTable1[8];
		$sql = $select[0]; 
		$PK = $this->GetPrimaryKeys($table);
		$i = 0;
		while ($args[$i] != NULL) {
			$s = $args[$i].$indTab1;
			if ($this->is_image($FILES[$s]['name'])) {
				$auxK++; //Notice that the $auxK value increments whenever the data type in $formTable1[3] is an image
			}
			$i++;
		}
		$k = $i+$auxK;
		?>
		<div id="status"></div>
		<?php
		//form_datetime_create ($values[$j][$i], $formTable1[1][$i]);
		
		$i = 0;
		
			
		//CREATING AND FILLING UP THE FORM
		
		
		/*The string variables $list1 and $list2 store the values that best identify to the user each record of their respective tables (1 or 2). Through them, the desired record is chosen to make the desired changes in it. Note that the code snippet below will run only if the parameter $JSIndex are not null. This will be better understood below.*/
		$n = 0;
		$it1 = $indTab1;
		if (!empty($indTab1)) $it1 .= '_';
		/*In the structures below, the names of the fields of tables 1 and 2 and their values are assigned to Javascript variables.*/
		echo "<script language='javascript'>\n";	
		/*In the following 'for', the Javascript array args receives the names of the inputs related to table 1. These are also the names of the fields of table 1. Note that the starting index of args is not 0, but $r1. When the funcion adminPage() is called more than once in one only page, args must receive the names of all the form inputs. Each call of adminPage() creates part of these inputs, but they add the names of these inputs to one only array args. So, there must be a way not to repeat the indexes of args whenever this function is called on the page. This function returns the last index of args plus 1, and when it is called again on the page, this last index plus 1 must be one of the arguments of the parameter $JSIndex and the starting index of args in the current call of adminPage(). This is how we can make args receive all the names of the form input. The variable args and the variables ValuesTable0, ValuesTable1, ValuesTable2... are useful for manipulation of the form on the client side. */
		global $a;
		$key = 0;
		echo "Keys[$a] = [];";
		for ($i = 0; !empty($args[$i]); $i++){
			echo "Keys[$a][".$key."] = '".$args[$i]."$indTab1';\n";
			$key++;
		}
		/*The Javascript variables ValuesTable0,... ValuestableN, receive from $values all the values related to the tables this form is associated with. Each ValuesTableI receives a row of $values. Note that the numbers that end the names of ValuesTable0,... ValuesTableN starts at $r2 in the current call of this function, which is a variable that, like the variable $r1, is used not to repeat these numbers with each call of this function adminPage(), so that every ValuesTavleI can receive all the data from the whole form. So, as seen before, $r1 and $r2 receives the starting indexes of args and ValuesTableI.*/
		for ($i = 0; $values[$i][0] !== NULL; $i++) {
			for ($j = 0; $values[$i][$j] !== NULL; $j++) {
				echo "Keys[$a][".$key."] = '".$args2[$j].$it1."$i';\n";
				$key++;
			} 		
		} 
		echo "</script>\n";
		/*Below we have the form inputs referring to table 1 being constructed. For each of them there is a label. The types of these inputs are contained in the $formTable1[3] vector, their names are in $args and their values in $values. The size of each of these inputs that are not textareas is the value in $maxlength if it's less than or equal to 50, and 50 if it is more than 50 ($max = 50), but the inputs maxlengths will always be equal to the value contained in $maxlength. There is also a variable called $properties, which receives the values provided in the $formTable1[7] vector, which contains other HTML properties that you want to assign to the inputs. Each div that surrounds each component of the form has an id and/or a class defined in this function. You must be aware of these values for you to create the CSS styles of this form.*/
		$i = 0;
		$max = 50;
		global $html; // This global variable receives the HTML code of all the form inputs.
		$html = "<div id='data$indTab1'>"; /* This div surrounds all the inputs associated with the tables of the current call of this function. Let's remember that $indTab1 is the index of the form inputs if they are repeated N times on the page by calling this function N times, and if they are and related to the same tables 1 and 2.*/
		$c = count($formTable1[2]);
		$aux_label = ($formTable1[2][$c-1] === true);
		while ($args[$i] != NULL && $formTable1[3][$i] !== NULL) {
			// The div that surrounds a form input related to table 1 has its id equal to the name of this input.
			$html .= "<div id='d_".$args[$i]."$indTab1'";
			if ($formTable1[3][$i] !== 'hidden') $html .= " class='item'";
			$html .= ">";
			$is_label = ($aux_label || !in_array($formTable1[3][$i], array("text", "password", "textarea")));
			if ($is_label)
				$html .= "<label for='".$args[$i]."$indTab1'>".$formTable1[2][$i]."</label>";
			$maxlength = $formTable1[6][$i]; // The input maxlength can be any size.
			$m = (int) $maxlength;
			if ($m > $max) {
				$size = "$max"; // The input size is limited in $max (=50), as seen before.
			} else $size = $maxlength;
			/*The variable $properties receives from $formTable1[7] a string containing other HTML properties that you pre-defined in order to assign it to each input. Each input has its own set of properties.*/
			$properties = $formTable1[7][$i]; 
			
			/* If you want the form to show any message so that it can be better understood, you can specify an 'input' whose 'type' is "html". It will actually be a hidden input whose value is this message, but it will be shown along with this hidden input, which menans that only it will be visible. Since we don't have a visible input to save such message into the database through this form, it can be defined through a database manager, like the phpmyadmin, or through another form.*/
			if ($formTable1[3][$i] === "html") {
				$html .= "<div class='c'><input type='hidden' name='".$args[$i]."$indTab1' value=''/></div>\n";
			/* If the current input is not a textarea, it can be defined by the HTML tag "input", and its type is in the vector $formTable1[3]. Note that each input is filled with its value, that is in the vector $values[0], When the inputs of this form are empty, it means that the variable $values is also empty.*/
			} elseif ($formTable1[3][$i] != "textarea") {
				$html .= "<div class='c'><input type='".$formTable1[3][$i]."' name='".$args[$i]."$indTab1' id='".$args[$i]."$indTab1' value='' size='$size' maxlength='$maxlength'  $properties ";
				if (!$is_label) $html .= "placeholder='".$formTable1[2][$i]."'";
				$html .= "/></div>\n";
			/*If the type of the current input is "textarea", then it must be created by the tag "textarea"*/
			} else {
				$html .= "<div class='c'><textarea rows=5 cols=40 name='".$args[$i]."$indTab1' id='".$args[$i]."$indTab1' $properties";
				if (!$is_label) $html .= " placeholder='".$formTable1[2][$i]."'";			
				$html .= "></textarea></div>\n";
			}
			$html .= "</div>";
			$i++;
		}
		
		$i = 1;
		//Creation of the fields of table 2 in the same way the forms of table 1 were created.
		$it1 = $indTab1;
		if ($args2 != NULL && $formTable2[3] != NULL) {
			/*If the form inputs associated with table 2 is in the editing mode, it will have one more repetition of the inputs related to table 2 before all the other repetitions, and there will be a '0' in the end of their names, as seen previously. This inputs will never be empty. If their values are text or numerical values, the labels are written and just after each of them the values are written in inputs whose types are in $formTable2[3]. If any of them is an image, first the input is written on the left, whose type is "file", which will not be useful to update the image besite it, that is, the image that will be displayed on the right. These inputs are added to the variable $html.*/
			/* If the variable $indTab1 is not empty, then the inputs of table two are also repeated with every repetition of table 1, by calling this function N times in relation to the same tables 1 and 2. Then the inputs of table 2 will have names like 'name1_2', where the first number is related to the repetitions of the inputs of table 1 and the second number are related to the repetitions of the inputs of table 2 for every instance of table 1 inputs. */
			if ($indTab1 != '') {
				$indTab1 .= '_';
			}
			$j = 0;
			$amountTable2 = (empty($formTable2[8])) ? 0 : $formTable2[8];
			$c = count($formTable2[2]);
			$aux_label = ($formTable2[2][$c-1] === true);
			/*Below we have the writing of the inputs of table 2 just like it was done above, but for $amountTable2 times.*/
			while ($i <= $amountTable2) {
				$j = 0;
				$html .= "<div class='form2' id='form2_$i'>"; // the div that surrounds each repetition of the inputs of table 2
				while ($args2[$j] !== NULL && $formTable2[3][$j] != NULL) {
					$html .= "<div id='".$args2[$j].$indTab1."$i'";
					if ($formTable2[3][$j] !== 'hidden') $html .= " class='item'";
					$html .= ">";// the div that surrounds an input, whose id is equal to the input name.
					$maxlength = $formTable2[6][$j];
					// The inputs maxlengths can be any size, but their sizes are limited in $max (=50).
					$m = (int) $maxlength;
					if ($m > $max) {
						$size = "$max";
					} else $size = $maxlength;
					/* As seen before, the variable $formTable2[7] can be a vector or a matrix, and it contains a different set of properties for each input related to table 2, whether it's a vetor or it's a matrix. But if it's a matrix, then the programmer can define a different set of properties for each repetition of every input associated with table 2.*/
					$properties = $formTable2[7][$j];
					if ($formTable2[7][$i][$j]) $properties = $formTable2[7][$i][$j];
					/* The code below makes a red star (*) appear after labels related to required fields. $req is a variable that receives a value defined by the programmer in the array $formTable2, which is the maximum number of repetitions of each input that is defined as required. If the form is in the editing mode, the first instance of the table 2 inputs created previously are also composed of required fields whenever $req > 0, as seen before, which means that each $req must be decreased by 1.*/
					$is_label = ($aux_label || !in_array($formTable1[3][$i], array("text", "password", "textarea")));
					if ($is_label) 
						$html .= "<label for='".$args2[$j].$indTab1."$i'>".$formTable2[2][$j].'</label>';
					//Now the table 2 inputs are builded for $amountTable2 times just like it was done above.
					if ($formTable2[3][$j] === "html") {
						$html .= "<div class='c'></div>\n";
					} elseif ($formTable2[3][$j] !== "file" && $formTable2[3][$j] !== "textarea") {
						$html .= "<div class='c'><input type='".$formTable2[3][$j]."' name='".$args2[$j].$indTab1;
						if ($formTable2[3][$j] !== "radio") $html .= "$i";
						$html .= "' id='".$args2[$j].$indTab1;
						if ($formTable2[3][$j] !== "radio") $html .= "$i";
						if ($is_label) $html .= "' placeholder='".$formTable2[2][$j];
						$html .= "' value='' size='$size' maxlength='$maxlength' $properties /></div>\n";
					} elseif ($formTable2[3][$j] === "file"){
						$html .= "<div class='c'><input type='".$formTable2[3][$j]."' name='".$args2[$j].$indTab1."$i' id='".$args2[$j].$indTab1."$i' value='' $properties";
						if ($is_label) $html .= " placeholder='".$formTable2[2][$j]."'";
						$html .= "/></div>";
					}
					else {
						$html .= "<div class='c'><textarea rows=3 cols=40 name='".$args2[$j].$indTab1."$i' id='".$args2[$j].$indTab1."$i' $properties></textarea></div>\n";
	
					}	
					$j++;
					$html .= "</div>";
				}
				$i++;
				$html .= '</div>';
			}
			/* For each record of table 2 shown in the form, there is only one record of table 3 that corresponds to it and to the record of table 1, if table 3 exists, that is, if the relation between tables 1 and 2 are M x N. */
			$args3 = $formTable3[0];
			$i = 1;
			if ($table3 !== NULL) {
				while ($i <= $amountTable2) {
					$j = 0;
					while ($args3[$j] !== NULL && $formTable3[3][$j] != NULL) {
						$name = $args3[$j];
						$html .= "<input type='".$formTable3[3][$j]."' name='$name".$indTab1."$i' value=''>";
						$j++;
					}
					$i++;
				}
			}
		}
		$html .= '</div>';
		/*When you call the function adminPage(), it will automatically print the form created by it, unless the programmer defines $select[2] to be equal to "no_print". In this case, the programmer must have in mind that $html, the variable that contains the form, is a global variable, and so the form can be printed only when the programmer wants it to be printed.*/
		if ($select[2] !== "no_print")
			echo $html;
		//$values = transposed_matrix ($values);
		$idSession = $this->idSession;
		?>
		<script language="javascript">
		pub = <?php echo ($public) ? "true" : "false";?>;
		idSession = "<?php echo (empty($idSession)) ? 'user' : $idSession;?>";
		ReadOnly[<?php echo $a;?>] = "<?php $formTable2[9];?>";
		n[<?php echo $a;?>] = <?php echo $PK[1];?>;
		sql[<?php echo $a;?>] = "<?php echo $select[0];?>";
		table[<?php echo $a;?>] = "<?php echo $table;?>";
		table2[<?php echo $a;?>] = "<?php echo $table2;?>";
		table3[<?php echo $a;?>] = "<?php echo $table3;?>";
		indT1[<?php echo $a;?>] = <?php echo (empty($formTable1[8])) ? "''" : $formTable1[8];?>;
		at2[<?php echo $a;?>] = <?php echo (!empty($amountTable2))? $amountTable2: 0; ?>;
		Rr1[<?php echo $a;?>] = <?php echo (isset($JSIndex)) ? $JSIndex[0] : 0;?>;
		Rr2[<?php echo $a;?>] = <?php echo (isset($JSIndex)) ? $JSIndex[1] : 0;?>;
		K[<?php echo $a;?>] = <?php echo (isset($k)) ? $k : 0;?>;
		try {
		<?php
		echo "f1[$a] = [];\n";
		echo "t1[$a] = [];\n";
		echo "inputsT1[$a] = [];\n";
		echo  "adds1[$a]= [];\n";
		for ($i = 0; !empty($args[$i]); $i++) { 
			echo "f1[$a][$i] = '".$args[$i]."';\n";
			echo "t1[$a][$i] = '".$formTable1[1][$i]."';\n";
			echo "inputsT1[$a][$i] = '".$formTable1[3][$i]."';\n";
			if (!empty($formTable1[4][$i])) echo  "adds1[$a][$i] = '".$formTable1[4][$i]."';\n";
		}
		echo "f2[$a] = [];\n";
		echo "t2[$a] = [];\n";
		echo "l2[$a] = [];\n";
		echo "inputsT2[$a] = [];\n";
		echo  "adds2[$a]= [];\n";
		echo "ml2[$a] = [];\n";
		echo  "p2[$a]= [];\n";
		for ($i = 0; !empty($args2[$i]); $i++) { 
			echo "f2[$a][$i] = '".$args2[$i]."';\n";
			echo "t2[$a][$i] = '".$formTable2[1][$i]."';\n";
			echo "l2[$a][$i] = '".$formTable2[2][$i]."';\n";
			echo "inputsT2[$a][$i] = '".$formTable2[3][$i]."';\n";
			if (!empty($formTable2[4][$i])) echo  "adds2[$a][$i] = '".$formTable2[4][$i]."';\n";
			echo "ml2[$a][$i] = '".$formTable2[6][$i]."';\n";
			echo "p2[$a][$i] = '".$formTable2[7][$i]."';\n";
		}
		echo "f3[$a] = [];\n";
		echo "t3[$a] = [];\n";
		echo "l3[$a] = [];\n";
		echo "inputsT3[$a] = [];\n";
		echo "ml3[$a] = [];\n";
		for ($i = 0; !empty($args3[$i]); $i++) {
			echo "f3[$a][$i] = '".$args3[$i]."';\n";
			echo "t3[$a][$i] = '".$formTable3[1][$i]."';\n";
			echo "l3[$a][$i] = '".$formTable3[2][$i]."';\n";
			echo "inputsT3[$a][$i] = '".$formTable3[3][$i]."';\n";
			echo "ml3[$a][$i] = '".$formTable3[6][$i]."';\n";
		}
		?>
		} catch (e) {alert(e.message);}
		function fillform<?php echo $a;?>(result) {
			/*The integer variables $r1 and $r2 are the starting indexes for important JavaScript variables. They are useful for creating a form using the function adminPage() for n times in one only page. This will be better understood below.*/
			var npk1 = n[<?php echo $a;?>];
			var q = sql[<?php echo $a;?>];
			var tab = table[<?php echo $a;?>];
			var tab2 = table2[<?php echo $a;?>];
			var tab3 = table3[<?php echo $a;?>];
			var it1 = indT1[<?php echo $a;?>];
			var amountTable2 = at2[<?php echo $a;?>];
			var k = K[<?php echo $a;?>];
			var fields = f1[<?php echo $a;?>];
			var types = t1[<?php echo $a;?>];
			var inputs_types = inputsT1[<?php echo $a;?>];
			var fields2 = f2[<?php echo $a;?>];
			var types2 = t2[<?php echo $a;?>];
			var labels2 = l2[<?php echo $a;?>];
			var inputs_types2 = inputsT2[<?php echo $a;?>];
			var maxlengths2 = ml2[<?php echo $a;?>];
			var properties2 = p2[<?php echo $a;?>];
			var fields3 = f3[<?php echo $a;?>];
			var types3 = t3[<?php echo $a;?>];
			var inputs_types3 = inputsT3[<?php echo $a;?>];
			var maxlengths3 = ml3[<?php echo $a;?>];
			var r1 = Rr1[<?php echo $a;?>];
			var r2 = Rr2[<?php echo $a;?>];
			var readonly = ReadOnly[<?php echo $a;?>];
			var form_data = new FormData();
			var color = 'green';
			indTab1 = it1;
			it1 = (it1 == '') ? '' : it1+'_';
			//The variable $select is a parameter of this function. Note that $list1 will receive an HTML code that is a select tag list with the values of the field used to identify to the user each record of table 1, but this will happen only if $select[1] is equal to "select", which means that the list mentioned previously will be created only in this case, because $select[1] defines the way how the main field to the user will be shown when a select operation is made in the database. When the current value of this list is changed, the values of the form inputs relative to table 1 are also changed, to show the values of the current record of this table. If there is a table 2, changing the value of $list1 also changes the values of the form inputs relative to table 2, to show the values of this table related to the current record of table 1.
			list_way = "<?php echo $select[1];?>";
			JSList = "<?php echo $select[3];?>";
			list1 = '';
			list2 = '';
			count1 = 0;
			if (list_way == "select") {
				properties = "<?php echo $formTable1[7][$n];?>"; //$formTable1[7] and $formTable2[7] contains HTML properties for the form inputs.
				//The list in $list1 will execute two lines of Javascript code when its value is changed. The first line assigns to the global Javascript variable si the current list value, and the second line performs the Javascript function select(), which is responsible for changing all values of the form inputs according to the new value chosen for the list. 
				list1 += "<select name='"+fields[npk1]+"indTab1' id='"+fields[npk1]+"indTab1' onchange='si = this.selectedIndex; seleciona(this.options[si].value, npk1, k, r1, r2); "+JSList+"' "+properties+">\n";
				j = 0;
				f = fields[npk1]+indTab1;
				while (result[f][j] != NULL) {
					list1 += "<option value='"+result[f][j]+"'>"+result[f][j]+"</option>\n";
					if (tab2 != '') {
						while (result[f][j] === result[f][j+1]) j++;
					}
					j++;
					count1++;
				}
				list1 += "</select>";
			} 
			for (i = 0; fields[i] != null; i++) {
				key = fields[i]+indTab1;
				if (i != npk1 || count1 < 2) {
					if (inputs_types[i] !== "file")
						$("input[name='"+key+"']").val(result[key][0]);
					if (inputs_types[i] === "checkbox" && result[key][0]) $("input[name='"+key+"']").attr('checked', 'checked'); 
					// If the value of the current input is the url of an image, This image will also be shown along with is input, through the tag "img".
					if (is_image(result[key][0])){
						result[key][0] = "<img src='"+result[key][0]+"' width=280>";
						$("#d_"+key).append("<div class='lab'>"+result[key][0]+"</div>\n");
					}
				} else $("#d_"+key).children(".c").html(list1); //children or find.
			} 
			//Since $k is the index of the primary key of table 2 in the $values vector, the variable $p will point to the first column in $values after the column of the primary keys and/or the column of the foreign keys of table 2 related to table 1. These values will be shown by means of $list2, and will identify to the user the records of table 2.
			p = k+1;
			if (tab3 == "") {
				p++;
			}
			//if (!empty($formTable2[11])) $p = $k+$formTable2[11];
			//When $select[1] == "select", the $list2 is also created, and will also receive a HTML code that is a select tag list of the values mentioned above.
			JSList2 = "<?php echo $select[6];?>";
			count2 = 1;
			if (list_way == "select") {
				// The variable $formTable2[7] can be a vector or a matrix, and it contains a different set of properties for each input related to table 2, whether it's a vetor or it's a matrix. But if it's a matrix, then the programmer can define a different set of properties for each repetition of every input associated with table 2.
				properties = "<?php echo (!$formTable2[7][0][$p-$k]) ? $formTable2[7][$p-$k] : $formTable2[7][0][$p-$k];?>";
				//The list in $list2 will also execute two lines of Javascript code when its value is changed. The first line assigns to the global Javascript variable si the current list value, and the second line performs the Javascript function select(), which is responsible for changing the values of the form inputs related to table 2 according to the new value chosen for the list. 
				list2 += "<select name='"+fields2[p-k]+it1+"0' id='"+fields2[p-k]+it1+"0' onchange='si = this.selectedIndex; seleciona(this.options[si].value, p, k, r1, r2);' "+properties+" "+JSList2+">\n";
				j = 0;
				f = fields2[p-k]+it1+j;
				for (j = 0; result[f] != NULL; j++) {
					list2 += "<option value='"+result[f]+"'>"+result[f]+"</option>\n";
					j++;
					f = fields2[p-k]+it1+j;
					
				}
				count2 = j;
				list2 += "</select>\n";
			}
			if ($("input[name='"+key+"']")) {
				for (i = 0; fields2[i] != null; i++) {
					key = fields2[i]+it1+'0';
					if (i != p-k || count2 == 1) {
						$("input[name='"+key+"']").val(result[key]);
						if (inputs_types2[i] === "checkbox" || inputs_types2[i] === "radio")
							$("#d_"+key).append(result[key]);
						if (is_image(result[key])) {
							result[key] = "<img src='"+result[key]+"' width=280 border='0'>";
							$("#d_"+key).append("<div class='lab' style='float: down;'>$value</div>\n");
						}
					} else $("#d_"+key).children(".c").html(list2); //children or find.
					if (tab3 != '') {
						key3 = fields3[i]+it1+'0';
						$("input[name='"+key3+"']").val(result[key3]);			
					}
				}
			} else {
				$("#form2_1").before("<div class='form2' id='form2_0'></div>");
				c = labels2.length;
				aux_label = (labels2[c-1] === true);
				for (i = 0; fields2[i] != null; i++) {
					html += "<div id='d_"+fields2[i]+it1+"0'";
					if (inputs_types2[i] !== 'hidden') html += " class='item'";
					html += ">";// This div surrounds an input, and its id is equal to the input name, like "name0" or "name2_0"
					is_label = (aux_label || !$.inArray(inputs_types2[i], ["text", "password", "textarea"]));
					if (is_label)
						html += "<label for='"+fields2[i]+it1+"0'>"+labels2[i]+"</label>"; // This div surrounds the current label.
					// Again the input maxlength can be any size, but is size cannot be more than $max (=50).
					maxlength = maxlengths2[i];                                
					m = parseInt(maxlength);   
					if (m > 50) {
						size = "50";
					} else size = maxlength;
					// The variable $formTable2[7] can be a vector or a matrix of strings with sets of properties.
					properties = properties2[i];
					if (properties2[0][i]) properties = properties2[0][i];
					//If $i points to the field that identifies to the user any record of table 2, as seen before, and if more than one records were selected from table 2 through the SQL code pre-defined by the programmer, and if $select[1] = "select", that is, if the values of the main field to the user were defined to be shown in a select tag list when a select operation was made in the database, then the second list created previously will be shown for manipulation of the form inputs related to table 2, instead of an input with a value for this field.
					
					// This first instance of the inputs related to table 2 is where the values of this table will be changed as the value of the second list is changed, if it exists.
					field = fields2[i]+it1+'0';
					if (i == p-k && count2 > 1) {
						html += "<div class='c'>"+list2+"</div>\n";
						//If the values in $list2 are images, then the $list2 current image will be displayed beside it.
						f = fields2[p-k]+it1+0;
						if (is_image(result[f])) {
							result[f] = "<img src='"+result[f]+"' width=280 border='0'>";
							html += "<div class='lab' id='"+fields2[i]+it1+"00'>"+result[f]+"</div>\n";
						}
					}
					// Note below that the variable used to assign values to the table 2 inputs is $values, and not $values2.
					// If the input type is "html", only the value with any important message for the form will be displayed.
					else if (inputs_types2[i] === "html") {
						html += "<div class='c'>"+result[field]+"</div>\n";
					//If the type of the current input is not textarea, the tag 'input' is used to create it, and if it's not an file input either, a value will be assigned to its value property, otherwise, it will be an empty input, even if the form is in the editing mode, because file names cannot be updated.
					} else if (inputs_types2[i] !== "file" && inputs_types2[i] !== "textarea") {
						html += "<div class='c'>";
						html += "<input type='"+inputs_typecs2[i]+"' name='"+fields2[i]+it1;
						//If the type of the current input is radio, its name will be someting like "name" or "name1_", so that the repetitions of this input can have one only name and so that it is possible to choose only one option among the radio buttons with the same name. 
						if (inputs_types2[i] !== "radio") html += "0";
						html += "' id='"+fields2[i]+it1;
						if (inputs_types2[i] !== "radio") html += "0";
						if (!is_label) html += "' placeholder = '"+labels2[i];
						f = fields2[0]+it1+'0';
						html += (inputs_types2[i] !== "checkbox" && inputs_types2[i] !== "radio") ? "' value='"+result[field]+"' size='"+size+"' maxlength='"+maxlength+"' "+properties+" /></div>\n" : "' value='"+result[f]+"' size='"+size+"' maxlength='"+maxlength+"' "+properties+" /> "+result[field]+"</div>\n";
					}
					else if (inputs_types2[i] === "file"){
						html += "<div class='c'><input type='"+inputs_types2[$i]+"' name='"+fields2[i]+it1+"0' id='"+fields2[i]+it1+"0' value='' "+properties+"/></div>";
						//If the value of the current input is an image, it will be displayed beside it.
						if (is_image(result[field])) {
							result[field] = "<img src='"+result[field]+"' width=280 border='0'>";
							html += "<div class='lab' id='"+fields2[i]+it1+"00'>"+result[field]+"</div>\n";
						}
					}
					// The code inside the 'else' below is executed only if the current input type is textarea.
					else {
						html += "<div class='c'><textarea rows=3 cols=40 name='"+fields2[i]+it1+"0' id='"+fields2[i]+it1+"0' "+properties;
						if (!is_label) html += " placeholder='"+labels2[i]+"'";
						html += ">"+result[field]+"</textarea></div>\n";
	
					}
					i++;
					html += "</div>\n\n";
				}
				if (readonly !== 'readonly') html += "<input type='submit' name='delete"+result[f]+"' id='exclude' value='Delete'/><br>\n";
				$("#form2_0").html(html);
				if (tab3 != '') {
					html = '';
					j = 0;
					while (fields3[j] !== NULL && inputs_types3[j] != NULL) {
						name = fields3[j]+it1+'0';
						html += "<input type='"+inputs_types3[j]+"' name='"+name+"' value='"+result[name]+"'>";
						j++;
					}
					$("input[name='"+fields3[0]+"']").before(html);
				}
			}
			//In the following 'for', the Javascript array args receives the names of the inputs related to table 1. These are also the names of the fields of table 1. Note that the starting index of args is not 0, but $r1. When the funcion adminPage() is called more than once in one only page, args must receive the names of all the form inputs. Each call of adminPage() creates part of these inputs, but they add the names of these inputs to one only array args. So, there must be a way not to repeat the indexes of args whenever this function is called on the page. This function returns the last index of args plus 1, and when it is called again on the page, this last index plus 1 must be one of the arguments of the parameter $JSIndex and the starting index of args in the current call of adminPage(). This is how we can make args receive all the names of the form input. The variable args and the variables ValuesTable0, ValuesTable1, ValuesTable2... are useful for manipulation of the form on the client side. 
			for (i = 0; fields[i] != null; i++){
				args[i+r1] = fields[i]+indTab1;
			}
			//Below, args also receives the names of the fields of table 2.
			for (j = 0; fields2[j] != null; j++){
				args[j+i+r1] = fields2[j]+it1+'0';
			}
			r1 += j+i;
			//The Javascript variables ValuesTable0,... ValuestableN, receive from $values all the values related to the tables this form is associated with. Each ValuesTableI receives a row of $values. Note that the numbers that end the names of ValuesTable0,... ValuesTableN starts at $r2 in the current call of this function, which is a variable that, like the variable $r1, is used not to repeat these numbers with each call of this function adminPage(), so that every ValuesTavleI can receive all the data from the whole form. So, as seen before, $r1 and $r2 receives the starting indexes of args and ValuesTableI.
			f = fields[0];
			for (i = 0; result[f][i] != null; i++) {
				eval("var ValuesTable"+(i+r2)+" = new Array();");
				field = fields[0];
				j = 0
				while (fields[j] != NULL) {
					eval ("ValuesTable"+(i+r2)+"[j] = '"+result[field][i]+"';");
					j++;
					field = fields[j];
				} 	
				aux = j;
				field = fields2[0]+it1+i;
				j = 0;
				while (fields2[j] != null) {
					eval ("ValuesTable"+(i+r2)+"[aux] = '"+result[field]+"';");
					j++;
					field = fields2[j]+it1+i;
					aux++;
				}	
			} 
			eval("var ValuesTable"+(i+r2)+" = new Array();");
			r2 += i;
	
		}
		function AjaxSuccess<?php echo $a;?>(val, result) {
			var keys = Keys[<?php echo $a;?>];
			var pk1 = f1[<?php echo $a;?>][0];
			var it1 = indT1[<?php echo $a;?>];
			color = (result['status'].indexOf("Error") === -1) ? "green" : "red";
			if (val != "Select") {
				$("#status").html("<font color='"+color+"'><center>"+result['status']+"</center></font>");
				if (result['pk1']) $("input[name='"+pk1+it1+"']").val(result['pk1']);
			} else {
				fillform<?php echo $a;?>(result);
			}
			return color;
		}
		function Ajax<?php echo $a;?> (val) {
			var npk1 = n[<?php echo $a;?>];
			var q = sql[<?php echo $a;?>];
			var tab = table[<?php echo $a;?>];
			var tab2 = table2[<?php echo $a;?>];
			var tab3 = table3[<?php echo $a;?>];
			var it1 = indT1[<?php echo $a;?>];
			var amountTable2 = at2[<?php echo $a;?>];
			var k = K[<?php echo $a;?>];
			var fields = f1[<?php echo $a;?>];
			var types = t1[<?php echo $a;?>];
			var inputs_types = inputsT1[<?php echo $a;?>];
			var addresses = adds1[<?php echo $a;?>];
			var fields2 = f2[<?php echo $a;?>];
			var types2 = t2[<?php echo $a;?>];
			var inputs_types2 = inputsT2[<?php echo $a;?>];
			var addresses2 = adds2[<?php echo $a;?>];
			var fields3 = f3[<?php echo $a;?>];
			var types3 = t3[<?php echo $a;?>];
			var keys = Keys[<?php echo $a;?>];
			var form_data = new FormData();
			var color = 'green';
			if (val == "Save") {
				for (i = 0; keys[i] != null; i++) {
					v = $("input[name='"+keys[i]+"']").val();
					if ($("input[name='"+keys[i]+"']").attr("type") != "file"){
						form_data.append(keys[i], v);
					} else if (v.length > 12) {
						v = (v.length > 12) ? v.substr(12) : '';
						form_data.append(keys[i], $("input[name='"+keys[i]+"']").prop("files")[0], v); 
					}
				}
			} else if (val == "Delete") {
				for (i = 0; i < npk1; i++) {
					v = $("input[name='"+keys[i]+"']").val();
					form_data.append(keys[i], v);
				}
				if (tab3 != "") {
					fpk2 = fields2[0]+it1+'_0';
					v = $("input[name='"+fpk2+"']").val();
					form_data.append(fpk2, v);
				}
			}
			form_data.append("butPres", val);
			form_data.append("sql", q);
			form_data.append("public", pub);
			form_data.append("table", tab);
			form_data.append("table2", tab2);
			form_data.append("table3", tab3);
			form_data.append("indTab1", it1);
			form_data.append("idSession", idSession);
			form_data.append("args", fields);
			form_data.append("args2", fields2);
			form_data.append("args3", fields3);
			form_data.append("types", types);
			form_data.append("types2", types2);
			form_data.append("types3", types3);
			form_data.append("inputs_types", inputs_types);
			form_data.append("inputs_types2", inputs_types2);
			form_data.append("addresses", addresses);
			form_data.append("addresses2", addresses2);
			form_data.append("amountTable2", amountTable2);
			try {
				$.ajax({
					url: 'operate_form.php',
					type: 'POST',
					dataType: 'json',
					processData: false,
					contentType: false,
					data: form_data,
					success: function (result, s, xhr) {
						//alert(xhr.responseText);
						color = AjaxSuccess<?php echo $a;?>(val, result);
					},
					error: function (xhr, s, e) {
						alert(xhr.responseText);
					}
				});
			} catch (e) {alert(e.message);}
			return color;
		}
		</script>
		<?php
		$a++;
		$ret = NULL;
		/*This function returns an array with the values of $r1 and $r2. These integer variables are the starting indexes of important Javascript variables in a possible next call of adminPage(), as we learnt previously. */
		if ($JSIndex !== NULL) $ret = array($r1, $r2);
		return $ret;
	}
}
/*
Índices de $formTabelas e suas correspondências

0 - campos de tabelas e nomes de imputs
1 - Tipos de dados no BD
2 - Títulos dos inputs
3 - Os inputs do formulário
4 - Endereços para onde arquivos vão
5 - Nome da tabela
6 - Tamanhos máximos dos campos e inputs
*/

?>
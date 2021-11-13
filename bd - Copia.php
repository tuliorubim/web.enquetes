<?php
session_start();
//if ($_SERVER['SERVER_PORT'] == 80)
	//echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=https://www.webenquetes.com.br".$_SERVER['REQUEST_URI']."'>";
header('Content-Type: text/html; charset=utf-8');
require_once "funcoes/funcoesDesign.php";
//$con = Connect('localhost', 'webenque_enquetes', 'root', 'oVqgIkh2SYFK');
$con = Connect('localhost', 'webenque_enquetes', 'root', ''); 
$dateformat = "d/m/Y";
$timeformat = "H:i:s";
$POST = $_POST;
$logged_in = false;
$status = '';
mysqli_query($con, "SET NAMES 'utf8'");
mysqli_query($con, 'SET character_set_connection=utf8');
mysqli_query($con, 'SET character_set_client=utf8');
mysqli_query($con, 'SET character_set_results=utf8');
$star = "<font color=red>*</font>";
$fw = '640px';
$fh = '520px';
$idSession = 'user';
$cookie_url = "localhost";
$cookie_https = FALSE;
//$cookie_url = "webenquetes.com.br";
//$cookie_https = TRUE;
if ($_GET['login'] === "off") {
	setcookie($idSession, '', time()-10, '/', $cookie_url, $cookie_https);
	logout();
}
if ($_SESSION[$idSession] !== NULL && $_SESSION[$idSession] !== $_COOKIE[$idSession]) {
	setcookie($idSession, $_SESSION[$idSession], time()+(86400*365*10), '/', $cookie_url, $cookie_https);
}
if ($_COOKIE[$idSession] !== NULL && $_SESSION[$idSession] !== $_COOKIE[$idSession]) {
	$_SESSION[$idSession] = $_COOKIE[$idSession];	
}
if ($_GET['login'] === "off") {
	$_SESSION[$idSession] = NULL;
}
$idu = $_SESSION[$idSession];
if ($idu === NULL) $idu = 0;
if (strpos($_SERVER['REQUEST_URI'], 'minhas_enquetes.php') !== FALSE) {
	select("select usuario from cliente where idCliente = $idu", array("user"));
	if ($idu === 0 || ($idu !== 0 && $user == NULL)) {
		login($POST, 'cliente', array('usuario', 'senha', 'enter'));
	} 
}
contagem ('cont_geral', true);
function enquetes_mais_procuradas() {
	global $enquete;
	global $num_votos;
	$enquetes = select("select e.idEnquete, e.enquete from enquete e inner join voto v on e.idEnquete = v.cd_enquete group by e.idEnquete having count(v.dt_voto) > 30 order by e.dt_criacao desc limit 6");
	/*$args = select("SELECT v.cd_enquete, v.cd_pergunta, v.dt_voto, e.esconder FROM enquete e inner join voto v on e.idEnquete = v.cd_enquete order by v.cd_enquete, v.cd_pergunta, v.dt_voto desc");
	$enq = array();
	$dif = array();
	$i = 0;
	$j = 0;
	while ($args[$i][0] !== NULL) {
		$d = 1;
		while ($args[$i+$d][1] === $args[$i][1] && $d < 15) 
			$d++;
		select("select count(dt_voto) from voto where cd_enquete = ".$args[$i][0], array('num_votos'));
		if ($num_votos >= 30 && !$args[$i][3]) {	
			$date1 = strtotime2($args[$i+$d-1][2]);
			$date2 = strtotime2(date("Y-m-d H:i:s"));
			$dif[$j] = ($date2-$date1)/$d;
			$enq[$j] = $args[$i][0];
			$j++;	
		}
		$i++;
		while ($args[$i][0] === $args[$i-1][0])
			$i++;
	}
	$j = 0;
	while ($enq[$j] !== NULL) {
		$d = 1;
		while ($dif[$j+$d] !== NULL) {
			if ($dif[$j] > $dif[$j+$d]) {
				$aux = $dif[$j];
				$dif[$j] = $dif[$j+$d];
				$dif[$j+$d] = $aux;
				$aux = $enq[$j];
				$enq[$j] = $enq[$j+$d];
				$enq[$j+$d] = $aux;
			}
			$d++;
		}
		$j++;
	}
	$enquetes = array();
	for ($j = 0; $enq[$j] !== NULL; $j++) {
		select("select enquete from enquete where idEnquete = ".$enq[$j], array("enquete"));
		$enquetes[$j] = array($enq[$j], $enquete);
	}*/
	return $enquetes;
}
function cria_enquete () {
	global $POST;
	global $logged_in;
	global $idSession;
	global $status;
	global $dateformat;
	global $timeformat;
	$variaveis = array("idEnquete", "cd_categoria", "cd_usuario", "enquete", "introducao", "dt_criacao");
	$tipos = array('integer', 'integer', 'integer', 'varchar', 'varchar', 'datetime');
	$tabela = "enquete";
	$POST["dt_criacao"] = date("$dateformat $timeformat");
	if (!$logged_in) {
		$email = $POST['usuario'];
		$senha = $POST['senha'];
		$res = select("select idCliente from cliente where usuario = '$email' and senha = '$senha'");
		$idCliente = $res[0][0];
		$POST['introducao'] = '';
		if ($idCliente == NULL) {
			$res = select("select usuario from cliente where usuario = '$email'");
			$usuario = $res[0][0];
			if ($usuario != $email) {
				include "sendmail.php";
				$variaveis1 = array("idCliente", "data_cadastro", "usuario", "senha");
				$tipos1 = array("integer", "datetime", "varchar", "varchar");
				$enderecos1 = array();
				$tabela1 = "cliente";
				$POST["data_cadastro"] = date("$dateformat $timeformat"); 
				$ret = save($tabela1, $variaveis1, $tipos1, $POST, $enderecos1, -1);
				$POST['subject'] = "Sua senha Web Enquetes";
				$POST['message'] = "A senha do usuário que você acabou de criar na Web Enquetes é \n\n".$POST['senha'].".";
				send_email ("tfrubim@gmail.com", array($email), $POST['subject'], $POST['message']);
				$_SESSION[$idSession] = $ret[1];
				$POST['cd_usuario'] = $ret[1];
				$ret2 = save($tabela, $variaveis, $tipos, $POST, array(), -1);
				$POST['idEnquete'] = $ret2[1];
				$status = '';
				echo "<script language='javascript'>alert('Voc\u00ea acabou de criar um cadastro e agora pode continuar o desenvolvimento de sua enquete. Sua senha fornecida foi enviada para o e-mail que voc\u00ea acabou de fornecer.');</script>";
			} else {
				$status = "A senha est&aacute; incorreta";
			}
		} else {
			$POST['cd_usuario'] = $idCliente; 
			$ret = save($tabela, $variaveis, $tipos, $POST, array(), -1);
			$POST['idEnquete'] = $ret[1];
			login($POST, 'cliente', array('usuario', 'senha', 'button'));
		}
	} else {
		$POST['cd_usuario'] = $_SESSION[$idSession]; 
		$ret = save($tabela, $variaveis, $tipos, $POST, array(), -1);
		$POST['idEnquete'] = $ret[1];
	}
	echo "<script language='javascript'>$('#status').html('<font color=red>$status</font>');</script>";
}
if (strpos($_SERVER['REQUEST_URI'], 'criar_enquete.php') !== FALSE && $_POST["button"] != NULL) {
	cria_enquete();
} 
if ($_SESSION[$idSession] != NULL) {
	$idu = $_SESSION[$idSession];
}
$title = htmlentities("Crie seu questionário para pesquisa de mercado ou de opinião.", ENT_NOQUOTES, 'ISO-8859-1', true);
$description = htmlentities("criar, fazer, elaborar, modelo, site, pergunta, perguntas, formulário, questionário, inquérito, enquete, pesquisa, mercado, satisfação, opinião, política, esportes, religião, atualidades, ciência, economia, entretenimento, filmes, jogos, livros, música, televisão, internet, informática, cliente, funcionário, interno", ENT_NOQUOTES, 'ISO-8859-1', true);
if ($_GET['ide'] !== NULL) {
	$ide = $_GET['ide'];
	select("select e.enquete, e.introducao, e.esconder, p.pergunta from enquete e left join pergunta p on e.idEnquete = p.cd_enquete where e.idEnquete = $ide limit 1", array("title", "description", "esconder", "pergunta"));
	$title = "Enquete: $title";
	if (!$esconder) {
		if (empty($description)) $description = $pergunta;
		$desc = explode(' ', $description);
		$description = '';
		for ($i = 0; $desc[$i] !== NULL; $i++) {
			$last = strlen($desc[$i])-1;
			if (!in_array($desc[$i][$last], array(',', '.', ';', '?', '!')))
				$description .= $desc[$i].', ';
			else $description .= substr($desc[$i], 0, $last).', ';	
		}
	} else $description = '';
}
include "service.php";
$service = new Service($idu);
$service_data = $service->get_acquired_service();
?>
<script src="jquery-3.5.1.min.js"></script>
<script src="funcoes/JSFunctions.js" type="text/javascript"></script>
<div id="fb-root"></div>
<?php
if (strpos($_SERVER['REQUEST_URI'], 'resultados_parciais.php') === FALSE) {
?>
<!--<script async defer crossorigin="anonymous" src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v5.0"></script>-->
<?php
}
include 'funcoes/init.html';
?>
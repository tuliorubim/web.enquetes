<?php
include "ajax_header.php";
$json = '';
$status = '';
$ide = $_GET['cd_enquete'];
$novo_voto = $_GET['novo_voto'];
//select("select end_date from enquete where idEnquete = $ide", array('end_date'));
if (true) {// || $end_date == '0000-00-00' || strtotime(date('Y-m-d')) <= strtotime($end_date)) {
	$data = date("$dateformat $timeformat");
	$db->select("select cd_usuario from enquete where idEnquete = $ide", array("cdu"));
	if ($idu == 0) {
		$db->save('cliente', array('data_cadastro'), array('datetime'), array($data));
		$db->select("select max(idCliente) from cliente", array("last_user"));
		$_SESSION['user'] = $last_user;
		$idu = $last_user;
	} elseif ($idu === $cdu) {
		if ($novo_voto == '1') {
			$votou = $db->select("select cd_usuario from voto where cd_usuario = $cdu and cd_enquete = $ide");
			if (!empty($votou)) {
				$db->save('cliente', array('data_cadastro'), array('datetime'), array($data));
				$db->select("select max(idCliente) from cliente", array("last_user"));
				$_SESSION['user2'] = $last_user;
				$idu = $last_user;
			} 
		} elseif (isset($_SESSION['user2'])) {
			$idu = $_SESSION['user2'];
		}
	}
	$idp = $_GET['cd_pergunta'];
	$idr = $_GET['cd_resposta'];
	$variaveis = array('cd_usuario', 'cd_enquete', 'cd_pergunta', 'cd_resposta');
	$tipos = array('integer', 'integer', 'integer', 'integer');
	$values = array($idu, $ide, $idp, 0);
	$cdr = $db->select("select cd_resposta from voto where cd_usuario = $idu and cd_enquete = $ide and cd_pergunta = $idp");
	for ($i = 0; !empty($cdr[$i][0]); $i++) {
		$values[3] = $cdr[$i][0];
		$db->delete ("voto", $variaveis, $tipos, $values);	
		$status = '';
	}
	$replace_vote = false;
	if ($i > 0) $replace_vote = true;
	$variaveis[4] = 'dt_voto'; 
	$tipos[4] = 'datetime';
	$values[4] = $data;
	if (!empty($idr)) {
		if (is_array($idr)) {
			for ($i = 0; array_key_exists($i, $idr); $i++) {
				$values[3] = $idr[$i];
				$db->save('voto', $variaveis, $tipos, $values);
			}
		} else {
			$values[3] = $idr;
			$db->save('voto', $variaveis, $tipos, $values);
		}
	}
	if (mysqli_error($con)) $status .= "Erro: ".mysqli_error($con);
	else {
		/*require_once "create_html.php";
		$poll_html = new Create_HTML($ide);
		$poll_html->con = $con;
		$poll_html->mudou();*/
		$db->select("select max(idPergunta) from pergunta where cd_enquete = $ide", array("cdp"));
		if ($cdp === $idp) {
			if (!$replace_vote) $status = "Suas respostas foram processadas com sucesso.";
			else $status = "Voc\u00ea alterou suas respostas com sucesso.";
		}
	}
} else $status = "A vota\u00e7\u00e3o desta enquete est\u00e1 encerrada.";
$json = '{ "status" : "'.$status.'" }'; 
echo $_GET['callback']."(".$json.")";
?>

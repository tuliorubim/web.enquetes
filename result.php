<?php
include "ajax_header.php";
$ide = $_POST['ide'];
$idr = $_POST['idr'];
$cds = $_POST['cds'];
$data_lim = $_POST['data_lim'];
/*if (strpos($idr, ',') !== FALSE) {
	$idr = explode (',', $idr);
}*/
//$status = var_dump($idr);
$selected_answers = "(select cd_usuario from voto";
$where = '';
if ($cds == 0) {
	$where = " where cd_resposta = $idr";
} else {
	for ($k = 0; !empty($idr[$k]); $k++) {
		$where .= "cd_resposta = ".$idr[$k];
		if (!empty($idr[$k+1])) $where .= " or ";
	} 
	if ($where !== '') $where = " where $where";
}		
$selected_answers .= $where.')';
$i = 0;
$conteudo = array();
$sql = "select p.idPergunta, r.idResposta, count(v.dt_voto) from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta left join voto v on r.idResposta = v.cd_resposta where p.cd_enquete = $ide group by r.idResposta order by p.idPergunta, count(v.dt_voto) desc, r.idResposta";
$rs = mysqli_query($con, $sql);
$i = 0;
$j = -1;
if ($rs && mysqli_num_rows($rs) > 0) $row = mysqli_fetch_array($rs);
$idP = NULL;
$votos_pergunta = 1;
$vp = array();
$json = '{ ';
$db->select("select count(cd_usuario) from voto where cd_enquete = $ide and cd_usuario in $selected_answers", array("ve"));
$json .= '"ve" : "Total de respostas: '.$ve.'", ';
do {
	if ($idP != $row['idPergunta']) {
		$idP = $row['idPergunta'];
		$sql = "select count(cd_usuario) from voto where cd_pergunta = $idP and cd_usuario in $selected_answers";
		if ($data_lim != '') $sql .= " and dt_voto <= '$data_lim'";
		$db->select ($sql, array("votos_pergunta"));
		$j++;
		$vp[$j] = $votos_pergunta;
		$json .= '"vp'.$j.'" : "Total de votos: '.$votos_pergunta.'", ';
		if ($votos_pergunta == 0) $votos_pergunta = 1;
	}
	$idR = $row["idResposta"];
	$sql = "select count(cd_usuario) from voto where cd_resposta = $idR and cd_usuario in $selected_answers";
	if ($data_lim != '') $sql .= " and dt_voto <= '$data_lim'";
	$db->select ($sql, array("votos_resposta"));
	$porcentagem = round(100*$votos_resposta/$votos_pergunta, 1);
	$json .= '"votos'.$i.'" : "'.$votos_resposta.' votos, '.$porcentagem.' % dos votos", ';
	$i++;
} while ($rs && $row = mysqli_fetch_array($rs));
$json .= '"status" : "'.$sql.'", ';
$json = substr($json, 0, strlen($json)-2).' }';
echo $json;
?>
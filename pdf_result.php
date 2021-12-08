<?php
include 'ajax_header.php';
$ide = $_GET['ide'];
if ($idu > 0) {
	$rs = mysqli_query($con, "select c.idCliente, e.idEnquete from cliente c inner join enquete e on c.idCliente = e.cd_usuario where idCliente = $idu and idEnquete = $ide");
	if ($rs && ($row = mysqli_num_rows($rs) > 0)) {
		$idr = array();
		for ($i = 0; isset($_GET["idr$i"]); $i++) {
			$idr[$i] = $_GET["idr$i"];
		}
		$selected_answers = '';
		$selected_answers2 = '';
		if (!empty($idr)){
			$selected_answers = "(select cd_usuario from voto";
			$selected_answers2 = "select resposta from resposta";
			$where = '';
			$where2 = '';
			for ($k = 0; !empty($idr[$k]); $k++) {
				$where .= "cd_resposta = ".$idr[$k];
				$where2 .= "idResposta = ".$idr[$k];
				if (!empty($idr[$k+1])) {
					$where .= " or ";
					$where2 .= " or ";
				}
			} 
			if ($where !== '') {
				$where = " where $where";
				$where2 = " where $where2";
			}		
			$selected_answers .= $where.')';
			$selected_answers2 .= $where2;
		}
		$and_sel_ans = '';
		if (strlen($selected_answers) > 0) $and_sel_ans = "and v.cd_usuario in $selected_answers";
		$sql = "select p.idPergunta, p.pergunta, r.idResposta, r.resposta, count(v.dt_voto) as num_votos, 100*count(v.dt_voto)/(select count(dt_voto) from voto where cd_pergunta = p.idPergunta $and_sel_ans) as porcentagem from pergunta p left join resposta r on p.idPergunta = r.cd_pergunta left join voto v on r.idResposta = v.cd_resposta where p.cd_enquete = $ide group by r.idResposta order by p.idPergunta, count(v.dt_voto) desc, r.idResposta";
		$args = $db->select($sql, array(), true);
		include 'pdf-php/src/Cezpdf.php';
		class Creport extends Cezpdf
		{
			public function Creport($p, $o)
			{
				$this->__construct($p, $o, 'none', array());
			}
		}
		$pdf = new Creport('a4', 'portrait');
		//$pdf->targetEncoding = "UTF-8";
		$pdf->ezSetMargins(30, 30, 30, 30);
		$pdf->selectFont('Helvetica');
		$db->select("select enquete from enquete where idEnquete = $ide", array("enquete"), array(), true);
		$pdf->ezText("<b>Resultados da enquete: $enquete</b>", 18, array('justification' => 'left'));
		if (!empty($selected_answers2)) {
			$args2 = $db->select($selected_answers2, array(), true);
			if (count($args2) === 1) {
				$respostas_escolhidas = "para as pessoas que votaram na resposta: \"".$args2[0]['resposta']."\"\n\n";
			} elseif (count($args2) > 1) {
				$respostas_escolhidas = "para as pessoas que votaram nas respostas: \n";
				for ($i = 0; $args2[$i][0] !== NULL; $i++) {
					if ($i > 0) $respostas_escolhidas .= "ou ";
					$respostas_escolhidas .= "\"".$args2[$i]['resposta']."\";\n";
				}
				$respostas_escolhidas .= "\n";
			}
			if (isset($respostas_escolhidas)) {
				$pdf->ezText("<b>$respostas_escolhidas</b>", 12, array('justification' => 'left'));
			}
		}
		$pdf->ezText("\n", 18, array('justification' => 'left'));
		$idP = $args[0]['idPergunta'];
		$j = 1;
		for ($i = 0; $args[$i][0] !== NULL; $i++) {
			$pdf->ezText("<b>$j) ".$args[$i]['pergunta']."</b>\n", 15, array('justification' => 'left'));
			$db->select("select count(v.dt_voto) from voto v where v.cd_pergunta = $idP $and_sel_ans", array("votos_pergunta"));
			while ($args[$i]['idPergunta'] === $idP) {
				if (strlen($selected_answers) > 0) {
					$db->select("select count(v.dt_voto) from voto v where v.cd_resposta = ".$args[$i]['idResposta']." $and_sel_ans", array("votos"));
					$args[$i]['num_votos'] = $votos;
					$args[$i]['porcentagem'] = 100*$votos/$votos_pergunta;
				} 
				$ans_result = $args[$i]['resposta']."  \t".$args[$i]['num_votos']." votos, ".round($args[$i]['porcentagem'], 1)." %\n";
				$pdf->ezText($ans_result, 12, array('justification' => 'left'));
				$i++;
			}
			$pdf->ezText("<b>Total de votos nesta pergunta: ".$votos_pergunta."</b>\n\n", 12, array('justification' => 'left'));
			$idP = $args[$i]['idPergunta'];
			$j++;
			$i--;
		}
		$db->select("select count(v.dt_voto) from voto v where v.cd_enquete = $ide $and_sel_ans", array("votos_enquete"));
		$pdf->ezText("<b>Total de votos nesta enquete: ".$votos_enquete."</b>", 12, array('justification' => 'left'));
		$pdf->ezStream(array('download' => 1));
	} else echo "Ocorreu um erro ao se tentar gerar o PDF";
}
?>

<?php
class EnquetesDestacadas extends DBFunctions {
	const MAX_ENQUETES_DESTACADAS = 30;
	public $idu;
	public function __contruct($idu, $con) {
		$this->idu = $idu;
		$this->con = $con;
	}
	public function enquetes_destacadas() {
		$idu = $this->idu;
		$enquetes = $this->select("select e.idEnquete, p.pergunta, p.idPergunta, p.multipla_resposta from enquete e inner join pergunta p on e.idEnquete = p.cd_enquete where p.idPergunta = (select min(idPergunta) from pergunta where cd_enquete = e.idEnquete and idPergunta not in (select cd_pergunta from voto where cd_usuario = $idu)) and e.status_divulgacao = 2 order by rand() limit 6");
		$count_polls = count($enquetes);
		if ($count_polls < 6) {
			$aux = $this->select("select e.idEnquete, e.enquete, count(v.dt_voto) as votos from enquete e inner join voto v on e.idEnquete = v.cd_enquete group by e.idEnquete order by count(v.dt_voto) desc limit ".(6-$count_polls));
			$enquetes = array_merge($enquetes, $aux); 
		}
		$respostas = [];
		for ($i = 0; $i < 6; $i++) {
			if (array_key_exists('pergunta', $enquetes[$i])) {
				$pergunta = $enquetes[$i]['pergunta'];
				if (strlen($pergunta) > 60) {
					$dot_positions = $this->str_positions('.', $pergunta);
					$last = count($dot_positions)-1;
					if ($last > -1) {
						$last_dot = $dot_positions[$last]+2;
						$enquetes[$i]['pergunta'] = substr($pergunta, $last_dot, strpos($pergunta, '?', $last_dot)-$last_dot); 
					} 
				}
				$resp = $this->select("select idResposta, resposta from resposta where cd_pergunta = ".$e['idPergunta']." order by idResposta");
				$aux = "<form name='pergunta' method='post' action='enquete.php'>";
				$aux .= "<input type='hidden' name='idEnquete' value='".$enquetes[$i]['idEnquete']."'>";
				$aux .= "<p>$pergunta</p><input type='hidden' name='idPergunta' value='".$enquetes[$i]['idPergunta']."'>";
				$aux .= "<ul>";
				$j = 0;
				$mr = $enquete[$i]['multipla_resposta'];
				foreach ($resp as $r) {
					if (!$mr) {
						$aux .= "<li><input type='radio' name='resposta0_' value='".$r['idResposta']."'>".$r['resposta']."</li>";
					} else {
						$aux .= "<li><input type='hidden' id='idResposta0_$j' value='".$r['idResposta']."'><input type='checkbox' id='resposta0_$j'>".$r['resposta']."</li>";
					}
					$j++;
				}
				if ($mr) {
					$aux .= '<br><button type="button" class="btn btn-primary estilo-modal" name="resposta0_">RESPONDER</button>';
				]
				$aux .= "</ul></form>";
				$respostas[$i] = $aux;
			} elseif (array_key_exists('enquete', $e)) {
				$respostas[$i] = "Esta enquete tem ".$enquetes[$i]['votos']." votos.";
			}
		}
		?>
		<script language="javascript">
			$(document).ready(function () {
				$("input[name='resposta0_']").click(function () {
					document.pergunta.submit();
				});
			});
		<?php
	}
}
?>
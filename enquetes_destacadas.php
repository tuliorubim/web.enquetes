<?php
class EnquetesDestacadas extends DBFunctions {
	const MAX_ENQUETES_DESTACADAS = 30;
	public $idu;
	public function __construct($idu, $con) {
		$this->idu = $idu;
		$this->con = $con;
	}
	public function enquetes_destacadas() {
		$idu = $this->idu;
		$enquetes = $this->select("select e.idEnquete, p.pergunta, p.idPergunta, p.multipla_resposta from enquete e inner join pergunta p on e.idEnquete = p.cd_enquete where p.idPergunta = (select min(idPergunta) from pergunta where cd_enquete = e.idEnquete and idPergunta not in (select cd_pergunta from voto where cd_usuario = $idu)) and e.status_divulgacao = 2 and disponivel = 1 order by rand() limit 6");
		$count_polls = count($enquetes);
		if ($count_polls < 6) {
			$aux = $this->select("select e.idEnquete, e.enquete, count(v.dt_voto) as votos from enquete e inner join voto v on e.idEnquete = v.cd_enquete where disponivel = 1 group by e.idEnquete order by count(v.dt_voto) desc limit ".(6-$count_polls));
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
				$r = $this->select("select idResposta, resposta from resposta where cd_pergunta = ".$enquetes[$i]['idPergunta']." order by idResposta");
				$aux = "<div class='dropdown-menu'><form id='form_pergunta$i' method='post' action='enquete.php?ide=".$enquetes[$i]['idEnquete']."'>";
				//$aux .= "<input type='hidden' name='idEnquete' value='".$enquetes[$i]['idEnquete']."'>";
				$aux .= "<p>$pergunta</p><input type='hidden' name='idPergunta' value='".$enquetes[$i]['idPergunta']."'>";
				$aux .= '<ul>';
				$mr = $enquetes[$i]['multipla_resposta'];
				for ($j = 0; array_key_exists($j, $r); $j++) {
					if (!$mr) {
						$aux .= "<li><input type='radio' name='resposta0_' value='".$r[$j]['idResposta']."' onclick='document.getElementById(\"form_pergunta$i\").submit()'> ".$r[$j]['resposta']."</li>";
					} else {
						$aux .= "<li><input type='hidden' name='idResposta0_$j'><input type='checkbox' name='resposta0_$j' value='".$r[$j]['idResposta']."'> ".$r[$j]['resposta']."</li>";
					}
				}
				if ($mr) {
					$aux .= "<br><input type='submit' class='btn btn-primary estilo-modal' name='resposta0_' value='RESPONDER'>";
				}
				$aux .= "</ul></form></div>";
				$respostas[$i] = $aux;
			} elseif (array_key_exists('enquete', $enquetes[$i])) {
				$respostas[$i] = "Esta enquete tem ".$enquetes[$i]['votos']." votos.";
			}
		}
		return [$enquetes, $respostas];
	}
}
?>
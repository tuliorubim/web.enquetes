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
		$enquetes = $this->select("select e.idEnquete, p.pergunta, p.idPergunta from enquete e inner join pergunta p on e.idEnquete = p.cd_enquete where p.idPergunta = (select min(idPergunta) from pergunta where cd_enquete = e.idEnquete and idPergunta not in (select cd_pergunta from voto where cd_usuario = $idu)) and e.status_divulgacao = 2 order by rand() limit 6");
		$count_polls = count($enquetes);
		if ($count_polls < 6) {
			$aux = $this->select("select e.idEnquete, e.enquete, count(v.dt_voto) from enquete e inner join voto v on e.idEnquete = v.cd_enquete group by e.idEnquete order by count(v.dt_voto) desc limit ".(6-$count_polls));
			$enquetes = array_merge($enquetes, $aux); 
		}
		$respostas = [];
		foreach ($enquetes as $e) {
			if (array_key_exists('pergunta', $e)) {
				if (strlen($e['pergunta']) > 60) {
					$dot_positions = $this->str_positions('.', $e['pergunta']);
					$last = count($dot_positions)-1;
					if ($last > -1) {
						$last_dot = $dot_positions[$last]+2;
						$e['pergunta'] = substr($e['pergunta'], $last_dot, strpos($e['pergunta'], '?', $last_dot)-$last_dot); 
					} 
				}
				$r = $this->select("select resposta from resposta where cd_pergunta = ".$e['idPergunta']." order by idResposta");
				$respostas[] = $r;
			} else break;
		}
	}
}
?>
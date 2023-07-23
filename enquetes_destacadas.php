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
		$enquetes = $this->select("select e.idEnquete, p.idPergunta, p.pergunta from enquete e inner join pergunta p on e.idEnquete = p.cd_enquete where p.idPergunta = (select min(idPergunta) from pergunta where cd_enquete = e.idEnquete and idPergunta not in (select cd_pergunta from voto where cd_usuario = $idu)) and e.status_divulgacao = 2 order by rand() limit 6");
		$count_polls = count($enquetes);
		if ($count_polls < 6) {
			$aux = $this->select("select e.idEnquete, e.enquete, count(v.dt_voto) from enquete e inner join voto v on e.idEnquete = v.cd_enquete group by e.idEnquete order by count(v.dt_voto) desc limit ".(6-$count_polls));
			$enquetes = array_merge($enquetes, $aux); 
		}
		return $enquetes;	
	}
	public function escreve_enquetes($enquetes) {
		$respostas = [];
		foreach ($enquetes as $e) {
			if (array_key_exists('pergunta', $e) {
				$respostas[] = $this->select("select resposta from resposta where cd_pergunta = ".$e['idPergunta']." order by idResposta");
			} else break;
		}
	}
}
?>
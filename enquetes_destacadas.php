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
		$enquetes = $this->select("select e.idEnquete, p.pergunta, r.resposta from enquete e inner join pergunta p on e.idEnquete = p.cd_enquete inner join resposta r where p.idPergunta = r.cd_pergunta where p.idPergunta = (select min(idPergunta) from pergunta where cd_enquete = e.idEnquete and idPergunta not in (select cd_pergunta from voto where cd_usuario = $idu)) and e.status_divulgacao = 2 order by rand() limit 6");
		return $enquetes;	
	}
}
?>
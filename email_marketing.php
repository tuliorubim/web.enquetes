<?php
include "bd.php";
class Email extends AdminFunctions {
	public $con;
	public function __construct($con) {
		$this->con = $con;
	}
	public function send_marketing () {
		global $status;
		$con = $this->con;
		$post = $_POST;
		$sql = $post['emails'];
		$args = $this->select($sql);
		$i = 0;
		//echo count($args)%25;   "select c.usuario from cliente c inner join enquete e on c.idCliente = e.cd_usuario inner join pergunta p on e.idEnquete = p.cd_enquete left join voto v on e.idEnquete = v.cd_enquete where date(e.dt_criacao) between '$this->ini_date' and '$this->end_date' and p.pergunta <> '' group by e.idEnquete having count(v.dt_voto) < 10 order by e.idEnquete"
		while (is_string($args[$i]['usuario'])) {
			$recipients = array();
			$max = 25;
			if ($i === 0) $max = count($args)%25+1;
			$inc = 0;
			for ($j = 0; $j < $max; $j++) {
				if (!empty($args[$i+$j]['usuario'])) {
					$recipients[$j-$inc] = $args[$i+$j]['usuario'];
				} else $inc++;
			}
			if ($i === 0 && $max < 25) $recipients[$j] = "tfrubim@gmail.com";
			$post['email'] = 'tfrubim@gmail.com';
			$post['name'] = "Web Enquetes";
			//$post['subject'] = utf8_encode($post['subject']);//, ENT_NOQUOTES, 'UTF-8', true); 
			$post['message'] = htmlentities($post['message'], ENT_NOQUOTES, 'UTF-8', true); 
			$this->sendEmail ($post, $recipients);
			if (strpos($status, "corretamente") !== FALSE) {
				$recip = implode(", ", $recipients);
				mysqli_query($con, "insert into enviados (email, recipients, subject, message) values ('".$post['email']."', '$recip', '".$post['subject']."', '".$post['message']."')");
			}
			$this->write_status();
			$i += $max;
		} 
		echo $i;
	}
}
if ($we->idu == 55291) {
	$e = new Email($we->con);
	$e->send_marketing();
}
?>

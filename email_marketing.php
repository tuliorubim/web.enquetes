<?php
session_start();
include "bd.php";
class Email {
	private $auth;
	private $ini_date;
	private $end_date;
	public function __construct($auth, $ini_date, $end_date) {
		$this->auth = $auth;
		$this->ini_date = $ini_date;
		$this->end_date = $end_date;
	}
	public function send_marketing () {
		global $status;
		global $con;
		$sql = "select c.usuario from cliente c inner join enquete e on c.idCliente = e.cd_usuario inner join pergunta p on e.idEnquete = p.cd_enquete left join voto v on e.idEnquete = v.cd_enquete where date(e.dt_criacao) between '$this->ini_date' and '$this->end_date' and p.pergunta <> '' group by e.idEnquete having count(v.dt_voto) < 10 order by e.idEnquete";
		$args = select($sql);
		$i = 0;
		//echo count($args)%25;
		while ($i < count($args)) {
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
			$post = $_POST;
			$post['email'] = 'webenquetes@webenquetes.com.br';
			$post['name'] = "Web Enquetes";
			sendEmail ($post, $recipients, $this->auth);
			if (strpos($status, "success") !== FALSE) {
				$recip = implode(", ", $recipients);
				mysqli_query($con, "insert into enviados (email, recipients, subject, message) values ('".$post['email']."', '$recip', '".$post['subject']."', '".$post['message']."')");
			}
			write_status();
			$i += $max;
		} 
		echo $i;
	}
}
if ($idu == 55291) {
	$e = new Email(array('webenquetes@webenquetes.com.br', '%@,_#?{.66$('), $_POST["ini_date"], $_POST["end_date"]);
	$e->send_marketing();
}
?>

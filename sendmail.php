<?php
class Email {
	public static function send_email ($from, $recips, $subject, $body, $show_recipient=FALSE) {
		require_once dirname(dirname(getcwd()))."/php/lib/php/Mail.php";
		global $status;
		$host = "ssl://smtp.gmail.com";
		$port = "465";
		$username = 'tfrubim@gmail.com';
		$password = 'ulaowubbnkccyxxm';
		foreach ($recips as $to) {
			$headers = array ('From' => $from, 'To' => $to,'Subject' => $subject);
			$smtp = Mail::factory('smtp',
			  array ('host' => $host,
				'port' => $port,
				'auth' => true,
				'username' => $username,
				'password' => $password));
			
			$mail = $smtp->send($to, $headers, $body);
			$to_email = (!$show_recipient) ? '' : " para $to";
				
			if (PEAR::isError($mail)) {
				$status = "Ocorreu um rerro no envio da sua mensagem$to_email.";
			} else {
				$status = "Mensagem$to_email enviada corretamente!<br>";
			}
		}
	}
}
?>

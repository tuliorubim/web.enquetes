<?php
session_start();
include 'bd.php';
if ($idu == 55291) {
	$args = array();
	$args[0][0] = ''; $args[0][1] = 'gutomelo22@hotmail.com'; $args[0][2] = 1793; $args[0][3] = 'Conselheiro Tutelar 2019'; $args[0][4] = 100; $args[0][5] = 299;
	$args[1][0] = ''; $args[1][1] = 'caio.de.sousa@gmail.com'; $args[1][2] = 1497; $args[1][3] = 'Quem você gostaria de ver em Porto Alegre ??'; $args[1][4] = 100; $args[1][5] = 263;
	$args[2][0] = ''; $args[2][1] = 'solonlelesadvogado@hotmail.com'; $args[2][2] = 1506; $args[2][3] = 'Em quem votaria para Prefeito de Adustina?'; $args[2][4] = 100; $args[2][5] = 263;
	$args[3][0] = ''; $args[3][1] = 'airtonmatiassjd@hotmail.com'; $args[3][2] = 1416; $args[3][3] = 'Eleições 2020 São José do Divino - PI'; $args[3][4] = 100; $args[3][5] = 237;
	$args[4][0] = ''; $args[4][1] = 'adsonsantana.a@gmail.com'; $args[4][2] = 1511; $args[4][3] = 'Em quem você votaria para Prefeito de Sítio do Qui...'; $args[4][4] = 100; $args[4][5] = 232;
	$args[5][0] = ''; $args[5][1] = 'eduardordl@hotmail.com'; $args[5][2] = 1279; $args[5][3] = ' DO RESGATE SOBRE O CT DO AZULÃO'; $args[5][4] = 200; $args[5][5] = 210;
	$args[6][0] = ''; $args[6][1] = 'm.arkuinhos@hotmail.com'; $args[6][2] = 1546; $args[6][3] = 'Em quem você votaria para vereador de paripiranga ...'; $args[6][4] = 100; $args[6][5] = 206;
	$args[7][0] = ''; $args[7][1] = 'nimost6@gmail.com'; $args[7][2] = 1499; $args[7][3] = 'Campeonato de vôlei da cruzeiro'; $args[7][4] = 100; $args[7][5] = 204;
	$args[8][0] = ''; $args[8][1] = 'rbezerradasilva24@hotmail.com'; $args[8][2] = 1471; $args[8][3] = 'Eleições 2020'; $args[8][4] = 100; $args[8][5] = 114;
	$args[9][0] = ''; $args[9][1] = 'mariicarioca15@gmail.com'; $args[9][2] = 1740; $args[9][3] = 'Modelo da samba-cançao'; $args[9][4] = 100; $args[9][5] = 109;
	//$args = select("select c.nome, c.usuario, e.idEnquete, e.enquete, 100*ceil((curdate()-convert(e.dt_criacao, date))/100), count(v.dt_voto) from cliente c inner join enquete e on c.idCliente = e.cd_usuario inner join voto v on e.idEnquete = v.cd_enquete where c.cd_servico = 0 and (select count(dt_voto) from voto where cd_enquete = e.idEnquete)/100 > ceil((select curdate()-convert(dt_criacao, date) from enquete where idEnquete = e.idEnquete)/100) group by e.idEnquete order by count(v.dt_voto) desc");
	$POST = array();
	$POST['email'] = 'webenquetes@webenquetes.com.br';
	$POST['name'] = 'Web Enquetes';
	$POST['subject'] = 'Ver resultados parciais completos';
	$auth = array('webenquetes@webenquetes.com.br', '%@,_#?{.66$(');
	for ($i = 0; $args[$i][2] !== NULL; $i++) {
		$email = $args[$i][1];
	  	$nome = (!empty($args[$i][0])) ? $args[$i][0] : substr($email, 0, strpos($email, '@'));
		//$nome = html_encode($nome);
		//$args[$i][3] = html_encode($args[$i][3]);
		if (!empty($email)) {
			$POST['message'] = "Ol&aacute;, ".$nome.", n&oacute;s da Web Enquetes gostar&iacute;amos de te lembrar que sua enquete \"".$args[$i][3]."\" tem ".$args[$i][5]." respostas, mas voc&ecirc; s&oacute; pode ver ".$args[$i][4]." delas, isso por causa do limite imposto pela Web Enquetes de 100 respostas vis&iacute;veis por m&ecirc;s. Se voc&ecirc; deseja ver os resultados parciais completos deste j&aacute;, com todas as respostas, torne-se assinante por apenas R$ 29,90 por m&ecirc;s clicando <a href=\"https://www.webenquetes.com.br/premium.php\">aqui</a>, onde voc&ecirc; poder&aacute; tamb&eacute;m ver outros benef&iacute;cios aos quais o assinante tem direito.<br><br>Atenciosamente, Túlio.";
			sendEmail ($POST, array($email), $auth);
			$status = (strpos($status, "success") !== FALSE) ? "Sua mensagem foi enviada corretamente" : "Ocorreu um erro no envio da sua mensagem";
			$status .= " para ".$email.".<br>";
			$color = (strpos($status, "correta") !== FALSE) ? "green" : "";
			write_status($color);
			$message = "Ol&aacute;, $email, n&oacute;s da Web Enquetes gostar&iacute;amos de te lembrar que sua enquete cujo id &eacute; ".$args[$i][2]." tem ".$args[$i][5]." respostas, mas voc&ecirc; s&oacute; pode ver ".$args[$i][4]." delas, isso por causa do limite imposto pela Web Enquetes de 100 respostas vis&iacute;veis por m&ecirc;s. Se voc&ecirc; deseja ver os resultados parciais completos deste j&aacute;, com todas as respostas, torne-se assinante por apenas R$ 29,90 por m&ecirc;s clicando <a href=\"https://www.webenquetes.com.br/premium.php\">aqui</a>, onde voc&ecirc; poder&aacute; tamb&eacute;m ver outros benef&iacute;cios aos quais o assinante tem direito.<br><br>Atenciosamente, T&uacute;lio.";
			if ($color == 'green') {
				mysqli_query($con, "insert into enviados (email, message) values ('$email', '$message')");
				echo mysqli_error($con);
			}
		}
	}
}
?>
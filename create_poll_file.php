<?php
	session_save_path("/tmp");
	session_start();
	header('Content-Type: application/json');
	require_once "funcoes/funcoesDesign.php";
	
	$db = new DBFunctions();
	
	$con = $db->Connect('localhost', 'webenque_enquetes', 'root', '5hondee5WBa0');
	//$con = $db->Connect('localhost', 'webenque_enquetes', 'root', ''); 
	mysqli_query($con, "SET NAMES 'utf8'");
	mysqli_query($con, 'SET character_set_connection=utf8');
	mysqli_query($con, 'SET character_set_client=utf8');
	mysqli_query($con, 'SET character_set_results=utf8');
	$idu = ($_SESSION['user'] !== NULL) ? $_SESSION['user'] : $_POST['cd_usuario'];
	$dateformat = "d/m/Y";
	$timeformat = "H:i:s";
	$ide = $_POST['ide'];
	$cd_servico = $_POST['cds'];
	$pollcode = '';
	$json = '{ "status" : ';
	if (!empty($ide)) {
		$db->select("select disponivel from enquete where idEnquete = $ide", array('disponivel'));
	}
	if ($disponivel) {
		$db->select("select e.cd_usuario, e.enquete, e.introducao, e.dt_criacao, e.duracao, e.hide_results from enquete e where idEnquete = $ide", array('cd_usuario', 'enquete', 'introducao', 'dt_criacao', 'duracao', 'hide_results'));
		$db->select("select code from enquete where idEnquete = $ide", array("pollcode"), true);
		$variaveis = array("idPergunta", "cd_enquete", "pergunta", "multipla_resposta");
		$tipos = array("integer", "integer", "varchar", "boolean");
		$labels = array();
		$inputs = array("hidden", "hidden", "html", "hidden");
		$maxlengths = array("", "", "1024", "");
		$properties = NULL;
		$tabela = "pergunta";
		$enderecos = array();
		
		$formTabela1 = array($variaveis, $tipos, $labels, $inputs, $enderecos, $tabela, $maxlengths, $properties);
		
		$variaveis2 = array("idResposta", "cd_pergunta", "resposta");
		$tipos2 = array("integer", "integer", "varchar");
		$labels2 = array();
		$inputs2 = array("hidden", "hidden", "radio");
		$maxlengths2 = array("", "", "1024");
		$tabela2 = "resposta";
		$enderecos2 = array();
		
		$formTabela2 = array($variaveis2, $tipos2, $labels2, $inputs2, $enderecos2, $tabela2, $maxlengths2, array(), 0, "readonly");
		
		$inds = array(0, 0);
		$select = array('', 'form', 'no_print');
		$args = $db->select("select idPergunta, multipla_resposta from pergunta where cd_enquete = $ide order by idPergunta");
		$select[5] = true;
		$idp = 0;
		$h = '';
		for ($i = 0; $args[$i][0]; $i++) {
			if ($idp !== $args[$i][0]) {
				$db->select("SELECT count(idResposta) from resposta where cd_Pergunta = ".$args[$i][0], array('cont'));
				$formTabela2[8] = $cont-1;
			}
			$idp = $args[$i][0];
			$select[0] = "select p.*, r.* from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta where idPergunta = $idp order by r.idResposta";
			$formTabela1[8] =  $i+1;
			if ($args[$i][1]) {
				$formTabela2[3][2] = "checkbox";
				for ($j = 0; $j < $cont; $j++) {
					$formTabela2[7][$j] = array('', '', "onclick='this.value=this.checked;'");		
				}
			} else {
				$formTabela2[3][2] = "radio";
				$formTabela2[7] = array();
			}
			/*if ($i === 1)
				$select[4] = "no_print";*/
			$design = new DesignFunctions();
			$design->con = $con;
			$design->formGeral ($_SESSION, $formTabela1, $formTabela2, NULL, $select, true);
			$h .= $design->html;
		}
		if ($cd_usuario !== NULL && $cd_usuario == $idu) {
			if ($cd_servico == 0) {
				$h = "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>\n<form name='enquete' method='post' action='https://www.webenquetes.com.br/resultados_parciais.php?ide=$ide'>\n<input type='hidden' name='pollcode' value='$pollcode'>\n<a href='https://www.webenquetes.com.br/'><img src='https://www.webenquetes.com.br/img/logo-web-enquetes.png' width='120'></a>\n<p><a href='https://www.webenquetes.com.br/resultados_parciais.php?ide=$ide&site=true' id='result'>Ver resultados parciais. </a></p>\n".$h."<div id='botao_votar'><input type='submit' name='votar' id='responder' value='Votar' /></div></form>";
			} else {
				$htm = $h;
			 	$h = "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>\n";
				if (!$hide_results) $h .= "<p><a href='https://www.webenquetes.com.br/resultados_parciais.php?ide=$ide' id='result' target='_blank'>Ver resultados parciais. </a></p>\n";
				$h .= "<div id='question_num' style='font-size:18px; font-weight:800'></div>\n<form name='enquete' method='post'>\n<input type='hidden' name='ide' id='ide' value='$ide'>\n".$htm."<div id='botao_votar'><button type='button' id='responder'>RESPONDER</button></div></form>\n
<script language='javascript'>
var mr = []; ";
				for ($j = 0; $j < $i; $j++) {
					$h .= "mr[$j] = ".$args[$j][1]."; ";
				}
				$h .= "var num_questions = $i;
var cd_enquete = $ide;
function selectQuestion (n) {
	if (num_questions > 1)
		\$('#question_num').html('Quest&atilde;o '+n+':');
	for (i = 1; \$('#data'+i).html() != null; i++) {
		if (i !== n) {
			\$('#data'+i).hide();
		} else \$('#data'+i).fadeIn(1000);
	}	
}
function setCookie(cname, cvalue, exyears) {
  var d = new Date();
  d.setTime(d.getTime() + (exyears * 365 * 24 * 60 * 60 * 1000));
  var expires = 'expires='+d.toUTCString();
  document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
}

function getCookie(cname) {
  var name = cname + '=';
  var ca = document.cookie.split(';');
  for(var i = 0; i < ca.length; i++) {
	var c = ca[i];
	while (c.charAt(0) == ' ') {
	  c = c.substring(1);
	}
	if (c.indexOf(name) == 0) {
	  return c.substring(name.length, c.length);
	}
  }
  return '';
}
\$(document).ready(function () {
	var q = 1;
	selectQuestion(q);
	for (i = 1; \$('#d_pergunta'+i).html() != null; i++) {
		\$('#d_pergunta'+i).css({'font-size' : '18px', 'font-weight' : '800'});
		for (j = 0; \$('#d_resposta'+i+'_'+j).html() != null; j++) {
			\$('#d_resposta'+i+'_'+j).css('font-size', '18px');
		}
	}
	\$('#responder').click(function () {
		if (cd_enquete !== 0) {
			cd_resposta = [];
			i = 0;
			if (mr[q-1] == 0) {
				cd_resposta[0] = eval('document.enquete.resposta'+q+'_.value');
			}
			else {
				i = 0;
				for (j = 0; \$('#idResposta'+q+'_'+j).val() != null; j++) {
					if (\$('#resposta'+q+'_'+j).prop('checked')) {
						cd_resposta[i] = \$('#idResposta'+q+'_'+j).val();
						i++;
					}
				}
			}
			url = 'https://www.webenquetes.com.br/';
			\$.ajax({
				url: url+'voto2.php',
				type: 'GET',
				dataType: 'jsonp',
				data: {
					cd_usuario: getCookie('user'),
					cd_enquete: cd_enquete,
					cd_pergunta: \$('#idPergunta'+q).val(),
					cd_resposta: cd_resposta
				},
				success: function (result) {
					\$('#status').html(result['status']);
					if (result['user'] != null) {
						setCookie('user', result['user'], 10);
					}
					if (result['status'] == '') {
						q++;
						selectQuestion(q);
					} else if (result['status'].indexOf('sucesso') != -1) {
						alert(result['status']);
						window.open(url+'resultados_parciais2.php?ide='+cd_enquete, 'Resultados Parciais', 'width=800 height=400');
					} 
				},
				error: function (xhr, s, e) {
					alert(xhr.responseText);
				}
			});
		}
	});
});
</script>";
			}
			$db->save_file ($h, $ide.'.txt', 'w');
		}
		if (file_exists($ide.'.txt')) {
			$json .= ' "success" }';
		} else $json .= ' "failure" }';
	} else 	$json .= ' "failure2" }';
	echo $json;
?>

<?php
session_start();
include "bd.php";
contagem2 ('cont_enquete', true, $_GET['ide']); 
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php include "head.php"; ?> 
<body>
<?php include "header.php"; ?> 
<div class="container">
	<div class="row">
    <!-- COLUNA ESQUERA -->
    <div class="col-md-7">
        
        <!-- INICIO / AQUI É O ESPAÇO AONDE VOCÊ IRA COLOCAR O CONTÉUDO DAS OUTRAS PAGINAS INTERNAS QUE VAI APARECER NA COLUNA ESQUERDA -->
	<script language="javascript">
	var mr = []; 
	</script>
	<div id='status'></div>
	<form name="form" method="post">
    <?php
	$ide = $_GET['ide'];
	$multipla_resposta = 0;
	$pollcode = '';
	if (!empty($ide)) {
		select("select disponivel, cd_usuario from enquete where idEnquete = $ide", array('disponivel', 'cd_usuario'));
	}
	if ($disponivel || $cd_usuario == $idu) {
		if (!$disponivel) {
			$status = "Esta enquete n&atilde;o est&aacute; dispon&iacute;vel. S&oacute; voc&ecirc; pode v&ecirc;-la.";
			echo "<script language='javascript'>$('#status').html('<font color=red>$status</font>');</script>";
		}
		select("select c.idCategoria, c.categoria, e.cd_usuario, e.enquete, e.introducao, e.dt_criacao, e.duracao, e.code, e.hide_results, cl.cd_servico from categoria c inner join enquete e on c.idCategoria = e.cd_categoria inner join cliente cl on e.cd_usuario = cl.idCliente where idEnquete = $ide", array('idCategoria', 'categoria', 'cd_usuario', 'enquete', 'introducao', 'dt_criacao', 'duracao', 'pollcode', 'hide_results', 'cd_servico'));
		echo "<h4>$enquete</h4>";
		echo "<p>Enquete sobre $categoria criada em ".std_date_create($dt_criacao)."</p>";
		if (!$hide_results || $_SESSION[$idSession] === $cd_usuario) {
	?>
		<p><a href='resultados_parciais.php?ide=<?php echo $ide; ?>' id="result">Ver resultados parciais. </a></p>
	<?php
		}
		if (!empty($introducao))
			echo "<p>Introdu&ccedil;&atilde;o: $introducao</p>";
	?>
	<div id="question_num" style="font-size:18px; font-weight:800"></div>
	<?php
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
		$select = array('', 'form');
		$args = select("select idPergunta, multipla_resposta from pergunta where cd_enquete = $ide order by idPergunta");
		$POST = $_POST;
		$POST["butPres"] = "Select";
		$idp = 0;
		$h = '';
		$mr = array();
		for ($i = 0; $args[$i][0]; $i++) {
			if ($idp !== $args[$i][0]) {
				select("SELECT count(idResposta) from resposta where cd_Pergunta = ".$args[$i][0], array('cont'));
				$formTabela2[8] = $cont-1;
			}
			$idp = $args[$i][0];
			$select[0] = "select p.*, r.* from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta where idPergunta = $idp";
			$formTabela1[8] =  $i+1;
			for ($j = 0; $j < $cont; $j++) {
				$formTabela2[7][$j] = array('', '', "onclick='functionAnswer($i, $j);'");			
			}
			if ($args[$i][1]) {
				$formTabela2[3][2] = "checkbox";
				for ($j = 0; $j < $cont; $j++) {
					$p = $formTabela2[7][$j][2];
					$p = insere($p, " this.value=this.checked;", strlen($p)-1);
					$formTabela2[7][$j][2] = $p;			
				}
			} else $formTabela2[3][2] = "radio";
			/*if ($i === 1)
				$select[4] = "no_print";*/
			adminPage ($POST, $_FILES, $SESSION, $formTabela1, $formTabela2, NULL, $select, true);
			$h .= $html;
			$mr[$i] = ($multipla_resposta[0] != '1') ? 0 : 1;
	?>
			<script language="javascript">
			mr[<?php echo $i;?>] = <?php echo ($multipla_resposta[0] != '1') ? 0 : 1; ?>;
			</script>
	<?php		
		}
	?>
	<script language="javascript">var num_questions = <?php echo $i;?>;</script>
	<?php
		if ($cd_usuario !== NULL && $cd_usuario == $idu) {
			if ($cd_servico == 0) {
				$h = "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script><form name='enquete' method='post' action='https://www.webenquetes.com.br/resultados_parciais.php?ide=$ide'>\n<input type='hidden' name='pollcode' value='$pollcode'>\n<a href='https://www.webenquetes.com.br/'><img src='https://www.webenquetes.com.br/img/logo-web-enquetes.png' width='120'></a>\n".$h."<div id='botao_votar'><input type='submit' name='votar' id='responder' value='Votar' /></div></form>";
			} else {
			 	$h = "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>\n<div id='question_num' style='font-size:18px; font-weight:800'></div>\n<form name='enquete' method='post'>\n<input type='hidden' name='ide' id='ide' value='$ide'>\n".$h."<div id='botao_votar'><button type='button' id='responder'>RESPONDER</button></div></form>\n
<script language='javascript'>
var mr = []; ";
				for ($j = 0; $j < $i; $j++) {
					$h .= "mr[$j] = ".$mr[$j]."; ";
				}
				$h .= "var num_questions = $i;
var cd_enquete = $ide;
function functionAnswer(i, j){}
function selectQuestion (n) {
	if (num_questions > 1)
		\$('#question_num').html('Quest&atilde;o '+n+':');
	for (i = 1; \$('#data'+i).html() != null; i++) {
		if (i !== n) {
			\$('#data'+i).hide();
		} else \$('#data'+i).fadeIn(1000);
	}	
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
				url: url+'voto.php',
				type: 'GET',
				dataType: 'jsonp',
				data: {
					cd_enquete: cd_enquete,
					cd_pergunta: \$('#idPergunta'+q).val(),
					cd_resposta: cd_resposta
				},
				success: function (result) {
					\$('#status').html(result['status']);
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
			save_file ($h, $ide.'.txt', 'w');
		}
	?>
	<br>
	<button type="button" class="btn btn-primary estilo-modal" id="responder">RESPONDER</button>
	<?php
	} else {
		$variaveis = array("cd_usuario", "cd_enquete", "cd_pergunta", "cd_resposta", "dt_voto");
		$tipos = array("integer", "integer", "integer", "integer", "datetime");
		$tabela = "voto";
		$formTabela = array($variaveis, $tipos, array(), array(), array(), $tabela);
		createTable($formTabela, 4);
		$status = "Esta enquete n&atilde;o est&aacute; dispon&iacute;vel no momento.";
		echo "<script language='javascript'>$('#status').html('<font color=red>$status</font>');</script>";
	}
	?>
	
    <!-- AQUI É O LIMITE PARA A COLUNA ESQUERDA, O CONTEÚDO DEVE ESTAR O ÍNICIO E AQUI O FIM -->
	</form>
    </div>
    
	<?php include "sidebar.php"; ?>    
    
    </div>
	<div class="row">
    <div class="col-md-12">
    <div class="bt-all">
    <a href="enquetes.php" class="btn btn-lg btn-success all-enq-home">TODAS AS ENQUETES</a>
	</div>
    </div>
    </div>
</div>
<script language="javascript">
var cd_enquete = <?php echo (!empty($ide)) ? $ide : 0; ?>;
function functionAnswer(i, j){}
function selectQuestion (n) {
	if (num_questions > 1)
		$("#question_num").html("Quest&atilde;o "+n+":");
	for (i = 1; $("#data"+i).html() != null; i++) {
		if (i !== n) {
			$("#data"+i).hide();
		} else $("#data"+i).fadeIn(1000);
	}	
}
$(document).ready(function () {
	var q = 1;
	selectQuestion(q);
	for (i = 1; $('#d_pergunta'+i).html() != null; i++) {
		$('#d_pergunta'+i).css({"font-size" : "18px", "font-weight" : "800"});
		for (j = 0; $('#d_resposta'+i+'_'+j).html() != null; j++) {
			$('#d_resposta'+i+'_'+j).css("font-size", "18px");
		}
	}
	$("#responder").click(function () {
		if (cd_enquete !== 0) {
			cd_resposta = [];
			i = 0;
			if (mr[q-1] == 0) {
				cd_resposta[0] = eval("document.form.resposta"+q+"_.value");
			}
			else {
				i = 0;
				for (j = 0; $('#idResposta'+q+'_'+j).val() != null; j++) {
					if ($('#resposta'+q+'_'+j).prop('checked')) {
						cd_resposta[i] = $('#idResposta'+q+'_'+j).val();
						i++;
					}
				}
			}
			$.ajax({
				url: 'voto.php',
				type: 'GET',
				dataType: 'jsonp',
				data: {
					cd_enquete: cd_enquete,
					cd_pergunta: $("#idPergunta"+q).val(),
					cd_resposta: cd_resposta
				},
				success: function (result) {
					$("#status").html(result['status']);
					if (result['status'] == '') {
						q++;
						selectQuestion(q);
					} else if (result['status'].indexOf("sucesso") != -1) {
						alert(result['status']);
						window.location.href = "resultados_parciais.php?ide="+cd_enquete;
					} 
				},
				error: function (xhr, s, e) {
					alert(xhr.responseText);
				}
			});
		}
	});
});
</script>

<?php include "categorias.php"; ?>

<!-- bkg-footer -->
<div class="clearfix">
<?php include "footer.php"; ?>
</div>

</body>
</html>
<?php
include "bd.php";
//contagem2 ('cont_enquete', true, $_GET['ide']); 
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
	<div id='status'></div>
	<form name="form" method="post">
    <?php
	$ide = $_GET['ide'];
	//$multipla_resposta = 0;
	if (!empty($ide)) {
		$we->select("select disponivel, cd_usuario from enquete where idEnquete = $ide", array('disponivel', 'cd_usuario'));
	}
	if ($disponivel || $cd_usuario == $we->idu) {
		require_once "create_html.php";
		$poll_html = new Create_HTML($ide);
		$poll_html->con = $we->con;
		$poll_html->idu = $we->idu;
		$poll_html->create_poll_header($disponivel, $cd_usuario);
	?>
	<div class="fb-like" data-href="https://www.facebook.com/WebEnquetesEPesquisas/" data-width="100" data-layout="standard" data-action="like" data-size="small" style='margin: 3px;' data-show-faces="true" data-share="false"></div>
	<div id="question_num" style="font-size:18px; font-weight:800"></div><button type="button" disabled="disabled" class="glyphicon glyphicon-chevron-left" id="previous"></button>
	<button type="button" class="glyphicon glyphicon-chevron-right" id="next"></button>
		<?php
		$poll = $poll_html->select_html_from_db(true);
		if (!$poll[0] && $poll[1]) {
			echo $poll[1];
		} else {
			$poll_html->create_poll();
			$poll_html->save_html_to_db($poll_html->html, true);
			echo $poll_html->html;
		}	
		?>	
	<br>
	<button type="button" class="btn btn-primary estilo-modal" id="responder">RESPONDER</button>
	<script language="javascript">
	if (num_questions == 1) {
		$("#previous").css("display", "none");
		$("#next").css("display", "none");
	}
	</script>
	<?php
	} else {
		$variaveis = array("cd_usuario", "cd_enquete", "cd_pergunta", "cd_resposta", "dt_voto");
		$tipos = array("integer", "integer", "integer", "integer", "datetime");
		$tabela = "voto";
		$formTabela = array($variaveis, $tipos, array(), array(), array(), $tabela);
		$we->createTable($formTabela, 4);
		echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=resultados_parciais.php?ide=$ide'>";
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

function selectQuestion (n) {
	if (num_questions > 1)
		$("#question_num").html("Quest&atilde;o "+n+":");
	for (i = 1; $("#data"+i).html() != null; i++) {
		if (i !== n) {
			$("#data"+i).hide();
		} else $("#data"+i).fadeIn(1000);
	}	
	if (n == 1) $("#previous").prop("disabled", true);
	if (n == 2) $("#previous").prop("disabled", false);
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
	$("#previous").click(function () {
		q--;
		selectQuestion(q);
		//if (q == num_questions-1) $("#next").prop("disabled", false);
	});
	$("#next").click(function () {
		if (q == num_questions) {
			q--; 
			if (confirm("Deseja terminar a enquete e ver os resultados parciais?")) {
				document.location.href = "resultados_parciais.php?ide="+cd_enquete;
			}  
		} 
		q++;
		selectQuestion(q);
	});
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
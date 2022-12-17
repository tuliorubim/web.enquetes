<?php
include "bd.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php include "head.php"; ?> 
<body>
<div class="container">
	<div class="row">
    <!-- COLUNA ESQUERA -->
    <div class="col-md-7">
        
        <!-- INICIO / AQUI � O ESPA�O AONDE VOC� IRA COLOCAR O CONT�UDO DAS OUTRAS PAGINAS INTERNAS QUE VAI APARECER NA COLUNA ESQUERDA -->
 	<form name="form" method="post">
    <?php
	$idEnquete = $_GET['ide'];
	$esconder = false;
	$we->select("select e.enquete, e.cd_usuario, e.disponivel, e.hide_results, cl.cd_servico from enquete e inner join cliente cl on e.cd_usuario = cl.idCliente where idEnquete = $idEnquete", array("enquete", "cd_usuario", "disponivel", "hide_results", "cd_servico"));
	$we->select("select code from enquete where idEnquete = $idEnquete", array("code"), true);
	if (true) {
		$result = new Result($we->idu, $we->con, $idEnquete);
		if (!$disponivel) {
			$status = "Enquete encerrada.";
			echo "<script language='javascript'>$('#status').html('<font color=red>$status</font>');</script>";
		}
		if ($_POST['pollcode'] === $code) {
			$result->processa_voto_remoto($idEnquete);
		}
		if (!$hide_results || $we->idu === $cd_usuario) {
		?>
		<h2>Resultados parciais</h2>
		<h6>da enquete: <?php echo $enquete; ?></h6>
		<a href="enquete.php?ide=<?php echo $idEnquete;?>">Voltar</a>
		<p><div class="fb-like" data-href="https://www.facebook.com/WebEnquetesEPesquisas/" data-width="100" data-layout="standard" data-action="like" data-size="small" style='margin: 3px;' data-show-faces="true" data-share="false"></div></p>
		<?php
			$site = $_GET['site'];
			if ($site === 'true' && $cd_usuario == $we->idu) {
				echo "<p><font color='red'>Caro $nome, se voc&ecirc; quer que a enquete no seu site mostre uma pergunta de cada vez, ocupando menos espa&ccedil;o no seu site, e quer que a marca da Web Enquetes seja removida, fa&ccedil;a <a href='premium.php'>aqui</a> sua assinatura e adquira tamb&eacute;m outras vantagens al&eacute;m dessas, como assinante. Ap&oacute;s adquirir a assinatura, fa&ccedil;a o download do novo HTML da sua enquete e cole-o no lugar do HTML antigo da mesma em seu site, para que voc&ecirc; obtenha os benef&iacute;cios acima citados. Somente voc&ecirc; pode ver esta mensagem.</font></p>";
			}
			$result->create_results($cd_servico);
			echo $result->html;
			$variaveis = array("idComentario", "cd_cliente", "cd_enquete", "comentario", "dt_comentario");
			$tipos = array("integer", "integer", "integer", "varchar", "dateTime");
			$maxlengths = array("", "", "", "2048", "");
			$tabela = "comentario";
			$formTabela = array($variaveis, $tipos, NULL, NULL, NULL, $tabela, $maxlengths);
			$result->createTable($formTabela, 1);
		?>
		<div id='novo_comentario'>
		<br><br>
		<b>Poste um coment&aacute;rio:</b><br />
		<form name='novo_comentario' method="post">
			<textarea name="comentario" id="comentario" rows="3" cols="40"></textarea>
			<div style="margin-top: 10px;"><input type="button" class="btn btn-primary estilo-modal" name="postar" value="Postar" /></div>
		</form>	
		</div>
		<div id='comentarios'>
	<?php
			$result->print_comments();
	?>
		</div>
	<?php		
		} else {
			$result->create_results2();
			if (!empty($result->html)) {
	?>	
				<h2>Resultado do teste</h2>
				<a href="enquete.php?ide=<?php echo $idEnquete;?>">Voltar</a>
	<?php	
				echo $result->html;		
			}
		}
	} else {
		$status = "Os resultados parciais desta enquete n&atilde;o est&atilde;o dispon&iacute;veis no momento.";
		echo "<script language='javascript'>$('#status').html('<font color=red>$status</font>');</script>";
	}
	?>
	<script language="javascript">
	ide = <?php echo $idEnquete;?>;
	idu = <?php echo $we->idu;?>;
	cds = <?php echo $cd_servico;?>;
	data_lim = '<?php echo $data_lim;?>';
	$(document).ready( function () {
		$(".item1").css({"margin": "10px", "font-size": "15px", "font-weight": "800"});
		$(".resposta").click(function () {
			idr = null;
			if (cds === 0) {
				idr = document.form.resposta.value;
			} else {
				idr = [];
				j = 0;
				res = document.form.resposta0;
				for (i = 0; res; i++) {
					if ($("input[name='resposta"+i+"']").prop("checked") == true) {
						idr[j] = $("input[name='resposta"+i+"']").val();
						j++;
					}
					eval("res = document.form.resposta"+(i+1));
				}
			}	
			$.ajax({
				url: "result.php",
				type: "POST",
				dataType: "json",
				data: {
					ide: ide,
					idr: idr,
					cds: cds, 
					data_lim: data_lim
				},
				success: function (result) {
					$(".resposta").hide();
					$("#ve").html(result['ve']);
					for (i = 0; result['vp'+i] != null; i++) {
						$("#vp"+i).html(result['vp'+i]);
					}
					for (i = 0; result['votos'+i] != null; i++) {
						$("#votos"+i).html(result['votos'+i]);
					}
					$(".resposta").fadeIn(1000);
				},
				error: function (xhr, s, e) {
					alert(xhr.responseText);
				}
			});
		});
		$("input[name='postar']").click(function () {
			comment = $("#comentario").val();
			/*offset = 0;
			comment_aux = comment;
			ht_pos1 = comment_aux.indexOf("https://", offset);
			ht_pos2 = comment_aux.indexOf("http://", offset);
			while (ht_pos1 !== -1 || ht_pos2 !== -1) {
				ht_pos = (ht_pos1
				lnk = comment_aux.substr();
			}*/
			if (comment.indexOf("'") === -1 && comment.length > 0 && idu > 0) {
				$.ajax({
					url: "comentarios.php",
					type: "POST",
					dataType: "json",
					data: {
						ide: ide,
						idu: idu,
						comment: comment
					},
					success: function (result) {
						$("#comentarios").prepend(result["comment"]);
						$("#comentario").val('');
					},
					error: function (xhr, s, e) {
						alert(xhr.responseText);
					}
				});
			} else if (comment.indexOf("'") !== -1) {
				alert("Aspas simples (') n\u00e3o s\u00e3o permitidas.");
			}
		});
	});
	</script>
	
    <!-- AQUI � O LIMITE PARA A COLUNA ESQUERDA, O CONTE�DO DEVE ESTAR O �NICIO E AQUI O FIM -->
    </div>
    </div>
</div>

<!-- bkg-footer -->
<div class="clearfix">
</div>

</body>
</html>
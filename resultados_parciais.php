<?php
include "bd.php";
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
 	<form name="form" method="post">
    <?php
	require_once "create_html.php";
	$idEnquete = $_GET['ide'];
	$esconder = false;
	$we->select("select e.cd_usuario, e.disponivel, e.hide_results, cl.cd_servico from enquete e inner join cliente cl on e.cd_usuario = cl.idCliente where idEnquete = $idEnquete", array("cd_usuario", "disponivel", "hide_results", "cd_servico"));
	$we->select("select code from enquete where idEnquete = $idEnquete", array("code"), true);
	if (true) {
		$poll_html = new Create_HTML($idEnquete);
		$poll_html->con = $we->con;
		$poll_html->idu = $we->idu;
		if (!$disponivel) {
			$status = "Enquete encerrada.";
			echo "<script language='javascript'>$('#status').html('<font color=red>$status</font>');</script>";
		}
		if ($_POST['pollcode'] === $code) {
			$we->processa_voto_remoto($idEnquete);
		}
		
		if (!$hide_results || $we->idu === $cd_usuario) {
			$poll_html->create_result_header ($cd_servico, $cd_usuario, $hide_results);
		?>	
			<a href="enquete.php?ide=<?php echo $idEnquete;?>">Voltar</a>
			<p><div class="fb-like" data-href="https://www.facebook.com/WebEnquetesEPesquisas/" data-width="100" data-layout="standard" data-action="like" data-size="small" style='margin: 3px;' data-show-faces="true" data-share="false"></div></p>
		<?php
			$site = $_GET['site'];
			if ($site === 'true' && $cd_usuario == $we->idu) {
				echo "<p><font color='red'>Caro $nome, se voc&ecirc; quer que a enquete no seu site mostre uma pergunta de cada vez, ocupando menos espa&ccedil;o no seu site, e quer que a marca da Web Enquetes seja removida, fa&ccedil;a <a href='premium.php'>aqui</a> sua assinatura e adquira tamb&eacute;m outras vantagens al&eacute;m dessas, como assinante. Ap&oacute;s adquirir a assinatura, fa&ccedil;a o download do novo HTML da sua enquete e cole-o no lugar do HTML antigo da mesma em seu site, para que voc&ecirc; obtenha os benef&iacute;cios acima citados. Somente voc&ecirc; pode ver esta mensagem.</font></p>";
			}
			$poll_html->create_results($cd_servico);
			echo $poll_html->html;
			$variaveis = array("idComentario", "cd_cliente", "cd_enquete", "comentario", "dt_comentario");
			$tipos = array("integer", "integer", "integer", "varchar", "dateTime");
			$maxlengths = array("", "", "", "2048", "");
			$tabela = "comentario";
			$formTabela = array($variaveis, $tipos, NULL, NULL, NULL, $tabela, $maxlengths);
			$we->createTable($formTabela, 1);
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
			$poll_html->print_comments();
	?>
		</div>
	<?php		
		} else {
			$status = "Agradecemos o seu voto. Ele foi corretamente computado.";
			echo "<script language='javascript'>$('#status').html('<font color=green>$status</font>');</script>";
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
	var idr = null;
	$(document).ready( function () {
		$(".resposta").click(function () {
			idr = null;
			if (cds === 0) {
				idr = document.form.resposta.value;
				if ($("#pdf").html() != null) {
					$("#pdf").attr("href", "pdf_result.php?ide="+ide+"&idr0="+idr);
				}
			} else {
				idr = [];
				j = 0;
				res = document.form.resposta0;
				pdf_page = "pdf_result.php?ide="+ide;
				for (i = 0; res; i++) {
					if ($("input[name='resposta"+i+"']").prop("checked") == true) {
						idr[j] = $("input[name='resposta"+i+"']").val();
						pdf_page += "&idr"+j+"="+idr[j];
						j++;
					}
					eval("res = document.form.resposta"+(i+1));
				}
				if ($("#pdf").html() != null) {
					$("#pdf").attr("href", pdf_page);
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
			if (/*comment.indexOf("'") === -1 && */comment.length > 0 && idu > 0) {
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
	/*$("#pdf").click(function () {
		$.ajax({
			url: "pdf_result.php",
			type: "POST",
			dataType: "json",
			data: {
				ide: ide,
				idr: idr
			},
			success: function (result) {
			
			}, 
			error: function (xhr, s, e) {
				alert(xhr.responseText);
			}
		});
	});*/
	</script>
	
    <!-- AQUI É O LIMITE PARA A COLUNA ESQUERDA, O CONTEÚDO DEVE ESTAR O ÍNICIO E AQUI O FIM -->
    </div>
    
	<?php include "sidebar.php"; ?>    
    
    </div>
</div>

<?php include "categorias.php"; ?>

<!-- bkg-footer -->
<div class="clearfix">
<?php include "footer.php"; ?>
</div>

</body>
</html>
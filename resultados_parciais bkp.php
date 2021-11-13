<?php
session_start();
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
	$idEnquete = $_GET['ide'];
	$esconder = false;
	select("select e.enquete, e.cd_usuario, e.introducao, e.dt_criacao, e.code, e.disponivel, e.hide_results, e.acima_de_cem, cl.cd_servico from enquete e inner join cliente cl on e.cd_usuario = cl.idCliente where idEnquete = $idEnquete", array("enquete", "cd_usuario", "introducao", "dt_criacao", "code", "disponivel", "hide_results", "acima_de_cem", "cd_servico"));
	if (true) {
		if (!$disponivel) {
			$status = "Enquete encerrada.";
			echo "<script language='javascript'>$('#status').html('<font color=red>$status</font>');</script>";
		}
		if ($_POST['pollcode'] === $code) {
			$tabela = "voto";	
			$i = 1;
			$data = date('Y-m-d H:i:s');
			select("select cd_usuario, cd_enquete from $tabela where cd_usuario = $idu and cd_enquete = $idEnquete limit 1", array("cdu", "cde"));
			$condicao = ($cdu !== NULL && $cde !== NULL);
			if ($condicao) {
				mysqli_query($con, "delete from $tabela where cd_usuario = $cdu and cd_enquete = $cde");
			}
			$sucesso = false;
			if ($idu === 0) {
				mysqli_query($con, "insert into cliente (data_cadastro) values ('$data')");
				select("select max(idCliente) from cliente", array("last_user"));
				$_SESSION[$idSession] = $last_user;
				$idu = $last_user;
			}
			while ($_POST["idPergunta$i"] !== NULL) {
				$j = 0;
				$idP = $_POST["idPergunta$i"];
				select("select multipla_resposta from pergunta where idPergunta = $idP", array("mr"));
				if ($mr == 0) {
					$idR = $_POST["resposta".$i."_"];
					if (!empty($idR)) {
						$sql = "insert into $tabela (cd_usuario, cd_enquete, cd_pergunta, cd_resposta, dt_voto) values ($idu, $idEnquete, $idP, $idR, '$data') ";
						mysqli_query($con, $sql);
						if (!mysqli_error($con)) $sucesso = true;
						else {
							echo mysqli_error($con);
							$sucesso = false;
							break;
						}
					}
				}
				else {
					while ($_POST["idResposta".$i."_$j"]  !== NULL) {
						if ($_POST["resposta".$i."_$j"] != NULL) {
							$idR = $_POST["idResposta".$i."_$j"];
							$sql = "insert into $tabela (cd_usuario, cd_enquete, cd_pergunta, cd_resposta, dt_voto) values ($idu, $idEnquete, $idP, $idR, '$data')";
							mysqli_query($con, $sql);
							if (!mysqli_error($con)) $sucesso = true;
							else {
								echo mysqli_error($con);
								$sucesso = false;
								break;
							}
						}
						$j++;
					}
				}
				$i++;
			}
			if ($sucesso && !$condicao) echo "<font color='green'><b>Voto efetuado com sucesso.</b></font><br>";
			elseif ($sucesso) echo "<font color='green'><b>Voc&ecirc; alterou suas respostas com sucesso.</b></font><br>";
		}
		
		$idu = $_SESSION[$idSession];
		if ($idu === NULL) $idu = 0;
		if (!$hide_results || $idu === $cd_usuario) {
		?>
		<h2>Resultados parciais</h2><a href="enquete.php?ide=<?php echo $idEnquete;?>">Voltar</a>
		<p><div class="fb-like" data-href="https://www.facebook.com/WebEnquetesEPesquisas/" data-width="100" data-layout="standard" data-action="like" data-size="small" style='margin: 3px;' data-show-faces="true" data-share="false"></div></p>
		<?php
			$banco = 'enquetes';
			$fields = "select p.pergunta, r.resposta from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta where p.cd_enquete = $idEnquete order by p.idPergunta, r.idResposta";//select p.pergunta, r.resposta, count(v.cd_usuario) from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta inner join voto v on r.idResposta = v.cd_resposta where p.cd_enquete = $idEnquete group by r.idResposta order by p.idPergunta, count(v.cd_usuario) desc
			$i = 0;
			$conteudo = array();
			$sql = "select p.idPergunta, r.idResposta from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta where p.cd_enquete = $idEnquete order by p.idPergunta, r.idResposta";
			$rs = mysqli_query($con, $sql);
			$i = 0;
			$j = -1;
			if ($rs && mysqli_num_rows($rs) > 0) $row = mysqli_fetch_array($rs);
			$idP = NULL;
			$votos_pergunta = 1;
			$vp = array(); 
			$data_lim = '';
			if (false && !$acima_de_cem && ($idu === 0 || $idu === $cd_usuario)) {
				if ($cd_servico == 0) {
					$rs2 = mysqli_query($con, "select dt_voto from voto where cd_enquete = $idEnquete order by dt_voto");
					if ($rs2 && mysqli_num_rows($rs2) > 0) {
						$num_resp = mysqli_num_rows($rs2);
						select("select dt_criacao from enquete where idEnquete = $idEnquete", array('data1'));
						$data2 = date("Y-m-d H:i:s");
						$num_months = ceil((strtotime($data2)-strtotime($data1))/(30*86400));
						$max_resp = 100*$num_months;
						//echo "$num_resp $max_resp";
						if ($num_resp > $max_resp) {
							mysqli_data_seek($rs2, $max_resp-1);
							$data = mysqli_fetch_array($rs2);
							$data_lim = $data['dt_voto'];
						}
					}
				} else {
					mysqli_query($con, "update enquete set acima_de_cem = 1 where idEnquete = $idEnquete");
				}
			}	
			do {
				if ($idP != $row['idPergunta']) {
					$idP = $row['idPergunta'];
					$sql = "select count(cd_usuario) from voto where cd_pergunta = $idP and cd_enquete = $idEnquete";
					if ($data_lim !== '') $sql .= " and dt_voto <= '$data_lim'";
					select ($sql, array("votos_pergunta"));
					$j++;
				}
				$idR = $row["idResposta"];
				$sql = "select count(cd_usuario) from voto where cd_resposta = $idR and cd_pergunta = $idP and cd_enquete = $idEnquete";
				if ($data_lim !== '') $sql .= " and dt_voto <= '$data_lim'";
				select ($sql, array("votos_resposta"));
				$vp[$j] = $votos_pergunta;
				if ($votos_pergunta == 0) $votos_pergunta = 1;
				$porcentagem = round(100*$votos_resposta/$votos_pergunta, 1);
				$conteudo[$i] = " <input ";
				if ($cd_servico == 0) {
					$conteudo[$i] .= "type='radio' name='resposta' ";
				} else $conteudo[$i] .= "type='checkbox' name='resposta$i' ";
				$conteudo[$i] .= "class='resposta' value=$idR title='Clique aqui e saiba como as pessoas que votaram nesta resposta votaram nas respostas das outras perguntas.'> <span id='votos$i' style='font-weight:600;'>".$votos_resposta." votos, $porcentagem % dos votos</span>";
				//echo "$idP $idR ".$conteudo[$i].'<br>';
				$i++;
			} while ($rs && $row = mysqli_fetch_array($rs));
			if ($j === 0) {
		?>
		<script language="javascript">
		$(document).ready(function () {
			$(".resposta").hide();
		});
		</script>
		</form>
		<?php
			}
			$incluiConteudo = array("content" => $conteudo, "beforeAfter" => "after", "level" => 1);
			$conteudo2 = array();
			$args = select("select p.idPergunta count(r.idResposta) from pergunta p inner join resposta r on p.idPergunta = r.cd_pergunta where cd_enquete = $idEnquete");
			for ($i = 0; $args[$i][0] !== NULL; $i++) {
				$conteudo2["indexes"][$i] = $args[$i][1]-1;
				$conteudo2["content"][$i] = "Total de ".$vp[$i]." votos.";
			}	 
			$sql = "select count(cd_usuario) from voto where cd_enquete = $idEnquete";
			select($sql, array('total2'));
			if ($data_lim !== '') $sql .= " and dt_voto <= '$data_lim'";
			select($sql, array('total'));
			$site = $_GET['site'];
			if ($site === 'true' && $cd_usuario == $idu) {
				echo "<p><font color='red'>Caro $nome, se voc&ecirc; quer que a enquete no seu site mostre uma pergunta de cada vez, ocupando menos espa&ccedil;o no seu site, e quer que a marca da Web Enquetes seja removida, fa&ccedil;a <a href='premium.php'>aqui</a> sua assinatura e adquira tamb&eacute;m outras vantagens al&eacute;m dessas, como assinante. Ap&oacute;s adquirir a assinatura, fa&ccedil;a o download do novo HTML da sua enquete e cole-o no lugar do HTML antigo da mesma em seu site, para que voc&ecirc; obtenha os benef&iacute;cios acima citados. Somente voc&ecirc; pode ver esta mensagem.</font></p>";
			}
			if (false && $idu === $cd_usuario && $data_lim !== '') {
		?>
			<p><font color="red">ATEN&Ccedil;&Atilde;O, <?php echo $nome;?>, sua enquete atingiu o limite de exibi&ccedil;&atilde;o de 100 respostas POR M&Ecirc;S. Sua enquete j&aacute; tem <font color="#000000"><?php echo $total2;?></font> votos e as pessoas continuam respondendo-a normalmente, mas voc&ecirc;, no momento, s&oacute; pode ver <font color="#000000"><?php echo $total;?></font> votos. Para que voc&ecirc; possa ver todas as respostas a esta enquete sem esperar o(s) mes(es) seguintes, fa&ccedil;a <a href="premium.php">aqui</a> sua assinatura, onde voc&ecirc; tamb&eacute; adquirir&aacute; outras vantagens importantes. Somente voc&ecirc; pode ver esta mensagem.<br>
			</font></p>
		<?php	
			}
			echo designBlocks (array("database" => $banco, "fields" => $fields, "includeContent" => $incluiConteudo, "separator" => 1, "insertContent" => $conteudo2));
			echo "<div style='margin-top: 10px; font-weight: 800'>Total de respostas: $total.</div>";
			
			$variaveis = array("idComentario", "cd_cliente", "cd_enquete", "comentario", "dt_comentario");
			$tipos = array("integer", "integer", "integer", "varchar", "dateTime");
			$maxlengths = array("", "", "", "2048", "");
			$tabela = "comentario";
			$formTabela = array($variaveis, $tipos, NULL, NULL, NULL, $tabela, $maxlengths);
			createTable($formTabela, 1);
		?>
		<script language="javascript">
			$(document).ready(function() {
				<?php
				for ($i = 0; $vp[$i] !== NULL; $i++) {
				?>
				$("#post_<?php echo $i+1;?>").append("<div style='font-weight:600;'>&nbsp;&nbsp;Total: <?php echo $vp[$i];?> votos.</div>");
				<?php } ?>
			});
		</script>
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
			$sql = "select c.nome, c.usuario, c.idCliente, co.* from cliente c inner join comentario co on c.idCliente = co.cd_cliente where cd_enquete = $idEnquete order by idComentario desc";
			$args = select($sql);
			$r = '';
			for ($i = 0; $args[$i][3] != NULL; $i++) {
				if (!empty($args[$i][0])) {
					if (strpos($args[$i][0], ' ') !== FALSE)
						$r .= substr($args[$i][0], 0, strpos($args[$i][0], ' ')) ;
					else $r .= $args[$i][0];
				} elseif (!empty($args[$i][1])) {
					$r .= substr($args[$i][1], 0, strpos($args[$i][1], '@'));
				} else $r .= "An&ocirc;nimo".$args[$i][2];	
				$r .= " em ".std_datetime_create($args[$i][7]).': ';
				$r .= $args[$i][6]."<br><br>";
			}
			echo $r;
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
	idu = <?php echo $idu;?>;
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
 <!-- ?rea de login do usu?rio -->
<div class="fundo-login">
<div class="container">
<div class="row">
<div class="col-md-6">
	<form method="get" name="search_form"  action="enquetes.php" style="margin-left:25%; margin-top:10px;">
		<div style="float:left;"><input name="procurar" size='25' class="procurar" type="text" title="Procurar enquete por palavras-chave" placeholder="Procurar enquete" ></div>
		<input type="hidden" name="ir" />
		<div><button type="button" id="search" value="Search" class="ir"><span class="glyphicon glyphicon-search"></span></button></div>
	</form>
	<script language="javascript">
	$(document).ready(function () {
		$("#search").click(function () {
			var conditions = [];
			valid = validateForm(document.search_form, conditions);
			if (valid) {
				document.search_form.ir.value = "Ir";
				document.search_form.submit();
			}
		});
	});
	</script>
</div>
<div class="col-md-6">
<div class="modal-home">
<!-- Small modal > Caixa para inserir email e senha -->
<?php
$we->addIP();
if ($we->logged_in) {
	if (empty($we->nome)) {
		$we->nome = substr($we->usuario, 0, strpos($we->usuario, '@'));
	}
	echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>Ol&aacute;, $we->nome <span class='caret'></span></a>";
?>
	<ul class="dropdown-menu" id="menu_cliente" role="menu" aria-labelledby="dLabel">
	  <li><a href="dados.php">Meus Dados</a></li>
	  <li><a href="minhas_enquetes.php">Minhas Enquetes</a></li>
	  <li><a href="criar_enquete.php">Criar Enquete</a></li>
	  <?php
		if (empty($service_data)) {
		?>
			<li role="presentation"><a href="bonus_mensais.php">Assinar</a></li>
		<?php 
		} else { 
		?>
			<li role="presentation"><a href="meu_plano.php">Meu Plano</a></li>
		<?php } ?>
		<li><a href="index.php?login=off">Sair</a></li>
	</ul>
<?php	
} else {
?>
<a href="#" class="btn btn-primary estilo-modal" id="login" onclick="$('#erro').remove()">LOGIN</a>
<?php } ?>
<script language="javascript">
$(document).ready(function () {
	$("#login").click(function () {
		$("#logar").modal("toggle");
	});
});
</script>
<div class="modal fade bs-example-modal-sm" id="logar" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel">Minha Conta</h4>
    </div>
      
    <div class="modal-body">
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#logar" aria-controls="logar" role="tab" data-toggle="tab">Logar</a></li>
        
      </ul>
    
      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="logar">
        <!-- Formul?rio -->
		<form name="login" method="post" action="minhas_enquetes.php">
		  <div class="form-group">
			<label for="usuario">E-mail</label>
			<input type="text" name="usuario" id="usuario" class="form-control" id="exampleInputEmail1" placeholder="E-mail" />
		  </div>
		  <div class="form-group">
			<label for="senha">Senha</label>
			<input type="password" name="senha" id="senha" class="form-control" id="exampleInputPassword1" placeholder="Senha" />
		  </div> 
		  <input type="hidden" name="enter">
			<script language="javascript">
			$(document).ready( function () {
				var conditions = [];
				conditions['usuario'] = 'email';
				conditions['senha'] = 7;
				$("input[name='button2']").click( function () {
					valid = validateForm(document.login, conditions);
					if (valid) {
						document.login.enter.value = 'ENVIAR';
						document.login.submit();
					}
				});
			});
			</script>
			<input type="button" name="button2" class="btn btn-default" value="ENVIAR" />
		</form>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <a href="dados.php">Cadastrar-se</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="enviar_senha.php">Esqueceu sua senha?</a>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>

<!--Cabe?alho -> Logotipo -> Menu -->
<header class="fundo-head">
<div class="container">
<div class="row">
	<div class="col-md-2">
    
    <!-- Logotipo -->
    <div class="logo"><a href="index.php"><img src="img/logo-web-enquetes.png" alt="Web Enquetes"></a></div>
    </div>
    	
	<!-- /NAVEGA??O/ -->
	<div class="col-md-10">
	  <nav class="navbar navbar-default">
	  <div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		</div>
	
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		  <ul class="nav navbar-nav navbar-right">
		  	<?php
			if (empty($we->usuario)) {
			?>
			<script language="javascript">
			$(document).ready(function () {
				$("#login2").click(function () {
					$("#logar").modal("toggle");
				});
			});
			</script>
			<?php
			}
			?>
			<li class="active"><a id="login2" <?php
			if (empty($we->usuario)) {
				echo "href='#' onclick='$(\"#erro\").remove()'";
			} else echo "href='criar_enquete.php'";
			?>>Criar enquete</a></li>
			<li><a href="premium.php" data-toggle="tooltip" data-placement='bottom' title="Saiba as vantagens de ser um assinante e crie aqui sua assinatura.">Servi&ccedil;os e Assinaturas</a></li>
			<li><a href="why_create_poll.php" data-toggle="tooltip" data-placement='bottom' title="Saiba o poder que uma enquete tem de te auxiliar em decis&otilde;es importantes.">Por que criar enquetes?</a></li>
			<li><a href="divulgar.php" data-toggle="tooltip" data-placement='bottom' title="Saiba aqui formas eficazes de divulgar sua enquete gratuitamente e faz&ecirc;-la ser bem votada.">Obtenha boas respostas<span class="sr-only">(current)</span></a></li>
			<li><a href="no_seu_site.php" data-toggle="tooltip" data-placement='bottom' title="Saiba aqui sobre como divulgar sua enquete no seu site, fazendo-a aparecer nele para ser votada nele.">No seu site</a></li>
		  </ul>
		</div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
  </div>
</div>
</div>
<script language="javascript">
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	//$('[title]').css({'position' : 'relative', 'display': 'inline-block'});   
});
</script>
</header>

<div class="container">
	<div class="row">
    <div class="col-md-12">
		<div id="status">
		<?php
		if (isset($status) && strpos($status, "incorreto") !== FALSE) {
			$status = "O e-mail ou a senha est&atilde;o incorretos.";
			$we->write_status();
		}
		?>
		</div><?php
		if (strpos($_SERVER['REQUEST_URI'], 'enquete.php') === FALSE && strpos($_SERVER['REQUEST_URI'], 'resultados_parciais.php') === FALSE) {
		?>
		<div class="fb-like" data-href="https://www.facebook.com/WebEnquetesEPesquisas/" data-width="100" data-layout="standard" data-action="like" data-size="small" style='margin: 3px;' data-show-faces="true" data-share="false">
		</div>
		<?php } ?>
	</div>
	</div>
</div>
<?php
$we->my_poll();	

?>
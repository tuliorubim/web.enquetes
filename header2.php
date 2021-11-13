<?php
session_start();
include "bd.php";
contagem2 ('contador', true, $_GET['from']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php include "head.php"; ?>
<body> 
 <!-- ?rea de login do usu?rio -->
<div class="fundo-login">
<div class="container">
<div class="row">
<?php
if ($_POST["ir"] === "Ir") {
	echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL=enquetes.php?pc=".encode($_POST["procurar"])."'>";
}
/*include 'php-graph-sdk-5.x/src/Facebook/autoload.php';

use Facebook\Facebook;
 
// Create our Application instance (replace this with your appId and secret).
$fb = new \Facebook\Facebook(array(
  'app_id' => '543474609763817',
  'app_secret' => '540f4fdf594d62fbc4b54655883adae1',
  'default_graph_version' => 'v2.3'
));
$helper = $fb->getCanvasHelper();
$token = NULL;
if (isset($_SESSION['token'])) {
	$token = $_SESSION['token'];
} else $token = $helper->getAccessToken();
if (isset($token)) {
	if (isset($_SESSION['token'])) {
		$fb->setDefaultAccessToken($_SESSION['token']);
	} else {
		$_SESSION['token'] = (string) $token;
		$oac = $fb->getOAuth2Client();
		var_dump($oac);
		$llat = $oac->getLongLivedAccessToken($_SESSION['token']);
		$_SESSION['token'] = (string) $llat;
		$fb->setDefaultAccessToken($_SESSION['token']);
	}
} 

$user = $facebook->get("/me/likes/");
$user = $user->getGraphEdge()->asArray();
var_dump($user); 
/*if ($user) {
  try {
   // $likes = $facebook->api("/me/likes/PAGE_ID");
    if( !empty($likes['data']) )
        echo "I like!";
    else
        echo "not a fan!";
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
 
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl(array(
    'scope' => 'user_likes'
  ));
}*/
?>
<div class="col-md-6">
	<form method="post" name="search_form" style="margin-left:25%; margin-top:10px;">
		<div style="float:left;"><input name="procurar" size='25' class="procurar" type="text" title="Procurar enquete por nome" placeholder="Procurar enquete" ></div>
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
<div class='col-md-3'>
	<div class="fb-like" data-href="https://www.facebook.com/WebEnquetesEPesquisas/" data-width="100" data-layout="standard" data-action="like" data-size="small" style='margin: 3px; position:fixed; z-index:1; top:7px;' data-show-faces="true" data-share="false"></div>
	<script language="javascript">
	$(document).ready( function () {
		try {
			FB.init({
				appId  : '543474609763817',
				status : true, // check login status
				cookie : true, // enable cookies to allow the server to access the session
				version: 'v5.0'
			});
		} catch (e) {
			alert(e.message);
		}
		try {
			FB.getLoginStatus(function(response) {
				if (response['status'] == 'connected') {
					token = response['authResponse']['accessToken'];
					//340029920105070 {access_token: token}
					try {
						FB.api(
							"/me/likes/340029920105070", {access_token: token},
							function (response) {
							  alert(response.data);
							  if (response && !response.error) {
								alert('sss');
							  }
							}
						);
					} catch (e) {alert(e.message);}
				}
			});
		} catch (e) {
			alert(e.message);
		}
		
	});
	</script>
</div>
<div class="col-md-3"> 
<div class="modal-home">
<!-- Small modal > Caixa para inserir email e senha -->
<?php
select("select nome, usuario from cliente where idCliente = $idu", array('nome', 'usuario'));
if ($_SESSION[$idSession] !== NULL && !empty($usuario)) {
	$logged_in = true;
	if (empty($nome)) {
		$nome = substr($usuario, 0, strpos($usuario, '@'));
	}
	echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>Ol&aacute;, $nome <span class='caret'></span></a>";
?>
	<ul class="dropdown-menu" id="menu_cliente" role="menu" aria-labelledby="dLabel">
	  <li><a href="dados.php">Meus Dados</a></li>
	  <li><a href="minhas_enquetes.php">Minhas Enquetes</a></li>
	  <li><a href="criar_enquete.php">Criar Enquete</a></li>
	  <li><a href="index.php?login=off">Sair</a></li>
	</ul>
<?php	
} else {
?>
<a href="#" class="btn btn-primary estilo-modal" id="login" onClick="$('#erro').remove()">LOGIN</a>
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
		<form name="login" method="post">
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
			select("select usuario from cliente where idCliente = $idu", array("email"));
			if (empty($email)) {
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
			if (empty($email)) {
				echo "href='#' onclick='$(\"#erro\").remove()'";
			} else echo "href='criar_enquete.php'";
			?>>Criar enquete</a></li>
			<li><a href="premium.php" data-toggle="tooltip" data-placement='bottom' title="Saiba as vantagens de ser um assinante e crie aqui sua assinatura.">Servi&ccedil;os e Pre&ccedil;os</a></li>
			<li><a href="divulgar.php" data-toggle="tooltip" data-placement='bottom' title="Saiba aqui formas eficazes de divulgar sua enquete gratuitamente.">Divulgue sua enquete<span class="sr-only">(current)</span></a></li>
			<li><a href="no_seu_site.php" data-toggle="tooltip" data-placement='bottom' title="Saiba aqui sobre como divulgar sua enquete no seu site, fazendo-a aparecer nele para ser votada nele.">No seu site</a></li>
			<li><a href="enquetes.php?m=true" data-toggle="tooltip" data-placement='bottom' title="Veja aqui modelos de enquetes bem elaboradas para que voc&ecirc; saiba como elaborar a sua, caso voc&ecirc; n&atilde;o saiba como fazer isso ainda.">Enquetes modelo</a></li>
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
		<div id="status" style="text-align:center;">
		<?php
		if (strpos($status, "incorrect") !== FALSE) {
			$status = "O e-mail ou a senha est&atilde;o incorretos.";
		}
		?>
		</div>
	</div>
	</div>
</div>
<?php
include "bd.php";
$we->contagem2 ('contador', true, $_GET['from']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php include "head.php"; ?>
<body>
<?php
include "header.php"; 
?> 
<!-- /BOX CHAMADA / FORMULARIO -->
<div class="container">
	<div class="row">
    <!-- /CHAMADA/ -->
	<div class="col-md-6">
        <div class="chamada">
        <h1>CONHEÇA SEU P&Uacute;BLICO com enquetes!</h1>
		<br>
        <p>
		<?php
		echo htmlentities("Adquira o CONHECIMENTO que você deseja criando e ", ENT_NOQUOTES, 'UTF-8', true); 
		?>
		<a href='divulgar.php'>divulgando</a>
		<?php		
		 echo htmlentities("enquetes agora mesmo! Comece de maneira fácil e rápida sua enquete no formulário ao lado. Se você ainda não é cadastrado, basta você fornecer seu e-mail e sua senha ao lado e você será automaticamente cadastrado, no ato da elaboração da sua enquete. Neste site, você poderá criar pesquisas de mercado, de opinião, de satisfação e outras pesquisas por meio de questionários que seu público alvo responde ao escolher opções de respostas pré-definidas por você.", ENT_NOQUOTES, 'UTF-8', true); 
		?>
		</p>
        </div>	
	</div>
    
    <!-- /FORMUL�RIO CADASTRO/ -->
    <div class="col-md-4 col-md-offset-2">
    <div class="form-cadastro">
	<?php include "form_enquete.php";?>
    </div>
    </div>
    </div>
</div>

<!-- CHAMADA 2 -->
<div class="bkg-chamada2">
<div class="container">
	<div class="row">
    <div class="col-md-4">
    <div class="panel panel-default">
	  <div class="panel-body">
	    <h2>Sua Enquete no SEU SITE!</h2>
		<img src="img/enquete.png" alt="Resultados Parciais">
		<a href="no_seu_site.php"><p>Agora voc&ecirc; pode criar sua enquete aqui e baixar o c&oacute;digo HTML da mesma para inclu&iacute;-lo no seu site gratuitamente, e os visitantes do seu site a responder&atilde;o.</p></a>	
	  </div>
	</div>
    </div>
    <div class="col-md-4">
    <div class="panel panel-default">
	  <div class="panel-body">
	    <h2>Como conseguir bastantes votos</h2>
		<img src="img/divulgue.png" alt="Resultados Parciais">
		<a href="divulgar.php"><p>Clique aqui e veja formas eficazes de divulgar suas enquetes gratuitamente para faz&ecirc;-las serem satisfatoriamente bem votadas.</p></a>
	  </div>
	</div>
    </div>
    <div class="col-md-4">
    <div class="panel panel-default">
	  <div class="panel-body">
		<h2>Resultados Enviesados</h2>
		<img src="img/resultado.png" alt="Resultados Parciais">
		<a href="resultados.php"><p>Veja aqui como funciona os resultados parciais de uma enquete de acordo com grupos de pessoas que voc&ecirc; estabelece.</p></a>
	  </div>
	</div>
    </div>
    </div>
</div>
</div>

<?php include "latest_polls.php"; ?>

<?php include "categorias.php"; ?>

<?php include "footer.php"; ?>

</body>
</html>

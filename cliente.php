<?php
session_start();
include "bd.php";
$idSession = 'user';
?>
<!DOCTYPE html>
<html lang="pt-br">
<?php include "head.php"; ?>
<body>
<?php include "header.php"; ?> 

<!-- MENU DO USUÁRIO -->
<div class="container">
    <div class="bkg-menu-cliente">
	<div class="row">
    <div class="col-md-8">
    <ul class="nav nav-pills">
	  <li role="presentation" class="active"><a href="#">Meus Dados</a></li>
	  <li role="presentation"><a href="#">Minhas Enquetes</a></li>
	  <li role="presentation"><a href="#">Criar Enquete</a></li>
	  <li role="presentation"><a href="#">Sair</a></li>
	</ul>
    </div>
    </div>
    </div>
    <div class="row">
    <div class="col-md-6">
    <h2>CRIE SUA ENQUETE</h2>
    <form>
		<div class="form-group">
		<label for="exampleInputEmail1"></label>
		<input type="text" class="form-control input-lg" id="exampleInputEmail1" placeholder="Pergunta">
		</div>
		<a href="#"><img src="img/botao-incluir.png" alt="Botão Adicionar" class="btn-add"></a>
		<label for="exampleInputEmail1"></label>
		<input type="text" class="form-control input-lg" id="exampleInputEmail1" placeholder="Op&ccedil;&atilde;o de resposta">
		<label for="exampleInputEmail1"></label>
		<input type="text" class="form-control input-lg" id="exampleInputEmail1" placeholder="Op&ccedil;&atilde;o de resposta">
		<a href="#"><img src="img/botao-incluir.png" alt="Botão Adicionar" class="btn-add-2"></a>
		<p>ADICIONE OUTRAS OP&Ccedil;&Otilde;ES</p>
    </form>
    </div>
    </div>
</div>

<div class="bkg-enquetes-h">
<?php include "latest_polls.php"; ?>
</div>

<?php include "footer.php"; ?>

</html>

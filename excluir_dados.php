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
    <!-- INICIO / AQUI � O ESPA�O AONDE VOC� IRA COLOCAR O CONT�UDO DAS OUTRAS PAGINAS INTERNAS QUE VAI APARECER NA COLUNA ESQUERDA -->
    <h4>Envie-nos sugest&otilde;es, d&uacute;vidas ou reclama&ccedil;&otilde;es</h4>
	<br><br>
    <?php
	$content = "<p>Para apagar os seus dados registrados neste site, voc� deve seguir os seguintes passos</p>
				<p>Passo 1: No Google Chrome, mantendo-se em qualquer p�gina do site da Web Enquetes, v� at� o canto superior direito e clique nos tr�s pontos verticais. Repouse o cursor sobre \"Mais ferramentas\" e selecione \"Ferramentas de desenvolvedor\";</p>
				<p><img src='passo 1 excluir dados.png' width='100%'></p>
				<p>Passo 2. Ap�s a abertura do painel, clique na aba \"Application\". Se n�o vir a op��o, selecione o �cone \">>\" para revelar guias ocultas e ent�o escolha \"Application\";</p>
				<p><img src='passo 2 excluir dados.png' width='100%'></p>
				<p>Passo 3. No menu lateral esquerdo, selecione \"Storage\" e depois \"Clear site data\". Se voc� quer excluir apenas dados espec�ficos, desmarque as op��es relativas �s informa��es que deseja preservar. Em seguida, pressione novamente o bot�o.</p>
				<p><img src='passo 3 excluir dados.png' width='100%'></p>";
	echo $we->html_encode($content);
	?>
    <!-- AQUI � O LIMITE PARA A COLUNA ESQUERDA, O CONTE�DO DEVE ESTAR O �NICIO E AQUI O FIM -->
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
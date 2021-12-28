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
    <h4>Envie-nos sugest&otilde;es, d&uacute;vidas ou reclama&ccedil;&otilde;es</h4>
	<br><br>
    <?php
	$content = "<p>Para apagar os seus dados registrados neste site, você deve seguir os seguintes passos</p>
				<p>Passo 1: No Google Chrome, mantendo-se em qualquer página do site da Web Enquetes, vá até o canto superior direito e clique nos três pontos verticais. Repouse o cursor sobre \"Mais ferramentas\" e selecione \"Ferramentas de desenvolvedor\";</p>
				<p><img src='passo 1 excluir dados.png' width='100%'></p>
				<p>Passo 2. Após a abertura do painel, clique na aba \"Application\". Se não vir a opção, selecione o ícone \">>\" para revelar guias ocultas e então escolha \"Application\";</p>
				<p><img src='passo 2 excluir dados.png' width='100%'></p>
				<p>Passo 3. No menu lateral esquerdo, selecione \"Storage\" e depois \"Clear site data\". Se você quer excluir apenas dados específicos, desmarque as opções relativas às informações que deseja preservar. Em seguida, pressione novamente o botão.</p>
				<p><img src='passo 3 excluir dados.png' width='100%'></p>";
	echo $we->html_encode($content);
	?>
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
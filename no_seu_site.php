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
    <h1>Sua enquete no seu site</h1>
    <p>
    <?php
	echo htmlentities("Você tem um site ou blog e deseja conhecer algo a respeito do seu público alvo? Você pode criar uma pesquisa por enquetes com uma ou mais perguntas de múltipla escolha na Web Enquetes e pode hospedá-la gratuitamente no seu site, sendo tal pesquisa sobre assunto relacionado ao assunto dele. Assim, à medida em que as pessoas vão visitando-o para ver o conteúdo que você aborda nele, elas também vão respondendo a enquete que está hospedada nele, e você, por meio dos resultados parciais, vai obtendo o conhecimento que você quer obter do seu público alvo. É muito simples criar enquetes na Web Enquetes. Você poderá criá-la em qualquer página deste site através do formulário à direita deste texto, tendo você cadastro ou não neste site, pois você poderá criar tal cadastro no ato da elaboração da sua pesquisa. Depois de fornecer as informações iniciais neste formulário, você é direcionado a outra página onde você vai criar as perguntas e opções de resposta da sua enquete.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Após criada a sua enquete, na mesma página onde ela foi completamente criada aparece um menu no canto superior direito cuja segunda opção é \"Baixar HTML desta enquete\". Quando você clica nesta opção, aparece uma caixa de diálogo perguntando se você quer que o texto da enquete que você pretende colar no seu site seja preto ou branco. Depois de escolher a cor que melhor se adapta ao seu site ou blog e depois de confirmá-la, acontece o download do HTML da sua enquete no formato .txt. Depois disso, tudo o que você tem a fazer é abrir o arquivo que você baixou, selecionar e copiar todo o conteúdo do arquivo, que é o HTML da sua enquete e depois colá-lo junto ao HTML do seu site ou blog, tendo você previamente escolhido em que parte do mesmo sua enquete deverá aparecer. Fazendo somente isto, sua enquete estará pronta para ser votada a partir do seu site.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Se você tem um site ou blog na Internet que é visitado regularmente, é vantajoso para você criar um espaço no mesmo para a enquete que você criar na Web Enquetes, pois o seu site irá gerar a visibilidade que sua enquete precisa para que ela receba as respostas que você deseja que sejam feitas nela. Esta é uma forma eficiente de divulgação de sua enquete, podendo ser mais vantajosa do que outras formas de divulgação de enquetes que sugerimos aqui na Web Enquetes, se tão somente o seu site ou blog estiver divulgado para ter a visibilidade que você deseja.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	
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
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
    <h1>Sua enquete no seu site</h1>
    <p>
    <?php
	echo htmlentities("Voc� tem um site ou blog e deseja conhecer algo a respeito do seu p�blico alvo? Voc� pode criar uma pesquisa por enquetes com uma ou mais perguntas de m�ltipla escolha na Web Enquetes e pode hosped�-la gratuitamente no seu site, sendo tal pesquisa sobre assunto relacionado ao assunto dele. Assim, � medida em que as pessoas v�o visitando-o para ver o conte�do que voc� aborda nele, elas tamb�m v�o respondendo a enquete que est� hospedada nele, e voc�, por meio dos resultados parciais, vai obtendo o conhecimento que voc� quer obter do seu p�blico alvo. � muito simples criar enquetes na Web Enquetes. Voc� poder� cri�-la em qualquer p�gina deste site atrav�s do formul�rio � direita deste texto, tendo voc� cadastro ou n�o neste site, pois voc� poder� criar tal cadastro no ato da elabora��o da sua pesquisa. Depois de fornecer as informa��es iniciais neste formul�rio, voc� � direcionado a outra p�gina onde voc� vai criar as perguntas e op��es de resposta da sua enquete.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Ap�s criada a sua enquete, na mesma p�gina onde ela foi completamente criada aparece um menu no canto superior direito cuja segunda op��o � \"Baixar HTML desta enquete\". Quando voc� clica nesta op��o, aparece uma caixa de di�logo perguntando se voc� quer que o texto da enquete que voc� pretende colar no seu site seja preto ou branco. Depois de escolher a cor que melhor se adapta ao seu site ou blog e depois de confirm�-la, acontece o download do HTML da sua enquete no formato .txt. Depois disso, tudo o que voc� tem a fazer � abrir o arquivo que voc� baixou, selecionar e copiar todo o conte�do do arquivo, que � o HTML da sua enquete e depois col�-lo junto ao HTML do seu site ou blog, tendo voc� previamente escolhido em que parte do mesmo sua enquete dever� aparecer. Fazendo somente isto, sua enquete estar� pronta para ser votada a partir do seu site.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Se voc� tem um site ou blog na Internet que � visitado regularmente, � vantajoso para voc� criar um espa�o no mesmo para a enquete que voc� criar na Web Enquetes, pois o seu site ir� gerar a visibilidade que sua enquete precisa para que ela receba as respostas que voc� deseja que sejam feitas nela. Esta � uma forma eficiente de divulga��o de sua enquete, podendo ser mais vantajosa do que outras formas de divulga��o de enquetes que sugerimos aqui na Web Enquetes, se t�o somente o seu site ou blog estiver divulgado para ter a visibilidade que voc� deseja.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	
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
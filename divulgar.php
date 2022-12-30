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
    <h1>Como conseguir bastantes votos</h1>
	<p><span style="color:#990000">ATUALIZADO EM 29/12/2022</span></p>
    <p>
    <?php
	echo htmlentities("Criar uma enquete e simplesmente deix�-la l� sem divulg�-la n�o ir� atrair votos para ela. Temos dado dicas de como divulgar sua enquete e voc� receber respostas das pessoas nela. Temos falado sobre divulgar no Youtube, Facebook e outras redes sociais, mas, com o passar dos anos, os mecanismos anti-spam dessas redes sociais est�o cada vez mais sofisticados, de modo que divulgar sua enquete em p�ginas, posts e v�deos de redes sociais como o Youtube e Facebook n�o est� mais dando resultado. Se voc� n�o � uma pessoa influente, n�o sendo l�der de uma multid�o para lev�-la a responder �s suas enquetes e/ou testes, voc� precisa colar os links delas em v�rios lugares da Internet para que elas sejam bem respondidas, mas infelizmente a cruzada dos grandes sites de redes sociais tem sido implac�veis contra esse tipo de divulga��o.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p><b>NOVIDADE => </b>
    <?php
	echo htmlentities("Hoje j� temos uma alternativa de divulga��o ampla dentro do site da Web Enquetes. J� havedo milhares de enquetes criadas aqui nesta plataforma, voc� pode colar o link da sua enquete como coment�rio de resultados parciais de enquetes da mesma categoria que a sua. Voc� pode colar esse link como coment�rio de qualquer enquete, mas voc� ter� os melhores resultados se o fizer nos resultados parciais de enquetes de mesma categoria que a sua enquete ou teste. Quando voc� posta esse link nos resultados parciais de outras enquetes, ele se transforma de fato em um link que � precedido com os dizeres \"VOTE NA ENQUETE: \" para chamar a aten��o de quem v� seu coment�rio. Assim, pessoas que acham no Google as enquetes onde voc� postou o link da sua eventualmente clicar�o nelas e ver�o o an�ncio da sua enquete nos coment�rios de seus resultados parciais e poder�o ainda clicar na sua enquete e votar nela. Isso far� com que sua enquete seja continuamente votada, at� que voc� encerre a vota��o nela, se for o caso. Estamos aprimorando essa forma de anunciar enquetes na Web Enquetes para que tais an�ncios tenham o m�ximo de impacto poss�vel.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Mas voc� pode fazer outras coisas para divulgar sua enquete que ainda d�o certo at� certo ponto, como postar o link dela no Facebook marcando 100 pessoas no seu post, por exemplo. Uma outra coisa que voc� tamb�m pode fazer � n�o deixar de criar uma introdu��o para a sua enquete, pois as palavras da introdu��o viram palavras chave que o servi�o de busca gratuito do Google utilizar� para fazer sua enquete aparecer nas buscas que as pessoas fazem por assuntos relacinados ao da sua enquete. Saiba ent�o escolher bem as palavras da introdu��o.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Para se ter uma divulga��o ainda maior, voc� poder� adquirir um servi�o n�o gratuito, mas que n�o te consumir� uma quantia de dinheiro t�o significativa. Voc� pode utilizar o servi�o pago de divulga��o do Google, que � o Google Ads. Pode tamb�m usar o servi�o pago de divulga��o no Facebook e/ou tamb�m de outras redes sociais. Esses servi�os oferecem as ferramentas para voc� segmentar o p�blico que ir� ver a sua enquete, de modo que voc� possa fazer com que ela seja vista somente por um p�blico que tenha interesse pelo assunto da mesma. � preciso saber utilizar bem essas ferramentas para que a divulga��o maior dos servi�os de divulga��o pagos aconte�a de fato.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("As formas de divulga��o acima s�o no caso de voc� n�o possuir um site ou blog na Internet. Se voc� tem um site ou blog online que � regularmente visitado, oferecemos o servi�o de exibi��o da sua enquete nele, e as pessoas poder�o votar nela no seu site mesmo, sendo que o voto se processa no site da Web Enquetes. Voc� tamb�m pode divulgar sua enquete da forma como apresentada acima, al�m de coloc�-la no seu site. Saiba mais sobre nosso servi�o de enquete no seu site clicando ", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?><a href="no_seu_site.php">aqui</a>.</p>
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
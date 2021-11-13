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
    <p>
    <?php
	echo htmlentities("Voc� deve divulgar sua enquete nos lugares que reunem pessoas com interesses pelo assunto da mesma, em redes sociais como o Youtube, o Facebook e o WhatsApp. Por exemplo, se voc� faz uma enquete sobre pol�tica nacional, poder� divulgar sua enquete como coment�rio ou respostas de coment�rios feitos em v�rios v�deos do Youtube sobre pol�tica nacional, ou feitos em not�cias sobre pol�tica de p�ginas de not�cias do Facebook ou de outras redes sociais. Se faz uma enquete sobre futebol, poder� divulg�-la como coment�rio ou respostas de coment�rios de v�rios v�deos do Youtue sobre futebol, ou de not�cias do Facebook sobre futebol e outras redes sociais. Quanto ao WhatsApp, este parece ter-se tornado o meio mais din�mico para propaga��o de informa��es online. Se o tema da sua enquete for o mesmo tema de grupos do WhatsApp do qual voc� faz parte, voc� pode divulg�-la neles e obter bons resultados.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Quanto ao Facebook, ele n�o permite que voc� repita uma mesma postagem muitas vezes, por causa de sua pol�tica anti-spam. Se voc� ultrapassar o limite, o Facebook emite uma mensagem dizendo que voc� est� utilizando esse recurso demasiadamente e que voc� poder� sofrer puni��es se continuar a fazer isso. Quando isso acontecer, voc� deve parar a divulga��o ou continuar divulgando com um texto alterado no conte�do do post. Caso voc� opte por parar, depois de alguns dias voc� poder� voltar a divulgar sua enquete maci�amente, at� o Facebook te alertar de novo sobre o uso em excesso desse recurso, e a� voc� p�ra a divulga��o de novo. J� no Youtube, voc� pode divulgar sua enquete sem limites, pois a pol�tica anti-spam deles se processa de uma outra maneira, n�o impedindo que voc� divulgue sua enquete quantas vezes quiser.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Fazendo todas essas coisas, voc� tem boas chances de fazer suas enquetes serem satisfatoriamente bem votadas. Uma outra coisa que voc� tamb�m pode fazer � n�o deixar de criar uma introdu��o para a sua enquete, pois as palavras da introdu��o viram palavras chave que o servi�o de busca gratuito do Google utilizar� para fazer sua enquete aparecer nas buscas que as pessoas fazem por assuntos relacinados ao da sua enquete. Saiba ent�o escolher bem as palavras da introdu��o.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Para se ter uma divulga��o ainda maior, voc� poder� adquirir um servi�o n�o gratuito, mas que n�o te consumir� uma quantia de dinheiro t�o significativa. Voc� pode utilizar o servi�o pago de divulga��o do Google, que � o Google Adwords. Pode tamb�m usar o servi�o pago de divulga��o no Facebook e/ou tamb�m de outras redes sociais. Esses servi�os oferecem as ferramentas para voc� segmentar o p�blico que ir� ver a sua enquete, de modo que voc� possa fazer com que ela seja vista somente por um p�blico que tenha interesse pelo assunto da mesma. � preciso saber utilizar bem essas ferramentas para que a divulga��o maior dos servi�os de divulga��o pagos aconte�a de fato.", ENT_NOQUOTES, 'ISO-8859-1', true); 
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
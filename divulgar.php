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
	<p><span style="color:#990000">ATUALIZADO EM 10/01/2023</span></p>
    <p>
    <?php
	echo htmlentities("Talvez voc� nem precise ler este texto, no caso, por exemplo, de voc� ter seu p�blico alvo e um meio muito simples de divulgar sua enquete ou teste para o mesmo e assim conseguir deles as respostas que voc� deseja. De qualquer forma, queremos apresentar a voc� os meios de que dispomos para te ajudar a divulgar sua enquete para seu p�blico alvo.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p><b>NOVIDADE => </b>
    <?php
	echo htmlentities("Hoje � poss�vel voc� processar as respostas das pessoas do seu p�blico alvo no seu pr�prio celular, por exemplo. Antigamente, s� era poss�vel responder uma �nica vez a uma enquete ou teste num mesmo dispositivo (computador, celular, tablet...) e, sempre que voc� tentasse responder de novo, o que acontecia era que voc� editava sua resposta, impossibilitando a utiliza��o de um celular por exemplo para entrevistar v�rias pessoas e registrar v�rias respostas diferentes atrav�s dele. Agora isso � poss�vel quando voc� est� logado na sua conta da Web Enquetes. Estando voc� logado, voc� visita seu question�rio de enquetes e/ou testes e registra quantas respostas voc� quiser. ", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Talvez voc� seja algu�m que j� tentou utilizar seu celular para entrevistar seu p�blico alvo por meio do seu question�rio da Web Enquetes e tenha se frustrado ao perceber que s� era poss�vel responde-lo uma vez e depois edit�-lo quantas vezes voc� quisesse. Agora voc� pode registrar quantas respostas quiser num mesmo dispositivo, desde que voc� esteja logado na sua conta. Por outro lado, as pessoas que responderem o seu question�rio de enquetes/testes em outro dispositivo s� poder� responde-lo uma �nica vez, editando sua resposta se ele o responder de novo. Isso porque ele n�o vai poder estar logado na sua conta e, nesta condi��o, continua sendo poss�vel registrar somente uma resposta a um question�rio, o que continua visando garantir que n�o haver� respostas m�ltiplas de um �nico usu�rio �s suas enquetes e/ou testes.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Mas se voc� n�o pretende coletar respostas de seu p�blico alvo por meio de entrevistas, e se voc� tem um site no ar que � regularmente visitado e pretende publicar l� seu question�rio, oferecemos o servi�o de exibi��o da sua enquete nele, e as pessoas poder�o votar nela no seu site mesmo, sendo que o voto se processa no site da Web Enquetes. Para isso, voc� precisa baixar o HTML do seu question�rio e col�-lo no seu site. Para saber mais sobre isso, clique ", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?><a href="no_seu_site.php">aqui</a>.</p>
	<p>
    <?php
	echo htmlentities("Agora, se voc� � apenas um curioso que n�o tem p�blico alvo, mas que deseja, por meio de enquetes e/ou testes, saber algo sobre as pessoas de uma forma geral, infelizmente temos que dizer que os mecanismos anti-spam das redes sociais est�o cada vez mais sofisticados, de modo que divulgar sua enquete em p�ginas, posts e v�deos de redes sociais como o Youtube e Facebook n�o est� mais dando resultado. Pensando nisto, estamos desenvolvendo uma forma de divulga��o da sua enquete dentro do pr�prio site da Web Enquetes. Nossa plataforma j� conta com a exist�ncia de milhares de enquetes que podem ser encontradas no Google por meio de palavras-chave. Assim, estamos estudando uma forma de fazer sua enquete aparecer, de alguma forma, nas p�ginas de outras enquetes, � medida que, por exemplo, voc� vai comentando nessas enquetes com o link para o seu question�rio/enquete/teste, para que ele seja visto pelas pessoas que visitam as nossas enquetes por meio de buscas Google.", ENT_NOQUOTES, 'ISO-8859-1', true); 
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
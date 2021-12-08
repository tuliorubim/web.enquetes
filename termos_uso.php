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
    <h1>TERMOS DE USO</h1>
    <p>
    <?php
	echo htmlentities("Ao utilizar os servi�os da Web Enquetes voc� concorda com as seguintes condi��es de uso da nossa plataforma:", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>1)	Aos que respondem enquetes</p>
	<p>
    <?php
	echo htmlentities("1.1)	Quando uma pessoa responde a uma enquete, ela n�o � intimada a concordar com estes termos de uso, como acontece com os criadores de enquetes. Mas, mesmo assim, estes termos se aplicam aos respondedores de enquetes, especialmente no que diz respeito aos resultados parciais delas, e n�o cabe a eles discordarem destes termos em hip�tese alguma.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("1.2)	Se voc� responde a uma enquete e percebe que est� faltando alguma op��o de resposta que deveria estar nela, n�o cabe reclama��o � Web Enquetes quanto a isto. O m�ximo que voc� pode fazer � reclamar com o criador da enquete, o qual pode ser qualquer cliente da Web Enquetes, sabendo, por�m, que este n�o ser� obrigado a alterar sua enquete para colocar nela a op��o de resposta que voc� julga que deveria estar l�, n�o cabendo, portanto, reclama��o � Web Enquetes sobre o criador de tal enquete tamb�m.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("1.3)	Nenhuma reclama��o sobre fraude em enquetes poder� ser feita com informa��es vagas, nem com base em achismos ou com base em uma estranheza quanto aos resultados parciais. Esta dever� ser feita com informa��es detalhadas e suficientes para que a suposta fraude possa ser investigada. Al�m disso, n�o temos prazo definido para concluir quaisquer investiga��es, caso sejam instauradas.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>2)	Aos criadores de enquetes gratuitas</p>
	<p>
    <?php
	echo htmlentities("2.1) Voc� s� poder� excluir sua enquete, perguntas de sua enquete ou op��es de resposta de sua enquete se ela tiver menos de 100 votos. Enquetes com mais de 100 votos n�o podem ter nenhuma de suas partes exclu�das, embora possam ser editadas.", ENT_NOQUOTES, 'ISO-8859-1', true);
	?></p>
	<p>
    <?php
	echo htmlentities("2.2) Voc� pode at� editar sua enquete a qualquer momento, mas se voc� fizer isso com o intuito de perverter os resultados parciais da mesma e algu�m dos que a responderam relatar tal fraude a n�s, fornecendo informa��es suficientes para que possamos abrir uma investiga��o, n�s o faremos e, caso seja identificada a fraude, sua enquete poder� ser alterada para a sua vers�o original ou at� mesmo exclu�da, na pior das hip�teses. Por�m, n�o haver� prazo definido para o t�rmino de quaisquer investiga��es.", ENT_NOQUOTES, 'ISO-8859-1', true);
	?></p>
	<p>
    <?php
	echo htmlentities("2.3) Os resultados parciais de sua enquete ser�o p�blicos na vers�o gratuita do seu uso da Web Enquetes. Al�m disso, na vers�o gratuita, sua enquete poder� ser encontrada nos mecanismos de busca do nosso site e de sites de busca e poder� aparecer entre as enquetes de destaque, se for bem votada. Somente assinantes poder�o esconder os resultados parciais de suas enquetes para eles mesmos e tamb�m torn�-las privada, para, por exemplo, poder divulg�-la somente a pessoas que podem votar nas mesmas e impedir que pessoas fora do grupo de pessoas para quem elas s�o divulgadas votem nela.", ENT_NOQUOTES, 'ISO-8859-1', true);
	?></p>
	<p>
    <?php
	echo htmlentities("2.4) At� onde sabemos, n�o h� nenhum sistema de enquetes online imunes a fraudes. Todavia, n�s contamos com um sistema anti-fraude que n�o permite que uma mesma pessoa vote mais de uma vez na mesma enquete, no qual, se ele tentar votar de novo, ele ir� editar seu voto, apagando o antigo e registrando o novo. Se voc� reclamar conosco dizendo que sua enquete foi burlada, fazendo isso com base apenas em uma mera suspeita, n�o abriremos investiga��o para saber o que pode ter ocorrido, pois n�o temos meios para isso. Assim, ao fazer enquete conosco, voc� concorda que aceita os resultados da mesma ainda que suspeitando que haja fraude, pois tal fraude muito provavelmente ser� apenas uma percep��o sua.", ENT_NOQUOTES, 'ISO-8859-1', true);
	?></p>
	<p>
    <?php
	echo htmlentities("2.5) Ao criar enquetes conosco, voc� estar� autorizando a Web Enquetes a lhe enviar e-mails sobre assuntos relacionados aos nossos servi�os, onde procuraremos falar sobre coisas que possam ser do seu interesse.", ENT_NOQUOTES, 'ISO-8859-1', true);
	?></p>
	<p>3) Coment&aacute;rios em enquetes</p>
	<p>
    <?php
	echo htmlentities("3.1) Ao criar um coment�rio em uma enquete, voc� dever� estar ciente de que o mesmo n�o poder� ser editado e nem mesmo apagado. Estas funcionalidades ainda n�o est�o dispon�veis na Web Enquetes. Portanto, pense bem no que voc� vai comentar.", ENT_NOQUOTES, 'ISO-8859-1', true);
	?></p>
	<p>
    <?php
	echo htmlentities("3.2) Qualquer den�ncia de coment�rios abusivos dever� ser feita por meio do nosso Fale Conosco, que est� no menu inferior. Ao fazer a den�ncia de algo que eventualmente possa te ofender, diga-nos em qual enquete ocorreu o agravo, fornecendo-nos o endere�o da mesma, copiando-o da sua barra de navega��o e colando-o na sua mensagem para n�s. Diga-nos tamb�m quem fez o coment�rio (exemplo: Anonimo98765) e cole tamb�m o coment�rio na mensagem. N�o fa�a den�ncias com informa��es insuficientes.", ENT_NOQUOTES, 'ISO-8859-1', true);
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
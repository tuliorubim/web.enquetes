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
	<?php
	echo $we->html_encode("<p>Aqui � o lugar onde voc� solicita que seu question�rio de enquetes e/ou testes seja publicado de modo que qualquer pessoa do Brasil ou de outro pa�s onde se fale a l�ngua portuguesa possa v�-lo e ser convidado a respond�-lo. Se o seu question�rio � para um grupo restrito de pessoas, como os habitantes de uma cidade, por exemplo, esta N�O � uma forma adequada de se divulgar sua enquete.</p>
	<p>Se, ent�o, a sua enquete � para qualquer pessoa de qualquer lugar, ent�o voc� pode solicitar nesta p�gina que ela seja divulgada amplamente. Como essa divulga��o acontece? Ela acontece na se��o \"Enquetes para todos\", que pode estar na lateral direita ou junto ao rodap� da p�gina atual. Seis enquetes aparecem nessa se��o escolhidas aleatoriamente. Cada vez que se carrega uma nova p�gina, outras seis enquetes, isso �, suas primeiras perguntas, aparecer�o aleatoriamente nessas se��es. Quando se toca com o dedo em cima do ponto de interroga��o dessas perguntas, aparece numa caixa sobreposta abaixo essa mesma pergunta e suas op��es de resposta, o que aumenta as chances do visitante do site responder a tal enquete. E � assim que uma enquete � divulgada amplamente. Todas as enquetes configuradas para terem divulga��o ampla ter�o sua vez para aparecer na se��o \"Enquetes para todos\".</p>
	<p> Por�m, faremos uma an�lise da qualidade de sua enquete, para saber se ela tem qualifica��o suficiente para ter ampla divulga��o. Se for simplesmente imposs�vel saber o que voc� est� querendo saber com sua enquete ou question�rio, enviaremos um e-mail a voc� dizendo que n�o ser� poss�vel divulgar amplamente sua enquete</p>");
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
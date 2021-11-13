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
    <h1>Resultados parciais enviesados</h1>
    <p>
    <?php
	echo htmlentities("O recurso \"Resultados parciais enviesados\" est� dispon�vel apenas para enquetes de mais de uma pergunta, por causa da forma como ele funciona, que ser� explicada a seguir. Quando voc� est� nos resultados parciais de uma enquete de mais de uma pergunta, observe que h� bot�es de sele��o ao lado das op��es de respostas. Quando voc� clica em um desses bot�es, voc� seleciona a op��o de resposta � qual ele est� associado, e automaticamente s� aparecer�o nos resultados parciais os votos das pessoas que votaram na op��o de resposta selecionada. Assim, voc� saber� como as pessoas que votaram nessa op��o selecionada responderam �s outras perguntas. Vamos citar um exemplo para ficar mais claro: voc� cria a seguinte pergunta: \"De que regi�o voc� �?\", e cria as op��es de resposta \"Norte\", \"Nordeste\", \"Centro-Oeste\", \"Sudeste\" e \"Sul\", e depois cria outras perguntas com op��es de resposta. Se voc�, por exemplo, quiser saber os resultados parciais da enquete apenas das pessoas que escolheram a op�o \"Norte\" como resposta � primeira pergunta, voc� vai nos resultados parciais da enquete e clica no bot�o de sele��o ao lado da op��o de resposta \"Norte\", e aparecer� os resultados parciais apenas para os que disseram ser do Norte do Brasil. Assim funcionam os resultados parciais enviesados.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Crie enquetes de v�rias perguntas e teste este recurso, que � importante para voc� tra�ar o perfil de cada grupo de pessoas que respondem � sua enquete, de acordo com o que voc� quer saber delas. Esse servi�o � gratuito, mas oferecemos para assinantes um servi�o de resultados parciais enviesados mais completo. A diferen�a em rela��o aos resultados enviesados mais simples � que na vers�o mais completa voc� pode escolher mais de uma op��o de resposta para saber como as pessoas que as escolheram responderam �s outras perguntas. Assim, no exemplo anterior, voc� pode querer saber como as pessoas do Norte e tamb�m do Centro-oeste ao mesmo tempo responderam �s outras perguntas da enquete. Nesta vers�o completa voc� tamb�m pode selecionar op��es de resposta de perguntas diferentes, para saber como as pessoas que votaram nelas responderam a toda a enquete. Somente assinantes t�m acesso a este e outros recursos extra, e voc� pode criar sua assinatura clicando ", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?><a href="premium.php">aqui.</a></p>
	
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
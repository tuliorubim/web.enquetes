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
    <h1>Quem somos</h1>
    <p>
    <?php
	echo htmlentities("Oferecemos um servi�o onde n�s mesmos elaboramos sua pesquisa. Sabendo que sua pesquisa deve ter boa qualidade para alcan�ar o objetivo que voc� anseia, temos qualifica��o para elaborar seu question�rio de modo que as quest�es que o comp�em sejam precisamente relevantes � sua pesquisa, e de modo que as respostas a tais perguntas possam abranger todas as possiblidades de respostas que uma pessoa poderia dar a elas. Essas respostas podem n�o refletir com exatid�o o que cada pessoa responderia �s perguntas do questin�rio, mas certamente ser�o uma boa aproxima��o de cada caso poss�vel.	
", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Desta maneira, sua enquete ir� te fornecer o conhecimento que voc� deseja adquirir dos seus clientes, funcion�rios e de outros grupos de pessoas por cujas caracter�sticas voc� se interessa. Nossa forma��o em Engenharia e nosso dom�nio sobre o m�todo cient�fico nos permite criar as ferramentas adequadas para a sua pesquisa. Entregamos a voc� o question�rio completo, ap�s o pagamento do servi�o, mas voc� pode fazer altera��es posteriores no mesmo, caso deseje, no formul�rio de edi��o de enquetes, fazendo o login, o qual te direcionar� a este formul�rio, ou, se n�o ocorrer este direcionamento, basta voc� clicar no item \"Minhas Enquetes\", do menu inferior.", ENT_NOQUOTES, 'ISO-8859-1', true); 
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
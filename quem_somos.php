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
    <h1>Quem somos</h1>
    <p>
    <?php
	echo htmlentities("Oferecemos um serviço onde nós mesmos elaboramos sua pesquisa. Sabendo que sua pesquisa deve ter boa qualidade para alcançar o objetivo que você anseia, temos qualificação para elaborar seu questionário de modo que as questões que o compõem sejam precisamente relevantes à sua pesquisa, e de modo que as respostas a tais perguntas possam abranger todas as possiblidades de respostas que uma pessoa poderia dar a elas. Essas respostas podem não refletir com exatidão o que cada pessoa responderia às perguntas do questinário, mas certamente serão uma boa aproximação de cada caso possível.	
", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Desta maneira, sua enquete irá te fornecer o conhecimento que você deseja adquirir dos seus clientes, funcionários e de outros grupos de pessoas por cujas características você se interessa. Nossa formação em Engenharia e nosso domínio sobre o método científico nos permite criar as ferramentas adequadas para a sua pesquisa. Entregamos a você o questionário completo, após o pagamento do serviço, mas você pode fazer alterações posteriores no mesmo, caso deseje, no formulário de edição de enquetes, fazendo o login, o qual te direcionará a este formulário, ou, se não ocorrer este direcionamento, basta você clicar no item \"Minhas Enquetes\", do menu inferior.", ENT_NOQUOTES, 'ISO-8859-1', true); 
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
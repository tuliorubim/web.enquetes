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
    <h1>Resultados parciais enviesados</h1>
    <p>
    <?php
	echo htmlentities("O recurso \"Resultados parciais enviesados\" está disponível apenas para enquetes de mais de uma pergunta, por causa da forma como ele funciona, que será explicada a seguir. Quando você está nos resultados parciais de uma enquete de mais de uma pergunta, observe que há botões de seleção ao lado das opções de respostas. Quando você clica em um desses botões, você seleciona a opção de resposta à qual ele está associado, e automaticamente só aparecerão nos resultados parciais os votos das pessoas que votaram na opção de resposta selecionada. Assim, você saberá como as pessoas que votaram nessa opção selecionada responderam às outras perguntas. Vamos citar um exemplo para ficar mais claro: você cria a seguinte pergunta: \"De que região você é?\", e cria as opções de resposta \"Norte\", \"Nordeste\", \"Centro-Oeste\", \"Sudeste\" e \"Sul\", e depois cria outras perguntas com opções de resposta. Se você, por exemplo, quiser saber os resultados parciais da enquete apenas das pessoas que escolheram a opão \"Norte\" como resposta à primeira pergunta, você vai nos resultados parciais da enquete e clica no botão de seleção ao lado da opção de resposta \"Norte\", e aparecerá os resultados parciais apenas para os que disseram ser do Norte do Brasil. Assim funcionam os resultados parciais enviesados.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Crie enquetes de várias perguntas e teste este recurso, que é importante para você traçar o perfil de cada grupo de pessoas que respondem à sua enquete, de acordo com o que você quer saber delas. Esse serviço é gratuito, mas oferecemos para assinantes um serviço de resultados parciais enviesados mais completo. A diferença em relação aos resultados enviesados mais simples é que na versão mais completa você pode escolher mais de uma opção de resposta para saber como as pessoas que as escolheram responderam às outras perguntas. Assim, no exemplo anterior, você pode querer saber como as pessoas do Norte e também do Centro-oeste ao mesmo tempo responderam às outras perguntas da enquete. Nesta versão completa você também pode selecionar opções de resposta de perguntas diferentes, para saber como as pessoas que votaram nelas responderam a toda a enquete. Somente assinantes têm acesso a este e outros recursos extra, e você pode criar sua assinatura clicando ", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?><a href="premium.php">aqui.</a></p>
	
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
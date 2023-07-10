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
    <h1>Como conseguir bastantes votos</h1>
	<?php
	echo $we->html_encode("<p>Aqui é o lugar onde você solicita que seu questionário de enquetes e/ou testes seja publicado de modo que qualquer pessoa do Brasil ou de outro país onde se fale a língua portuguesa possa vê-lo e ser convidado a respondê-lo. Se o seu questionário é para um grupo restrito de pessoas, como os habitantes de uma cidade, por exemplo, esta NÃO é uma forma adequada de se divulgar sua enquete.</p>
	<p>Se, então, a sua enquete é para qualquer pessoa de qualquer lugar, então você pode solicitar nesta página que ela seja divulgada amplamente. Como essa divulgação acontece? Ela acontece na seção \"Enquetes para todos\", que pode estar na lateral direita ou junto ao rodapé da página atual. Seis enquetes aparecem nessa seção escolhidas aleatoriamente. Cada vez que se carrega uma nova página, outras seis enquetes, isso é, suas primeiras perguntas, aparecerão aleatoriamente nessas seções. Quando se toca com o dedo em cima do ponto de interrogação dessas perguntas, aparece numa caixa sobreposta abaixo essa mesma pergunta e suas opções de resposta, o que aumenta as chances do visitante do site responder a tal enquete. E é assim que uma enquete é divulgada amplamente. Todas as enquetes configuradas para terem divulgação ampla terão sua vez para aparecer na seção \"Enquetes para todos\".</p>
	<p> Porém, faremos uma análise da qualidade de sua enquete, para saber se ela tem qualificação suficiente para ter ampla divulgação. Se for simplesmente impossível saber o que você está querendo saber com sua enquete ou questionário, enviaremos um e-mail a você dizendo que não será possível divulgar amplamente sua enquete</p>");
	?>
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
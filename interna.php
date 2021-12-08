<?php
session_start();
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
    <h1>Divulgue sua enquete</h1>
    <p>
    <?php
	echo htmlentities("Você deve divulgar sua enquete nos lugares que reunem pessoas com interesses pelo assunto da mesma. Por exemplo, se você faz uma enquete sobre política nacional, poderá divulgar sua enquete como comtentário de várias notícias sobre política de páginas de notícias do Facebook ou outra rede social. Se faz uma enquete sobre futebol, poderá divulgá-la como comentário de várias notícias sobre futebol de páginas de notícias do Facebook. Mas cuidado para não criar posts e comentários em excesso divulgando sua enquete, se o Facebook, por exemplo, emitir a mensagem dizendo que você está utilizando-se desse recurso de forma não cuidadosa, páre de divulgar, pois você poderá sofrer sanções da rede social. Apesar disso, você poderá, depois de alguns dias divulgar sua enquete desta maneira de novo, mas toda vez que o Facebook emitir a mensagem acima citada, pare de divulgar assim.", ENT_NOQUOTES, 'ISO-8859-1', true); 
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
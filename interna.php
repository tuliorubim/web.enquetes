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
        
        <!-- INICIO / AQUI � O ESPA�O AONDE VOC� IRA COLOCAR O CONT�UDO DAS OUTRAS PAGINAS INTERNAS QUE VAI APARECER NA COLUNA ESQUERDA -->
    <h1>Divulgue sua enquete</h1>
    <p>
    <?php
	echo htmlentities("Voc� deve divulgar sua enquete nos lugares que reunem pessoas com interesses pelo assunto da mesma. Por exemplo, se voc� faz uma enquete sobre pol�tica nacional, poder� divulgar sua enquete como comtent�rio de v�rias not�cias sobre pol�tica de p�ginas de not�cias do Facebook ou outra rede social. Se faz uma enquete sobre futebol, poder� divulg�-la como coment�rio de v�rias not�cias sobre futebol de p�ginas de not�cias do Facebook. Mas cuidado para n�o criar posts e coment�rios em excesso divulgando sua enquete, se o Facebook, por exemplo, emitir a mensagem dizendo que voc� est� utilizando-se desse recurso de forma n�o cuidadosa, p�re de divulgar, pois voc� poder� sofrer san��es da rede social. Apesar disso, voc� poder�, depois de alguns dias divulgar sua enquete desta maneira de novo, mas toda vez que o Facebook emitir a mensagem acima citada, pare de divulgar assim.", ENT_NOQUOTES, 'ISO-8859-1', true); 
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
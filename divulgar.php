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
    <p>
    <?php
	echo htmlentities("Você deve divulgar sua enquete nos lugares que reunem pessoas com interesses pelo assunto da mesma, em redes sociais como o Youtube, o Facebook e o WhatsApp. Por exemplo, se você faz uma enquete sobre política nacional, poderá divulgar sua enquete como comentário ou respostas de comentários feitos em vários vídeos do Youtube sobre política nacional, ou feitos em notícias sobre política de páginas de notícias do Facebook ou de outras redes sociais. Se faz uma enquete sobre futebol, poderá divulgá-la como comentário ou respostas de comentários de vários vídeos do Youtue sobre futebol, ou de notícias do Facebook sobre futebol e outras redes sociais. Quanto ao WhatsApp, este parece ter-se tornado o meio mais dinâmico para propagação de informações online. Se o tema da sua enquete for o mesmo tema de grupos do WhatsApp do qual você faz parte, você pode divulgá-la neles e obter bons resultados.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Quanto ao Facebook, ele não permite que você repita uma mesma postagem muitas vezes, por causa de sua política anti-spam. Se você ultrapassar o limite, o Facebook emite uma mensagem dizendo que você está utilizando esse recurso demasiadamente e que você poderá sofrer punições se continuar a fazer isso. Quando isso acontecer, você deve parar a divulgação ou continuar divulgando com um texto alterado no conteúdo do post. Caso você opte por parar, depois de alguns dias você poderá voltar a divulgar sua enquete maciçamente, até o Facebook te alertar de novo sobre o uso em excesso desse recurso, e aí você pára a divulgação de novo. Já no Youtube, você pode divulgar sua enquete sem limites, pois a política anti-spam deles se processa de uma outra maneira, não impedindo que você divulgue sua enquete quantas vezes quiser.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Fazendo todas essas coisas, você tem boas chances de fazer suas enquetes serem satisfatoriamente bem votadas. Uma outra coisa que você também pode fazer é não deixar de criar uma introdução para a sua enquete, pois as palavras da introdução viram palavras chave que o serviço de busca gratuito do Google utilizará para fazer sua enquete aparecer nas buscas que as pessoas fazem por assuntos relacinados ao da sua enquete. Saiba então escolher bem as palavras da introdução.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Para se ter uma divulgação ainda maior, você poderá adquirir um serviço não gratuito, mas que não te consumirá uma quantia de dinheiro tão significativa. Você pode utilizar o serviço pago de divulgação do Google, que é o Google Adwords. Pode também usar o serviço pago de divulgação no Facebook e/ou também de outras redes sociais. Esses serviços oferecem as ferramentas para você segmentar o público que irá ver a sua enquete, de modo que você possa fazer com que ela seja vista somente por um público que tenha interesse pelo assunto da mesma. É preciso saber utilizar bem essas ferramentas para que a divulgação maior dos serviços de divulgação pagos aconteça de fato.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("As formas de divulgação acima são no caso de você não possuir um site ou blog na Internet. Se você tem um site ou blog online que é regularmente visitado, oferecemos o serviço de exibição da sua enquete nele, e as pessoas poderão votar nela no seu site mesmo, sendo que o voto se processa no site da Web Enquetes. Você também pode divulgar sua enquete da forma como apresentada acima, além de colocá-la no seu site. Saiba mais sobre nosso serviço de enquete no seu site clicando ", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?><a href="no_seu_site.php">aqui</a>.</p>
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
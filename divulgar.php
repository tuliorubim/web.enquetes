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
	<p><span style="color:#990000">ATUALIZADO EM 29/12/2022</span></p>
    <p>
    <?php
	echo htmlentities("Criar uma enquete e simplesmente deixá-la lá sem divulgá-la não irá atrair votos para ela. Temos dado dicas de como divulgar sua enquete e você receber respostas das pessoas nela. Temos falado sobre divulgar no Youtube, Facebook e outras redes sociais, mas, com o passar dos anos, os mecanismos anti-spam dessas redes sociais estão cada vez mais sofisticados, de modo que divulgar sua enquete em páginas, posts e vídeos de redes sociais como o Youtube e Facebook não está mais dando resultado. Se você não é uma pessoa influente, não sendo líder de uma multidão para levá-la a responder às suas enquetes e/ou testes, você precisa colar os links delas em vários lugares da Internet para que elas sejam bem respondidas, mas infelizmente a cruzada dos grandes sites de redes sociais tem sido implacáveis contra esse tipo de divulgação.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p><b>NOVIDADE => </b>
    <?php
	echo htmlentities("Hoje já temos uma alternativa de divulgação ampla dentro do site da Web Enquetes. Já havedo milhares de enquetes criadas aqui nesta plataforma, você pode colar o link da sua enquete como comentário de resultados parciais de enquetes da mesma categoria que a sua. Você pode colar esse link como comentário de qualquer enquete, mas você terá os melhores resultados se o fizer nos resultados parciais de enquetes de mesma categoria que a sua enquete ou teste. Quando você posta esse link nos resultados parciais de outras enquetes, ele se transforma de fato em um link que é precedido com os dizeres \"VOTE NA ENQUETE: \" para chamar a atenção de quem vê seu comentário. Assim, pessoas que acham no Google as enquetes onde você postou o link da sua eventualmente clicarão nelas e verão o anúncio da sua enquete nos comentários de seus resultados parciais e poderão ainda clicar na sua enquete e votar nela. Isso fará com que sua enquete seja continuamente votada, até que você encerre a votação nela, se for o caso. Estamos aprimorando essa forma de anunciar enquetes na Web Enquetes para que tais anúncios tenham o máximo de impacto possível.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Mas você pode fazer outras coisas para divulgar sua enquete que ainda dão certo até certo ponto, como postar o link dela no Facebook marcando 100 pessoas no seu post, por exemplo. Uma outra coisa que você também pode fazer é não deixar de criar uma introdução para a sua enquete, pois as palavras da introdução viram palavras chave que o serviço de busca gratuito do Google utilizará para fazer sua enquete aparecer nas buscas que as pessoas fazem por assuntos relacinados ao da sua enquete. Saiba então escolher bem as palavras da introdução.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Para se ter uma divulgação ainda maior, você poderá adquirir um serviço não gratuito, mas que não te consumirá uma quantia de dinheiro tão significativa. Você pode utilizar o serviço pago de divulgação do Google, que é o Google Ads. Pode também usar o serviço pago de divulgação no Facebook e/ou também de outras redes sociais. Esses serviços oferecem as ferramentas para você segmentar o público que irá ver a sua enquete, de modo que você possa fazer com que ela seja vista somente por um público que tenha interesse pelo assunto da mesma. É preciso saber utilizar bem essas ferramentas para que a divulgação maior dos serviços de divulgação pagos aconteça de fato.", ENT_NOQUOTES, 'ISO-8859-1', true); 
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
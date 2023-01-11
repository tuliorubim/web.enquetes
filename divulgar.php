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
	<p><span style="color:#990000">ATUALIZADO EM 10/01/2023</span></p>
    <p>
    <?php
	echo htmlentities("Talvez você nem precise ler este texto, no caso, por exemplo, de você ter seu público alvo e um meio muito simples de divulgar sua enquete ou teste para o mesmo e assim conseguir deles as respostas que você deseja. De qualquer forma, queremos apresentar a você os meios de que dispomos para te ajudar a divulgar sua enquete para seu público alvo.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p><b>NOVIDADE => </b>
    <?php
	echo htmlentities("Hoje é possível você processar as respostas das pessoas do seu público alvo no seu próprio celular, por exemplo. Antigamente, só era possível responder uma única vez a uma enquete ou teste num mesmo dispositivo (computador, celular, tablet...) e, sempre que você tentasse responder de novo, o que acontecia era que você editava sua resposta, impossibilitando a utilização de um celular por exemplo para entrevistar várias pessoas e registrar várias respostas diferentes através dele. Agora isso é possível quando você está logado na sua conta da Web Enquetes. Estando você logado, você visita seu questionário de enquetes e/ou testes e registra quantas respostas você quiser. ", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Talvez você seja alguém que já tentou utilizar seu celular para entrevistar seu público alvo por meio do seu questionário da Web Enquetes e tenha se frustrado ao perceber que só era possível responde-lo uma vez e depois editá-lo quantas vezes você quisesse. Agora você pode registrar quantas respostas quiser num mesmo dispositivo, desde que você esteja logado na sua conta. Por outro lado, as pessoas que responderem o seu questionário de enquetes/testes em outro dispositivo só poderá responde-lo uma única vez, editando sua resposta se ele o responder de novo. Isso porque ele não vai poder estar logado na sua conta e, nesta condição, continua sendo possível registrar somente uma resposta a um questionário, o que continua visando garantir que não haverá respostas múltiplas de um único usuário às suas enquetes e/ou testes.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Mas se você não pretende coletar respostas de seu público alvo por meio de entrevistas, e se você tem um site no ar que é regularmente visitado e pretende publicar lá seu questionário, oferecemos o serviço de exibição da sua enquete nele, e as pessoas poderão votar nela no seu site mesmo, sendo que o voto se processa no site da Web Enquetes. Para isso, você precisa baixar o HTML do seu questionário e colá-lo no seu site. Para saber mais sobre isso, clique ", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?><a href="no_seu_site.php">aqui</a>.</p>
	<p>
    <?php
	echo htmlentities("Agora, se você é apenas um curioso que não tem público alvo, mas que deseja, por meio de enquetes e/ou testes, saber algo sobre as pessoas de uma forma geral, infelizmente temos que dizer que os mecanismos anti-spam das redes sociais estão cada vez mais sofisticados, de modo que divulgar sua enquete em páginas, posts e vídeos de redes sociais como o Youtube e Facebook não está mais dando resultado. Pensando nisto, estamos desenvolvendo uma forma de divulgação da sua enquete dentro do próprio site da Web Enquetes. Nossa plataforma já conta com a existência de milhares de enquetes que podem ser encontradas no Google por meio de palavras-chave. Assim, estamos estudando uma forma de fazer sua enquete aparecer, de alguma forma, nas páginas de outras enquetes, à medida que, por exemplo, você vai comentando nessas enquetes com o link para o seu questionário/enquete/teste, para que ele seja visto pelas pessoas que visitam as nossas enquetes por meio de buscas Google.", ENT_NOQUOTES, 'ISO-8859-1', true); 
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
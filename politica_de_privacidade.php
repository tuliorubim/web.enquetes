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
    <h1>POL&Iacute;TICA DE PRIVACIDADE</h1>
	<p>Nossa pol&iacute;tica de privacidade obedecer&aacute; as seguintes diretrizes:</p>
    <p>
    <?php
	echo htmlentities("1)	Os dados que o usuário envia ao site da Web Enquetes trafegam pela internet criptografados, porque usamos o protocolo HTTPS. Isso significa que, quando você faz um login, um cadastro ou cria uma enquete, os dados que você envia ao servidor da Web Enquetes por meio dos formulários do site não seguem o caminho do seu computador ao servidor do jeito que eles são, e, sim, criptografados. Essa é uma medida de segurança muito importante dos sites HTTPS em vez de HTTP, pois qualquer hacker mal intencionado que venha a capturar esses dados enquanto eles trafegam na internet os receberão codificados por criptografia, e, assim, ele não os poderá usar, a menos que ele os consiga decriptografar, o que é algo extremamente difícil de se fazer. Se os dados que você envia por meio do site da Web Enquetes fossem enviados como eles são, como ocorre no protocolo HTTP, os hackers mal intencionados os receberiam e estariam livres para usá-los para benefício próprio e para o seu prejuízo, pois não teriam trabalho nenhum de decriptografia.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("2)	Para pagamento da assinatura pela qual se adquire serviços especiais, usamos o Picpay, que também usa o protocolo seguro HTTPS, o que significa que os seus dados de pagamento, como o número do seu cartão de crédito, trafegarão na internet até o servidor criptografados também. Isso é de extrema importância. Imagina seu número de cartão de crédito ser transferido pela internet sem criptografia e ele ser capturado por um hacker. Ele ficaria livre para comprar usando o número de seu cartão. Dessa forma, pelo Picpay, garantimos a segurança da sua transação de aquisição de assinatura. É importante ressaltar que, para se pagar por meio o Picpay, é preciso ter o aplicativo do mesmo instalado no seu celular, e tal instalação é um processo muito simples.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("3)	Todos os seus dados pessoais que você fornece no momento em que você se cadastra na Web Enquetes estarão visíveis somente para você. Nenhum deles será divulgado e nem vendido por nós a terceiros em hipótese alguma. Assim, por exemplo, seu e-mail será mantido em sigilo, não podendo ser visto por ninguém e nem muito menos sendo usado para envio de spam.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("4)	Na versão gratuita da sua conta Web Enquetes, todas as enquetes que você criar ficarão visíveis para todos, assim como os resultados de suas votações. Além disso, sua enquete poderá ser encontrada pelos mecanismos de pesquisa da Web Enquetes e do Google, e, caso quaisquer de suas enquetes sejam bem votadas, elas poderão ser exibidas entre as seis enquetes de destaque em todas as páginas do site Web Enquetes.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("5)	Caso você adquira a assinatura da Web Enquetes, você tem a opção de esconder os resultados da votação de quaisquer enquetes que você criar, de modo que somente você poderá vê-los. Além disso, você tem a opção de tornar sua enquete privada, de modo que ela não poderá ser encontrada nos mecanismos de pesquisa da Web Enquetes ou do Google, e nem aparecerá entre as enquetes de destaque em qualquer hipótese, caso você queira que sua enquete seja visível somente às pessoas para quem você a divulgar.", ENT_NOQUOTES, 'ISO-8859-1', true); 
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
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
    <h1>POL&Iacute;TICA DE PRIVACIDADE</h1>
	<p>Nossa pol&iacute;tica de privacidade obedecer&aacute; as seguintes diretrizes:</p>
    <p>
    <?php
	echo htmlentities("1)	Os dados que o usu�rio envia ao site da Web Enquetes trafegam pela internet criptografados, porque usamos o protocolo HTTPS. Isso significa que, quando voc� faz um login, um cadastro ou cria uma enquete, os dados que voc� envia ao servidor da Web Enquetes por meio dos formul�rios do site n�o seguem o caminho do seu computador ao servidor do jeito que eles s�o, e, sim, criptografados. Essa � uma medida de seguran�a muito importante dos sites HTTPS em vez de HTTP, pois qualquer hacker mal intencionado que venha a capturar esses dados enquanto eles trafegam na internet os receber�o codificados por criptografia, e, assim, ele n�o os poder� usar, a menos que ele os consiga decriptografar, o que � algo extremamente dif�cil de se fazer. Se os dados que voc� envia por meio do site da Web Enquetes fossem enviados como eles s�o, como ocorre no protocolo HTTP, os hackers mal intencionados os receberiam e estariam livres para us�-los para benef�cio pr�prio e para o seu preju�zo, pois n�o teriam trabalho nenhum de decriptografia.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("2)	Para pagamento da assinatura pela qual se adquire servi�os especiais, usamos o Picpay, que tamb�m usa o protocolo seguro HTTPS, o que significa que os seus dados de pagamento, como o n�mero do seu cart�o de cr�dito, trafegar�o na internet at� o servidor criptografados tamb�m. Isso � de extrema import�ncia. Imagina seu n�mero de cart�o de cr�dito ser transferido pela internet sem criptografia e ele ser capturado por um hacker. Ele ficaria livre para comprar usando o n�mero de seu cart�o. Dessa forma, pelo Picpay, garantimos a seguran�a da sua transa��o de aquisi��o de assinatura. � importante ressaltar que, para se pagar por meio o Picpay, � preciso ter o aplicativo do mesmo instalado no seu celular, e tal instala��o � um processo muito simples.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("3)	Todos os seus dados pessoais que voc� fornece no momento em que voc� se cadastra na Web Enquetes estar�o vis�veis somente para voc�. Nenhum deles ser� divulgado e nem vendido por n�s a terceiros em hip�tese alguma. Assim, por exemplo, seu e-mail ser� mantido em sigilo, n�o podendo ser visto por ningu�m e nem muito menos sendo usado para envio de spam.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("4)	Na vers�o gratuita da sua conta Web Enquetes, todas as enquetes que voc� criar ficar�o vis�veis para todos, assim como os resultados de suas vota��es. Al�m disso, sua enquete poder� ser encontrada pelos mecanismos de pesquisa da Web Enquetes e do Google, e, caso quaisquer de suas enquetes sejam bem votadas, elas poder�o ser exibidas entre as seis enquetes de destaque em todas as p�ginas do site Web Enquetes.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("5)	Caso voc� adquira a assinatura da Web Enquetes, voc� tem a op��o de esconder os resultados da vota��o de quaisquer enquetes que voc� criar, de modo que somente voc� poder� v�-los. Al�m disso, voc� tem a op��o de tornar sua enquete privada, de modo que ela n�o poder� ser encontrada nos mecanismos de pesquisa da Web Enquetes ou do Google, e nem aparecer� entre as enquetes de destaque em qualquer hip�tese, caso voc� queira que sua enquete seja vis�vel somente �s pessoas para quem voc� a divulgar.", ENT_NOQUOTES, 'ISO-8859-1', true); 
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
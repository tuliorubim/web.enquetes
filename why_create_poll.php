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
        
        <!-- INICIO / AQUI � O ESPA�O AONDE VOC� IRA COLOCAR O CONT�UDO DAS OUTRAS PAGINAS INTERNAS QUE VAI APARECER NA COLUNA ESQUERDA -->
    <h1>Por que criar pesquisas por enquetes?</h1>
    <p>
    <?php
	echo htmlentities("Enquetes s�o criadas para se fazer pesquisa de mercado, de opini�o, de satisfa��o, dentre outras. Atrav�s de enquetes, isso �, da forma como as pessoas a respondem, voc� ir� adquirir um conhecimento espec�fico a respeito dessas pessoas, que � seu p�blico alvo, e esse conhecimento lhe ser� �til para tomada de decis�es a respeito desse p�blico alvo. Por exemplo, se voc� tem uma sorveteria, poder� criar uma enquete perguntando aos seus clientes qual sabor de sorvete eles preferem. De acordo com os resultados da enquete, levando em conta a porcentagem de pessoas que escolheram cada sabor de sorvete, voc� poder�, por exemplo, ofertar uma quantidade de cada um desses sabores de acordo com essas porcentagens, para que voc� possa minimizar as faltas e as sobras que ocorrerem no seu estoque de sorvete. Outro exemplo � quando o s�ndico de um condom�nio quer fazer um investimento, mas est� em d�vida de qual op��o de investimento escolher dentre, por exemplo, tr�s op��es. A� ele faz uma enquete com os moradores do condom�nio para que eles decidam qual das tr�s op��es ser� o pr�ximo investimento a ser feito dentre as tr�s op��es. A op��o mais votada fica sendo a escolhida pelo s�ndico.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Os exemplos acima s�o exemplos bem simples. Muito mais se pode fazer por meio das enquetes criadas neste site, as quais podem ter mais de uma pergunta, sendo que cada pergunta pode ter um tamanho razoavelmente grande. As op��es de resposta a perguntas que podem ser criadas neste site tamb�m t�m pouca limita��o de tamanho. Estas vantagens n�o existem em enquetes do Facebook, por exemplo. Veja portanto que enquetes s�o ferramentas de obten��o de conhecimentos que auxiliam em decis�es espec�ficas com o fim de proporcionarem melhorias na administra��o de qualquer coisa. Auxiliam bastante, por exemplo, como pesquisa de mercado para tomada das melhores decis�es em neg�cios comerciais. Portanto, se voc� administra uma organiza��o qualquer, e se h� algo que voc� queira conhecer do seu p�blico alvo, pode criar sua enquete em nosso site a partir do pequeno formul�rio ao lado deste texto. Por�m, qualquer pessoa pode criar enquete sobre qualquer assunto na Web Enquetes, se t�o somente tiver curiosidade de saber algo sobre um p�blico qualquer ou sobre as pessoas em geral.", ENT_NOQUOTES, 'ISO-8859-1', true); 
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

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
    <h1>Por que criar pesquisas por enquetes?</h1>
    <p>
    <?php
	echo htmlentities("Enquetes são criadas para se fazer pesquisa de mercado, de opinião, de satisfação, dentre outras. Através de enquetes, isso é, da forma como as pessoas a respondem, você irá adquirir um conhecimento específico a respeito dessas pessoas, que é seu público alvo, e esse conhecimento lhe será útil para tomada de decisões a respeito desse público alvo. Por exemplo, se você tem uma sorveteria, poderá criar uma enquete perguntando aos seus clientes qual sabor de sorvete eles preferem. De acordo com os resultados da enquete, levando em conta a porcentagem de pessoas que escolheram cada sabor de sorvete, você poderá, por exemplo, ofertar uma quantidade de cada um desses sabores de acordo com essas porcentagens, para que você possa minimizar as faltas e as sobras que ocorrerem no seu estoque de sorvete. Outro exemplo é quando o síndico de um condomínio quer fazer um investimento, mas está em dúvida de qual opção de investimento escolher dentre, por exemplo, três opções. Aí ele faz uma enquete com os moradores do condomínio para que eles decidam qual das três opções será o próximo investimento a ser feito dentre as três opções. A opção mais votada fica sendo a escolhida pelo síndico.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("Os exemplos acima são exemplos bem simples. Muito mais se pode fazer por meio das enquetes criadas neste site, as quais podem ter mais de uma pergunta, sendo que cada pergunta pode ter um tamanho razoavelmente grande. As opções de resposta a perguntas que podem ser criadas neste site também têm pouca limitação de tamanho. Estas vantagens não existem em enquetes do Facebook, por exemplo. Veja portanto que enquetes são ferramentas de obtenção de conhecimentos que auxiliam em decisões específicas com o fim de proporcionarem melhorias na administração de qualquer coisa. Auxiliam bastante, por exemplo, como pesquisa de mercado para tomada das melhores decisões em negócios comerciais. Portanto, se você administra uma organização qualquer, e se há algo que você queira conhecer do seu público alvo, pode criar sua enquete em nosso site a partir do pequeno formulário ao lado deste texto. Porém, qualquer pessoa pode criar enquete sobre qualquer assunto na Web Enquetes, se tão somente tiver curiosidade de saber algo sobre um público qualquer ou sobre as pessoas em geral.", ENT_NOQUOTES, 'ISO-8859-1', true); 
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

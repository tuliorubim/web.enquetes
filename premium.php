<?php
include "bd.php";
require_once "funcoes/funcoesDesign.php";
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
    <h1>Nossos Servi&ccedil;os</h1>
	<p><b><font color="red">
	<?php
	echo htmlentities("ATENÇÃO: esta página será editada, pois agora todos os serviços da \"Assinatura paga\" abaixo estão disponívels para TODOS, exceto o de excluir anúcios Google. Nossos serviços não são gratuitos, mas o pagamento se dá por meio de doações voluntárias. Avalie sua experiência com nossa plataforma e faça-nos uma doação segundo o valor que você acha que nossos serviços valem. Nossa chave pix para doações é 27981170014 (número de celular brasileiro).", ENT_NOQUOTES, 'ISO-8859-1', true);
	?>
	</font> <!--Informe-nos <a href="https://www.facebook.com/WebEnquetesEPesquisas/">aqui</a> sobre a aquisi&ccedil;&atilde;o da sua assinatura e o seu e-mail de cadastro neste site para que possamos fazer sua assinatura entrar em vigor assim que identificarmos o pagamento.--></b>
	</p>
	<?php
	require_once "funcoes/funcoesDesign.php";
	class Premium extends DesignFunctions {
		public function our_services() {
			$header = array();
			$header[0] = "<center><p valign='middle'>Item</p></center>";
			$header[1] = "<center><p>Plano b&aacute;sico (gratuito)</p></center>";
			$header[2] = "<center><p>Assinatura <a href='bonus_mensais.php'>gr&aacute;tis</a>. Assinatura <a href='assinar.php'>paga.</a></center>";
			$args = array();
			$args[0][0] = "Respostas ilimitadas por m&ecirc;s";
			$args[1][0] = "Um voto por pessoa<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Este site cont&eacute;m um sistema autom&aacute;tico eficiente que impede que uma pessoa responda mais de uma vez a uma mesma enquete. Quando algu&eacute;m vota de novo, seu voto &eacute; editado, e o novo voto substitui o anterior.'></span>";
			$args[2][0] = "Desativar e reativar enquete<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Voc&ecirc; pode desativar e reativar sua enquete a qualquer momento. A enquete desativada e seus resultados parciais s&atilde;o vis&iacute;veis somente para voc&ecirc;. Este recurso pode ser usado para se estabelecer data de t&eacute;rmino para sua enquete.'></span>";
			$args[3][0] = "Suporte ao cliente<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='O suporte ao cliente ocorrer&aacute; nos dias de semana &agrave; noite e nos fins de semana de dia e de noite. Nunca de madrugada. E ser&aacute; feito por e-mail ou por meio da p&aacute;gina do Facebook cujo link est&aacute; no rodap&eacute; desta p&aacute;gina.'></span>";
			$args[4][0] = "Exibi&ccedil;&atilde;o de enquetes modelo<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='O menu principal deste site cont&eacute;m o item Enquetes Modelo, onde voc&ecirc; ter&aacute; acesso a enquetes bem elaboradas para te dar uma luz sobre como criar uma enquete bem feita, caso voc&ecirc; n&atilde;o saiba como fazer isso.'></span>";
			$args[5][0] = "Destaques do momento<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Se voc&ecirc; conseguir divulgar bem sua enquete, ela poder&aacute; aparecer entre as enquetes que s&atilde;o destaque do momento, as quais s&atilde;o exibidas em todas as p&aacute;ginas deste site. Assim, sua enquete poder&aacute; receber ainda mais respostas por estar sendo exibida como estando entre as de destaque do momento.'></span>";
			$args[6][0] = "Esconder resultados parciais<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Esta op&ccedil;&atilde;o est&aacute; dispon&iacute;vel apenas para assinantes. Em muitas situa&ccedil;&otilde;es, &eacute; interessante para o criador da enquete esconder o conhecimento revelado nos resultados da mesma, como por exemplo no caso de n&atilde;o se querer que concorrentes tenham acesso a eles.'></span>";
			$args[7][0] = "Enquete privada<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='O criador de uma enquete pode querer que apenas um grupo restrito de pessoas tenha acesso a ela, como por exemplo no caso em que uma enquete &eacute; direcionada a moradores de um condom&iacute;nio, n&atilde;o podendo pessoas que n&atilde;o residem nele respond&ecirc;-la. Assim, o criador da enquete a torna privada, para que ela n&atilde;o apare&ccedil;a entre as enquetes destaque do momento, em resultados de busca neste site e para que nem possam ser achadas no Google, e assim ela a divulga somenta para seu p&uacute;blico alvo. Op&ccedil;&atilde;o dispon&iacute;vel somente para assinantes.'></span>";
			$args[8][0] = "Poder excluir enquetes<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Somente assinantes podem excluir suas enquetes. Se sua assinatura terminar e voc&ecirc; n&atilde;o tiver exclu&iacute;do suas enquetes cujos resultados parciais voc&ecirc; possa ter escondido, tais enquetes n&atilde; poder&atilde;o ser exclu&iacute;s e seus resultados parciais voltar&atilde;o a ser exibidos publicamente at&eacute; que voc&ecirc; renove sua assinatura.'></span>";
			$args[9][0] = "Exibir seu an&uacute;ncio<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Somente assinantes podem exibir um an&uacute;cio seu em suas enquetes. Seu an&uacute;ncio dever&aacute; ser uma imagem que voc&ecirc; cadastra ao atualizar seus dados ou ao criar uma enquete.'></span>";
			$args[10][0] = "Baixar resultados da enquete<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Somente assinantes podem baixar os resultados parciais ou finais de sua enquete. Os resultados s&atilde;o baixados no formato PDF e podem ser baixados tamb&eacute;m neste formato resultados por grupos de pessoas.'></span>";
			$args[11][0] = "Excluir nossos an&uacute;ncios<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Na assinatura gratuita, os nossos an&uacute;ncios n&atilde;o s&atilde;o exclu&iacute;dos. Isso acontece somente na assinatura paga, e de acordo com o per&iacute;odo de pagamento escolhido.'></span>";
			$args[12][0] = "Enquete no seu site<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' data-html='true' title='Voc&ecirc; pode hospedar sua enquete da Web Enquetes no seu site, baixando o HTML da mesma e colando-a em p&aacute;ginas do seu site.'></span>";
			$args[13][0] = "Resultados enviesados<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='right' title='Quando uma enquete tem mais de uma pergunta, &eacute; poss&iacute;vel aplicar os resultados parciais enviesados, escolhendo-se uma das op&ccedil;&otilde;es de resposta a qualquer pergunta nos resultados parciais da enquete e vendo como as pessoas que escolheram tal resposta responderam &agrave;s outras perguntas da enquete.'></span>";
			for ($i = 0; $i < 12; $i++) {
				if ($i < 6) {
					$args[$i][1] = "<span class='glyphicon glyphicon-ok' style='color:#00CC00'></span>";
				} else $args[$i][1] = "<span class='glyphicon glyphicon-remove' style='color:#CC0000'></span>";
				if ($i < 11) {
					$args[$i][2] = "<span class='glyphicon glyphicon-ok' style='color:#00CC00'></span>";
				} else $args[$i][2] = "De acordo com o per&iacute;odo de pagamento. <a href='assinar.php'>Saiba mais</a>";
			}
			$args[12][1] = "Padr&atilde;o<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='left' title='No plano b&aacute;sico, a enquete a ser hospedada no seu site, caso voc&ecirc; tenha um, aparecer&aacute; com todas as perguntas de uma vez, caso ela tenha mais de uma pergunta, o que tomar&aacute; um espa&ccedil;o consider&aacute;vel da p&aacute;gina do site onde ela aparece. Al&eacute;m disso, quando uma pessoa responde a essa enquete no seu site, ela &eacute; redirecionada para fora dele, indo aos resultados parciais da mesma no site da Web Enquetes. A marca da Web Enquetes tamb&eacute;m &eacute; exibida junto com a enquete hospedada.'></span>";
			$args[12][2] = "Sob medida<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='left' title='Como assinante, quando voc&ecirc; cria uma enquete de mais de uma pergunta, a vers&atilde;o dela hospedada no seu site exibir&aacute; uma pergunta de cada vez, ocupando pouco espa&ccedil;o nele. Al&eacute;m disso, quando algu&eacute;m termina de responder &agrave; sua enquete no seu site, ela permanece nele, e n&atilde;o h&Aacute; redirecionamento para a Web Enquetes. A marca da Web Enquetes n&atilde;o aparece com a enquete hospedada.'></span>";
			$args[13][1] = "B&aacute;sico<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='left' title='Em enquetes de mais de uma pergunta, voc&ecirc; s&oacute; pode escolher apenas uma das respostas a qualquer pergunta para saber como as pessoas que escolheram tal resposta responderam &agrave;s outras perguntas.'></span>";
			$args[13][2] = "Avan&ccedil;ado<span class='glyphicon glyphicon-question-sign qf' data-toggle='tooltip' data-placement='left' title='Em enquetes de mais de uma pergunta, nos resultados parciais, voc&ecirc; pode escolher mais de uma resposta a quaisquer perguntas para ver como as essoas que escolheram tais respostas selecionadas responderam &agrave;s outras perguntas.'></span>";
			$this->write_table($args, array("header" => $header));
		}
	}
	$design = new Premium();
	$design->our_services();
	?>
	<script language="javascript">
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();
		//$('[title]').css({'position' : 'relative', 'display': 'inline-block'});   
	});
	</script>
	
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
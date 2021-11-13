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
    <h1>TERMOS DE USO</h1>
    <p>
    <?php
	echo htmlentities("Ao utilizar os serviços da Web Enquetes você concorda com as seguintes condições de uso da nossa plataforma:", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>1)	Aos que respondem enquetes</p>
	<p>
    <?php
	echo htmlentities("1.1)	Quando uma pessoa responde a uma enquete, ela não é intimada a concordar com estes termos de uso, como acontece com os criadores de enquetes. Mas, mesmo assim, estes termos se aplicam aos respondedores de enquetes, especialmente no que diz respeito aos resultados parciais delas, e não cabe a eles discordarem destes termos em hipótese alguma.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("1.2)	Se você responde a uma enquete e percebe que está faltando alguma opção de resposta que deveria estar nela, não cabe reclamação à Web Enquetes quanto a isto. O máximo que você pode fazer é reclamar com o criador da enquete, o qual pode ser qualquer cliente da Web Enquetes, sabendo, porém, que este não será obrigado a alterar sua enquete para colocar nela a opção de resposta que você julga que deveria estar lá, não cabendo, portanto, reclamação à Web Enquetes sobre o criador de tal enquete também.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>
    <?php
	echo htmlentities("1.3)	Nenhuma reclamação sobre fraude em enquetes poderá ser feita com informações vagas, nem com base em achismos ou com base em uma estranheza quanto aos resultados parciais. Esta deverá ser feita com informações detalhadas e suficientes para que a suposta fraude possa ser investigada. Além disso, não temos prazo definido para concluir quaisquer investigações, caso sejam instauradas.", ENT_NOQUOTES, 'ISO-8859-1', true); 
	?></p>
	<p>2)	Aos criadores de enquetes gratuitas</p>
	<p>
    <?php
	echo htmlentities("2.1) Você só poderá excluir sua enquete, perguntas de sua enquete ou opções de resposta de sua enquete se ela tiver menos de 100 votos. Enquetes com mais de 100 votos não podem ter nenhuma de suas partes excluídas, embora possam ser editadas.", ENT_NOQUOTES, 'ISO-8859-1', true);
	?></p>
	<p>
    <?php
	echo htmlentities("2.2) Você pode até editar sua enquete a qualquer momento, mas se você fizer isso com o intuito de perverter os resultados parciais da mesma e alguém dos que a responderam relatar tal fraude a nós, fornecendo informações suficientes para que possamos abrir uma investigação, nós o faremos e, caso seja identificada a fraude, sua enquete poderá ser alterada para a sua versão original ou até mesmo excluída, na pior das hipóteses. Porém, não haverá prazo definido para o término de quaisquer investigações.", ENT_NOQUOTES, 'ISO-8859-1', true);
	?></p>
	<p>
    <?php
	echo htmlentities("2.3) Os resultados parciais de sua enquete serão públicos na versão gratuita do seu uso da Web Enquetes. Além disso, na versão gratuita, sua enquete poderá ser encontrada nos mecanismos de busca do nosso site e de sites de busca e poderá aparecer entre as enquetes de destaque, se for bem votada. Somente assinantes poderão esconder os resultados parciais de suas enquetes para eles mesmos e também torná-las privada, para, por exemplo, poder divulgá-la somente a pessoas que podem votar nas mesmas e impedir que pessoas fora do grupo de pessoas para quem elas são divulgadas votem nela.", ENT_NOQUOTES, 'ISO-8859-1', true);
	?></p>
	<p>
    <?php
	echo htmlentities("2.4) Até onde sabemos, não há nenhum sistema de enquetes online imunes a fraudes. Todavia, nós contamos com um sistema anti-fraude que não permite que uma mesma pessoa vote mais de uma vez na mesma enquete, no qual, se ele tentar votar de novo, ele irá editar seu voto, apagando o antigo e registrando o novo. Se você reclamar conosco dizendo que sua enquete foi burlada, fazendo isso com base apenas em uma mera suspeita, não abriremos investigação para saber o que pode ter ocorrido, pois não temos meios para isso. Assim, ao fazer enquete conosco, você concorda que aceita os resultados da mesma ainda que suspeitando que haja fraude, pois tal fraude muito provavelmente será apenas uma percepção sua.", ENT_NOQUOTES, 'ISO-8859-1', true);
	?></p>
	<p>
    <?php
	echo htmlentities("2.5) Ao criar enquetes conosco, você estará autorizando a Web Enquetes a lhe enviar e-mails sobre assuntos relacionados aos nossos serviços, onde procuraremos falar sobre coisas que possam ser do seu interesse.", ENT_NOQUOTES, 'ISO-8859-1', true);
	?></p>
	<p>3) Coment&aacute;rios em enquetes</p>
	<p>
    <?php
	echo htmlentities("3.1) Ao criar um comentário em uma enquete, você deverá estar ciente de que o mesmo não poderá ser editado e nem mesmo apagado. Estas funcionalidades ainda não estão disponíveis na Web Enquetes. Portanto, pense bem no que você vai comentar.", ENT_NOQUOTES, 'ISO-8859-1', true);
	?></p>
	<p>
    <?php
	echo htmlentities("3.2) Qualquer denúncia de comentários abusivos deverá ser feita por meio do nosso Fale Conosco, que está no menu inferior. Ao fazer a denúncia de algo que eventualmente possa te ofender, diga-nos em qual enquete ocorreu o agravo, fornecendo-nos o endereço da mesma, copiando-o da sua barra de navegação e colando-o na sua mensagem para nós. Diga-nos também quem fez o comentário (exemplo: Anonimo98765) e cole também o comentário na mensagem. Não faça denúncias com informações insuficientes.", ENT_NOQUOTES, 'ISO-8859-1', true);
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
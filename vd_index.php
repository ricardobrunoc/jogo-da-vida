<?php
	include "vd_checa.php";
	include "vd_cabecalho.php";
	include "vd_topo.php";
	include "vd_conexao.php";
	$nome = $_SESSION["nome_vida"];
?>

<?php
	
	$ativo = false;
	//verifica se ja sexiste um jogo ativo para esse usuário
	// if(isset($_SESSION["id_jogada"])){
	// 	$ativo = true;
	// }

	//
	$select = "
	select * from 
	jogada J
		left join partida P on P.idPartida = J.partida_id
		left join jogador G on G.idJogador = J.jogador_id
		left join carro C on C.idCarro = J.carro_id
	where J.jogador_id = ".$_SESSION['id_vida']." and P.estadoPartida != 2
	";
	$query_all = mysqli_query($con,$select);
	if(mysqli_num_rows($query_all) > 0){
    	$dados = mysqli_fetch_array($query_all,MYSQLI_ASSOC);
    	$ativo = true;
    	$_SESSION["id_jogada"] = $dados['idJogada'];
	}

	// var_dump($_SESSION);

?>


<!-- !PAGE CONTENT! -->
<div class="w3-main  w3-white" style="margin-left:300px;margin-top:43px;">
	<div class="w3-panel w3-center w3-container ">
		<p>Olá, <b><?php echo $nome; ?></b></p>
		<?php if($ativo == false): ?>
			<a href="vd_iniciar.php?origem=new" class="w3-button w3-green w3-center">
				<i class="fa fa-plus"></i> Iniciar um novo jogo
			</a>
			<br/>
			<br/>
			<a href="vd_ingressar.php" class="w3-button w3-orange w3-center">
				<i class="fa fa-chess-pawn"></i> Ingressar em um jogo existente
			</a>
			<br/>
			<br/>
		<?php else: ?>
			<div class="w3-panel w3-pale-green w3-border w3-center" style="padding: 10px"><b> Você possui uma partida em andamento </b></div>

			<a href="vd_aguarde.php" class="w3-button w3-blue w3-center">
				<i class="fa fa-undo-alt"></i> Retomar partida
			</a>
			<br/>
			<br/>
		<?php endif; ?>
		<a href="logout.php" class="w3-button w3-red w3-center">
			<i class="fa fa-power-off"></i> Sair
		</a>
		<br/>
		<br/>
	</div>
	<br/>
</div>

</body>
</html>
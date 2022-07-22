<?php
	include "vd_checa.php";
	include "vd_cabecalho.php";
	include "vd_topo.php";
	include "vd_conexao.php";

	$nome = $_SESSION["nome_vida"];
	$idjogador = $_SESSION["id_vida"];
	$idjogada = $_SESSION["id_jogada"];
	$idPartida = $_SESSION['idPartida'];
    $codigoPartida = $_SESSION['codigoPartida'];
    $corCarro = $_SESSION['corCarro'];
    $corw3 = $_SESSION['corCarro2'];

    $jogador = $nome." (".$corCarro.")";

    //buscar os dados dos vencedores
    $select_joga = "select * from jogada J left join jogador G on G.idJogador = J.jogador_id where J.partida_id = '".$idPartida."' order by ranking asc";
	$query_joga = mysqli_query($con,$select_joga);

?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<div class="w3-row-padding w3-white">
		<center>
			<br/>
			<i class='w3-large fa fa-trophy'></i> <span class='w3-large'> Fim de jogo</span>
		</center>
	</div>
	<form class="w3-container w3-white w3-center" method="POST">
		<p>
			<div class="w3-row-padding">

				<?php while ($user = mysqli_fetch_array($query_joga,MYSQLI_ASSOC)): ?>

					<?php if($user['ranking'] == 1): ?>
						<div class="w3-row w3-button w3-yellow " style="width: 100%; text-align: center;">
							<i class="fa fa-trophy"></i> 1º Lugar<br/>
							<b><?php echo $user['nomeJogador']; ?></b><br/>
							R$ <?php echo dinheiro($user['dinheiroJogada']); ?>
						</div>
						<br/>
						<br/>
					<?php endif; ?>

					<?php if($user['ranking'] == 2): ?>
						<div class="w3-row w3-button w3-gray " style="width: 100%; text-align: center;">
							<i class="fa fa-medal"></i> 2º Lugar<br/>
							<b><?php echo $user['nomeJogador']; ?></b><br/>
							R$ <?php echo dinheiro($user['dinheiroJogada']); ?>
						</div>
						<br/>
						<br/>
					<?php endif; ?>

					<?php if($user['ranking'] == 3): ?>
						<div class="w3-row w3-button w3-brown " style="width: 100%; text-align: center;">
							<i class="fa fa-award"></i> 3º Lugar<br/>
							<b><?php echo $user['nomeJogador']; ?></b><br/>
							R$ <?php echo dinheiro($user['dinheiroJogada']); ?>
						</div>
						<br/>
						<br/>
					<?php endif; ?>

					<?php if($user['ranking'] > 3 && $user['ranking'] < 9): ?>
						<div class="w3-row w3-button w3-lime " style="width: 100%; text-align: center;">
							</i> <?php echo $user['ranking']; ?>º Lugar<br/>
							<b><?php echo $user['nomeJogador']; ?></b><br/>
							R$ <?php echo dinheiro($user['dinheiroJogada']); ?>
						</div>
						<br/>
						<br/>
					<?php endif; ?>

					<?php if($user['ranking'] == 9): ?>
						<div class="w3-row w3-button w3-light-gray " style="width: 100%; text-align: center;">
							</i> Falência<br/>
							<b><?php echo $user['nomeJogador']; ?></b><br/>
							-
						</div>
						<br/>
						<br/>
					<?php endif; ?>




				<?php endwhile; ?>
				<div class="w3-row">
					<!-- <input type="submit" class="w3-btn w3-green w3-center" value="Confirmar" name="save">&nbsp; -->
					<a href="logout.php" class="w3-btn w3-red w3-center" >Sair</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>
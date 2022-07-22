<?php
	include "vd_checa.php";
	include "vd_cabecalho.php";
	include "vd_topo.php";
	include "vd_conexao.php";

	$nome = $_SESSION["nome_vida"];
	$idjogador = $_SESSION["id_vida"];
	$idjogada = $_SESSION["id_jogada"];
    $codigoPartida = $_SESSION['codigoPartida'];
    $corCarro = $_SESSION['corCarro'];
    $corw3 = $_SESSION['corCarro2'];



    //Ver o o que o jogador possui
    $select = "
	select * from 
	jogada J
		left join partida P on P.idPartida = J.partida_id
		left join profissao T on T.idProfissao = J.profissao_id
	where idJogada = ".$idjogada."
	";
	$query_all = mysqli_query($con,$select);
    $dados = mysqli_fetch_array($query_all,MYSQLI_ASSOC);

    if($dados['estadoPartida'] == 0)
    	echo "<script language='javascript'>location.href='vd_aguarde.php'</script>";

    if($dados['estadoPartida'] == 2)
    	echo "<script language='javascript'>location.href='vd_fim.php'</script>";

    $posses = array();
    if($dados['seguroCarro'] == 1) $posses[] = "Seguro do carro";
    if($dados['seguroVida'] == 1) $posses[] = "Seguro de vida";
    if($dados['seguroCasa'] == 1) $posses[] = "Seguro do casa";
    if($dados['acoesJogada'] == 1) $posses[] = "Ações na bolsa";

?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<div class="w3-row-padding w3-white">
		<p>
			Jogador: <b><?php echo $nome; ?></b><br/>
			Código do Jogo: <b><?php echo $codigoPartida; ?></b><br/>
			Carro: <b><?php echo $corCarro; ?></b><br/>
			<?php
				if($dados['juizo'] != 3){
					echo "Você possui: ";
					if(count($posses) > 0){
						echo implode(', ', $posses)." e ";
					}
					echo $dados['filhosJogada']." filho";
					if($dados['filhosJogada'] != 1)
						echo "s";
				}else{
					echo "Você não possui nada";
				}
			?>
		</p>
	</div>

	
	<!-- <div id="load_tweets">ss </div> -->


	<form class="w3-container w3-white w3-center" action="vd_aguarde.php" method="POST">
		<?php if($dados['juizo'] == 3): ?>
			<div class="w3-panel w3-red w3-animate-opacity w3-center">
				<p>
				<b>Você faliu!</b>
				</p>
			</div>
		<?php endif; ?>
		<?php if($dados['juizo'] == 2): ?>
			<div class="w3-panel w3-green w3-animate-opacity w3-center">
				<p>
				<b>Você se tornou um magnata e venceu o jogo</b>
				</p>
			</div>
		<?php endif; ?>
		<div id="load_historico" class="w3-panel w3-animate-opacity w3-center">
			<div class="w3-panel w3-blue w3-animate-opacity w3-center">
				<p>
				<b>Seu saldo</b><br/>
				R$ <?php echo dinheiro($dados['dinheiroJogada']); ?>
				</p>
			</div>

			<div class="w3-panel w3-khaki w3-border w3-animate-opacity " style="font-size: 12px; text-align: left;">
				<br/>
				Carregando...<br/>
				<br/>
			</div>

		</div>
		<p>
			<div class="w3-row-padding w3-left" style="width: 100%">
				<?php if($dados['juizo'] != 3 && $dados['juizo'] != 2): ?>
					<div class="w3-row">
						<a href="vd_transacao.php?acao=receber" class="w3-button w3-green " style="width: 100%; text-align: left;">
							<i class="fa fa-sign-in-alt"></i> Receber do banco
						</a>
					</div>
					<div class="w3-row">
						<a href="vd_transacao.php?acao=pagar" class="w3-button w3-blue " style="width: 100%; text-align: left;">
							<i class="fa fa-sign-out-alt"></i> Pagar ao banco
						</a>
					</div>
					<div class="w3-row">
						<a href="vd_transacao.php?acao=transferir" class="w3-button w3-purple " style="width: 100%; text-align: left;">
							<i class="fa fa-exchange-alt"></i> Transferir para outro jogador
						</a>
					</div>
					<?php if($dados['juizo'] == 0): ?>
						<?php if($dados['profissao_id'] == ''): ?>
							<div class="w3-row">
								<a href="vd_profissao.php" class="w3-button w3-lime " style="width: 100%; text-align: left;">
									<i class="fa fa-briefcase"></i> Definir profissão
								</a>
							</div>
						<?php else: ?>
							<div class="w3-row">
								<a href="vd_salario.php" class="w3-button w3-lime " style="width: 100%; text-align: left;">
									<i class="fa fa-briefcase"></i> Receber salário <?php echo "(".$dados['nomeProfissao'].")"; ?>
								</a>
							</div>
						<?php endif; ?>
					<?php endif; ?>
					<?php if($dados['juizo'] == 1): ?>
						<?php if($dados['seguroVida'] == 1 || $dados['acoesJogada'] == 1): ?>
							<div class="w3-row">
								<a href="vd_vender.php" class="w3-button w3-brown " style="width: 100%; text-align: left;">
									<i class="fa fa-chart-line"></i> Vender seguros e ações
								</a>
							</div>
						<?php endif; ?>
					<?php else: ?>
						<?php if(count($posses) != 4): ?>
							<div class="w3-row">
								<a href="vd_seguros.php" class="w3-button w3-brown " style="width: 100%; text-align: left;">
									<i class="fa fa-chart-line"></i> Comprar seguros e ações
								</a>
							</div>
						<?php endif; ?>
					<?php endif; ?>
					<?php if($dados['juizo'] == 0): ?>
						<div class="w3-row">
							<a href="vd_filhos.php" class="w3-button w3-deep-orange " style="width: 100%; text-align: left;">
								<i class="fa fa-baby"></i> Ter filhos
							</a>
						</div>
					<?php endif; ?>
					<?php if(count($posses) > 0): ?>
						<div class="w3-row">
							<a href="vd_perda.php" class="w3-button w3-red " style="width: 100%; text-align: left;">
								<i class="fa fa-ban"></i> Perder seguro
							</a>
						</div>
					<?php endif; ?>

					<?php if($dados['juizo'] == 0): ?>
						<div class="w3-row">
							<a href="vd_juizo.php" class="w3-button w3-black " style="width: 100%; text-align: left;">
								<i class="fa fa-gavel"></i> Dia do juízo
							</a>
						</div>
					<?php else: ?>
						<div class="w3-row">
							<a href="vd_encerrar.php" class="w3-button w3-black " style="width: 100%; text-align: left;">
								<i class="fa fa-stop"></i> Encerrar partida
							</a>
						</div>
					<?php endif; ?>
				<?php else: ?>
					<div class="w3-row">
						<a href="vd_encerrar.php" class="w3-button w3-black " style="width: 100%; text-align: left;">
							<i class="fa fa-stop"></i> Encerrar partida
						</a>
					</div>
				<?php endif; ?>
			</div>
		</p>
	</form>
	<br/>
</div>

</body>
</html>

<script type="text/javascript">
	var auto_refresh = setInterval(
	function ()
	{
		$('#load_historico').load('vd_historico.php').fadeIn("slow");
	}, 1000); // refresh every 10000 milliseconds
	</script>
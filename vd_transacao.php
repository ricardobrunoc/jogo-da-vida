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


    $partida_sel = "select * from partida where idPartida = ".$idPartida;
	$query_sel = mysqli_query($con,$partida_sel);
    $dados_sel = mysqli_fetch_array($query_sel,MYSQLI_ASSOC);

    if($dados_sel['estadoPartida'] == 0)
    	echo "<script language='javascript'>location.href='vd_aguarde.php'</script>";

    if($dados_sel['estadoPartida'] == 2)
    	echo "<script language='javascript'>location.href='vd_fim.php'</script>";


	if(isset($_GET['acao'])){
		if($_GET['acao'] == 'receber') $acao = 1;
		if($_GET['acao'] == 'pagar') $acao = 2;
		if($_GET['acao'] == 'transferir') $acao = 3;
	}

	$jogador = $nome." (".$corCarro.")";

	if(isset($_POST['save'])){
		$valor = $_POST['valor'];
		$acao = $_POST['acao'];
		$validacao = true;


		if($valor < 0){
			$validacao = false;
			$msg_error = "Valor não pode ser negativo";
		}

		$valor = limpaNumero($valor);
		$valor = $valor * 1000;

		//busca quanto a pessoa tem
		$select = "select dinheiroJogada from jogada J where idJogada = ".$idjogada."";
		$query_all = mysqli_query($con,$select);
    	$dados = mysqli_fetch_array($query_all,MYSQLI_ASSOC);
    	$dinheiro = $dados['dinheiroJogada'];

        $up_jogada = array();

		switch ($acao) {
			case 1:
				//apenas faz a soma
        		$up_jogada['dinheiroJogada'] = $dinheiro + $valor;
        		$origem = NULL;
        		$destino = $idjogada;
        		$texto = $nome." RECEBEU R$ ".dinheiro($valor)." DO BANCO";
			break;
			case 2: 
				//verifica se o jogador tem saldo pra tal
				if($dinheiro >= $valor){
					$up_jogada['dinheiroJogada'] = $dinheiro - $valor;

					$destino = NULL;
        			$origem = $idjogada;

        			$texto = $nome." PAGOU R$ ".dinheiro($valor)." AO BANCO";

				}else{
					$validacao = false;
					$msg_error = "Este jogador não tem saldo suficiente para tal";
				}
			break;
			case 3: 
				//verifica se o jogador tem saldo pra tal
				if($dinheiro > $valor){
					$up_jogada['dinheiroJogada'] = $dinheiro - $valor;

					//deposita na conta do jogador de destino
					$destino = $_POST['destino'];
					$select_destino = "select J.dinheiroJogada, G.nomeJogador from jogada J left join jogador G on G.idJogador=J.jogador_id where J.idJogada = ".$destino."";
					$query_destino = mysqli_query($con,$select_destino);
			    	$dados_destino = mysqli_fetch_array($query_destino,MYSQLI_ASSOC);
			    	$dinheiro_destino = $dados_destino['dinheiroJogada'];
					$up_destino = array();
			    	$up_destino['dinheiroJogada'] = $dinheiro_destino + $valor;
			    	$update_destino = "update jogada set ".montaUpdate($up_destino)." where idJogada = ".$destino;
			    	mysqli_query($con,$update_destino);

			    	$destino = $destino;
        			$origem = $idjogada;

        			$texto = $nome." PAGOU R$ ".dinheiro($valor)." PARA ".$dados_destino['nomeJogador'];

				}else{
					$validacao = false;
					$msg_error = "Este jogador não tem saldo suficiente para tal";
				}


			break;
		}

		if($validacao == true){

	        $update = "update jogada set ".montaUpdate($up_jogada)." where idJogada = ".$idjogada;

	        if(mysqli_query($con,$update)){
	            historico($con, $idPartida, $origem, $destino, $texto, $valor);
	            echo "<script language='javascript'>location.href='vd_jogo.php'</script>";
	        }else{
	            $msg_error = "Erro ao cadastrar";
	        }
		}
    }

    if($acao == 3){
    	$select = "
		select C.w3Cor, C.corCarro, G.nomeJogador, J.idJogada from 
		jogada J
			left join partida P on P.idPartida = J.partida_id
			left join jogador G on G.idJogador = J.jogador_id
			left join carro C on C.idCarro = J.carro_id
		where P.codigoPartida = '".$codigoPartida."' and J.idJogada <> ".$idjogada."
		";
		$query_all = mysqli_query($con,$select);
    }

?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<div class="w3-row-padding w3-white">
		<center>
			<br/>
			<?php
				switch ($acao) {
					case 1: echo "<i class='w3-large fa fa-sign-in-alt'></i> <span class='w3-large'> Receber do banco</span>"; break;
					case 2: echo "<i class='w3-large fa fa-sign-out-alt'></i> <span class='w3-large'> Pagar ao banco</span>"; break;
					case 3: echo "<i class='w3-large fa fa-exchange-alt'></i> <span class='w3-large'> Transferir dinheiro</span>"; break;
				}
			?>
		</center>
		<p>
			<?php 
				switch ($acao) {
					case 1: echo "Origem: <b>Banco</b><br/> Destino: <b>".$jogador."</b><br/>"; break;
					case 2: echo "Origem: <b>".$jogador."</b><br/> Destino: <b>Banco</b><br/>"; break;
					case 3: echo "Origem: <b>".$jogador."</b>"; break;
				}
			?>
		</p>
	</div>
	<form class="w3-container w3-white w3-center" action="" method="POST">
		<input type="hidden" name="acao" value="<?php echo $acao; ?>">
		<?php include "mensagem.php"; ?>
		<p>
			<div class="w3-row-padding">
				<?php if($acao == 3): ?>
					<div class="w3-row">
						<label class="w3-text-teal"><b>Selecione o jogador de destino</b></label>
						<select class="w3-select" name="destino" required >
							<?php
								while ($dados = mysqli_fetch_array($query_all,MYSQLI_ASSOC)) {
									echo "<option class='w3-select' value='".$dados['idJogada']."' >".$dados['nomeJogador']." (".$dados['corCarro'].")</option>";

								}
							?>
						</select>
					</div>
					<br/>
				<?php endif; ?>

				<div class="w3-row">
					<label class="w3-text-teal"><b>Informe o valor</b></label>
					<table class='w3-table'  width="100%" >
						<tr>
							<td width="50%"><input class="w3-input" name="valor" type="number" required style="text-align: right; vertical-align: middle; font-size: 20px;" placeholder="XX" autofocus></td>
							<td width="50%" style="width: 50%; text-align: left; vertical-align: middle; font-size: 20px;">.000,00</td>
						</tr>
						
					</table>
					
				</div>
				
				<br/>
				<div class="w3-row">
					<input type="submit" class="w3-btn w3-green w3-center" value="OK" name="save">&nbsp;
					<a href="vd_jogo.php" class="w3-btn w3-red w3-center" >Voltar</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>
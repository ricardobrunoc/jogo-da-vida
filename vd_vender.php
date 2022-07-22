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

    $partida_sel = "select * from partida where idPartida = ".$idPartida;
	$query_sel = mysqli_query($con,$partida_sel);
    $dados_sel = mysqli_fetch_array($query_sel,MYSQLI_ASSOC);

    if($dados_sel['estadoPartida'] == 0)
    	echo "<script language='javascript'>location.href='vd_aguarde.php'</script>";

    if($dados_sel['estadoPartida'] == 2)
    	echo "<script language='javascript'>location.href='vd_fim.php'</script>";

    //Ver o o que o jogador possui
    $select = "select * from jogada J where idJogada = ".$idjogada;
	$query_all = mysqli_query($con,$select);
    $dados = mysqli_fetch_array($query_all,MYSQLI_ASSOC);
    $dinheiro = $dados['dinheiroJogada'];

    if($dados['juizo'] != 1 && ($dados['seguroVida'] == 0 && $dados['acoesJogada'] == 0)){
    	echo "<script language='javascript'>location.href='vd_jogo.php'</script>";
    }

    if(isset($_POST['vender'])){
    	$marca = 0;
		$origem = NULL;
		$destino = $idjogada;

    	if(isset($_POST['marcaVida'])){
    		$marca++;
    		$up_jogada = array();
			$up_jogada['seguroVida'] = '0';
    		$up_jogada['dinheiroJogada'] = $dinheiro + 8000;
			$update = "update jogada set ".montaUpdate($up_jogada)." where idJogada = ".$idjogada;
			if(mysqli_query($con,$update)){
    			
    			$texto = $nome." VENDEU O SEGURO DE VIDA POR R$ ".dinheiro(8000);
	            historico($con, $idPartida, $origem, $destino, $texto, 8000);
	        }
    	}

    	if(isset($_POST['marcaAcoes'])){
    		$marca++;
    		$up_jogada = array();
			$up_jogada['acoesJogada'] = '0';
    		$up_jogada['dinheiroJogada'] = $dinheiro + 120000;
			$update = "update jogada set ".montaUpdate($up_jogada)." where idJogada = ".$idjogada;
			if(mysqli_query($con,$update)){
    			$texto = $nome." VENDEU SUAS AÇÕES POR R$ ".dinheiro(120000);
	            historico($con, $idPartida, $origem, $destino, $texto, 120000);
	        }
    	}

    	if($marca == 0){
    		$msg_error = "Você não selecionou nada";
    	}else{
    		echo "<script language='javascript'>location.href='vd_jogo.php'</script>";
    	}
    }


?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<div class="w3-row-padding w3-white">
		<center>
			<br/>
			<i class='w3-large fa fa-chart-line'></i> <span class='w3-large'> Vender seguros e ações</span>
		</center>
		<p>
			<?php echo "Jogador: <b>".$jogador."</b>"; ?><br/>
			Agora que você é um milionário você pode vender seu seguro de vida e suas ações. Selecione os itens que deseja vender e clique em "Vender"
		</p>
	</div>
	<form class="w3-container w3-white w3-center" action="" method="POST">
		<?php include "mensagem.php"; ?>
		<p>
			<div class="w3-row-padding">
				
				<div class="w3-row">
					<label class="w3-text-teal"><b>Itens</b></label>
					<br>
					<?php

						if($dados['seguroVida'] == 1){
							echo "<input class='w3-checkbox'  type='checkbox' name='marcaVida' > Seguro de Vida (R$ 8.000,00)<br/>";
						}
						if($dados['acoesJogada'] == 1){
							echo "<input class='w3-checkbox'  type='checkbox' name='marcaAcoes' > Ações (R$ 120.000,00)<br/>";
						}
					?>
				</div>
				<br/>
				<div class="w3-row">
					<input type="submit" class="w3-btn w3-green w3-center" value="Vender" name="vender">&nbsp;
					<a href="vd_jogo.php" class="w3-btn w3-red w3-center" >Voltar</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>
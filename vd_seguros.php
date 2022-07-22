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

    $coisas = array(
    	'A' => array('desc' => "Seguro de casa", 'valor' => 10000),
    	'B' => array('desc' => "Seguro de vida", 'valor' => 10000),
    	'C' => array('desc' => "Seguro de carro", 'valor' => 10000),
    	'D' => array('desc' => "Ações na bolsa", 'valor' => 50000),
    );

    //Ver o o que o jogador possui
    $select = "select * from jogada J where idJogada = ".$idjogada;
	$query_all = mysqli_query($con,$select);
    $dados = mysqli_fetch_array($query_all,MYSQLI_ASSOC);
    if($dados['seguroCarro'] == 1) unset($coisas["C"]);
    if($dados['seguroVida'] == 1) unset($coisas["B"]);
    if($dados['seguroCasa'] == 1) unset($coisas["A"]);
    if($dados['acoesJogada'] == 1) unset($coisas["D"]);

    if(count($coisas) == 0)
    	echo "<script language='javascript'>location.href='vd_jogo.php'</script>";

    
    if(isset($_POST['confirma'])){
    	$coisa = $_POST['seguro'];
    	$validacao = true;

    	//busca quanto a pessoa tem
		$select = "select dinheiroJogada from jogada J where idJogada = ".$idjogada."";
		$query_all = mysqli_query($con,$select);
    	$dados_atuais = mysqli_fetch_array($query_all,MYSQLI_ASSOC);
    	$dinheiro = $dados_atuais['dinheiroJogada'];

    	$valor = $coisas[$coisa]['valor'];
    	
    	$up_jogada = array();

    	//verifica se o jogador tem saldo pra tal
		if($dinheiro > $valor){
			$up_jogada['dinheiroJogada'] = $dinheiro - $valor;

			$destino = NULL;
			$origem = $idjogada;

			$texto = $nome." COMPROU ".mb_strtoupper($coisas[$coisa]['desc'])." POR R$ ".dinheiro($valor);

		}else{
			$validacao = false;
			$msg_error = "Este jogador não tem saldo suficiente para tal";
		}

		switch ($coisa) {
			case 'A': $up_jogada['seguroCasa'] = 1; break;
			case 'B': $up_jogada['seguroVida'] = 1; break;
			case 'C': $up_jogada['seguroCarro'] = 1; break;
			case 'D': $up_jogada['acoesJogada'] = 1; break;
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



?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<div class="w3-row-padding w3-white">
		<center>
			<br/>
			<i class='w3-large fa fa-chart-line'></i> <span class='w3-large'> Comprar seguros e ações</span>
		</center>
		<p>
			<?php echo "Jogador: <b>".$jogador."</b>"; ?>
		</p>
	</div>
	<form class="w3-container w3-white w3-center" action="" method="POST">
		<p>
			<div class="w3-row-padding">
				
				<div class="w3-row">
					<label class="w3-text-teal"><b>Selecione o que você deseja comprar</b></label>
					<select class="w3-select" name="seguro" required>
						<option></option>
						<?php
							foreach ($coisas as $key => $value) {
								echo "<option class='w3-select' value='".$key."' >".$value['desc'].": R$ ".dinheiro($value['valor'])."</option>";
							}
						?>
					</select>
				</div>
				<br/>
				<div class="w3-row">
					<input type="submit" class="w3-btn w3-green w3-center" value="Confirmar compra" name="confirma">&nbsp;
					<a href="vd_jogo.php" class="w3-btn w3-red w3-center" >Voltar</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>
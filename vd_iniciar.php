<?php
	include "vd_checa.php";
	include "vd_cabecalho.php";
	include "vd_topo.php";
	include "vd_conexao.php";
	$nome = $_SESSION["nome_vida"];
	$idjogador = $_SESSION["id_vida"];
?>

<?php

	//Seleciona os carros
	$select_carros = "select * from carro order by corCarro asc";

	$linhacod = "";
	$novo = true;

	if(isset($_GET['origem'])){
		if($_GET['origem'] == 'entrar'){
			$idpartida = $_GET['idpartida'];
			$select_partida = "select * from partida where idPartida = '".$idpartida."'";
	        $query_partida = mysqli_query($con,$select_partida);
        	$partida = mysqli_fetch_array($query_partida);
        	$linhacod = "Código do Jogo: <b>".$partida['codigoPartida']."</b>";
        	$novo = false;

        	//busca os carros existentes dessa partida
        	$outros_carros = array();
        	$select_outros = "select carro_id as carro from jogada where partida_id = ".$idpartida;
        	$query_outros = mysqli_query($con,$select_outros);
        	while ($outros = mysqli_fetch_array($query_outros)) {
        		$outros_carros[] = $outros['carro'];
        	}
        	if(count($outros_carros) >= 6){
        		//não cabe mais - fazer
        	}
        	$select_carros = "select * from carro where idCarro not in (".implode(',', $outros_carros).") order by corCarro asc";

		}
	}

	$query_carros = mysqli_query($con, $select_carros);

	//gera o codigo
	

	if(isset($_POST['criar'])){
        $dados = $_POST;
        $validacao = true;

        if($novo == true){
	        do{
		        //Cria a partida
		        $cod =  generatorCodigoRand(6, true, false, true);

		        $new_partida = array();
		        $new_partida['codigoPartida'] = $cod;
		        $new_partida['host_id'] = $idjogador;

		        $insert = "insert into partida ".montaInsert($new_partida);
		        if(mysqli_query($con,$insert)){
		            $idpartida = mysqli_insert_id($con);
		            $flag = true;
		        }else{
		        	$flag = false;
		        }
	        }while($flag = false);
        }


        $new_jogada = array();
        $new_jogada['filhosJogada'] = 0;
        $new_jogada['seguroVida'] = 0;
        $new_jogada['acoesJogada'] = 0;
        $new_jogada['seguroCasa'] = 0;
        $new_jogada['juizo'] = 0;
        $new_jogada['jogador_id'] = $idjogador;
        $new_jogada['partida_id'] = $idpartida;
        $new_jogada['carro_id'] = $dados['corCarro'];

        if($dados['seguroCarro'] == 'S'){
        	$new_jogada['dinheiroJogada'] = 9000;
        	$new_jogada['seguroCarro'] = 1;
        }else{
        	$new_jogada['dinheiroJogada'] = 10000;
        	$new_jogada['seguroCarro'] = 0;
        }

        $insert = "insert into jogada ".montaInsert($new_jogada);
        if(mysqli_query($con,$insert)){
            $idjogada = mysqli_insert_id($con);

            $_SESSION["id_jogada"] = $idjogada;
            

        }
        //die();
        echo "<script language='javascript'>location.href='vd_aguarde.php'</script>";
    }

?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<div class="w3-row-padding w3-white">
		<p>
			Jogador: <b><?php echo $nome; ?></b><br/>
			<?php echo $linhacod; ?>
		</p>
	</div>
	<form class="w3-container w3-white w3-center" action="" method="POST">
		<p>
			<div class="w3-row-padding">
				<div class="w3-row">
					<label class="w3-text-teal"><b>Selecione o carro</b></label>
					<select class="w3-select" name="corCarro">
						<?php
							while ($carro = mysqli_fetch_array($query_carros)) {
								echo "<option class='w3-select' value='".$carro['idCarro']."' >".$carro['corCarro']."</option>";
							}
						?>
					</select>
				</div>
				<br/>
				<div class="w3-row">
					<label class="w3-text-teal"><b>Deseja seguro do carro?</b></label>
					<br>
					<!-- (O seguro do carro tem um custo de R$ 1.000) -->
					<input class="w3-radio"  type="radio" name="seguroCarro" value='S' >
					<label>Sim</label>&nbsp;
					<input class="w3-radio"  type="radio" name="seguroCarro" value='N' >
					<label>Não</label>
				</div>
				<br/>
				<div class="w3-row">
					<input type="submit" class="w3-btn w3-green w3-center" value="Continuar" name="criar">&nbsp;
					<a href="vd_index.php" class="w3-btn w3-red w3-center" >Voltar</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>
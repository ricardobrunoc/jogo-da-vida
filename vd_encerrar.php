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

    if(isset($_POST['confirmar'])){
    	$opcao = $_POST['encerrar'];
    	$validacao = true;

    	if($opcao == 2){
    		echo "<script language='javascript'>location.href='vd_jogo.php'</script>";
    	}else{

    		include "codigo_encerramento.php";
    	}
    }



?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<div class="w3-row-padding w3-white">
		<center>
			<br/>
			<i class='w3-large fa fa-stop'></i> <span class='w3-large'> Encerrar o jogo</span>
		</center>
		<p>
			<?php echo "Jogador: <b>".$jogador."</b>"; ?><br/>
		</p>
	</div>
	<form class="w3-container w3-white w3-center" action="" method="POST">
		<?php include "mensagem.php"; ?>
		<p>
			<div class="w3-row-padding">
				
				<div class="w3-row">
					<label class="w3-text-teal"><b>Você tem certeza que deseja encerrar o jogo? </b></label>
					<br>
					<!-- (O seguro do carro tem um custo de R$ 1.000) -->
					<input class="w3-radio"  type="radio" name="encerrar" value='1'  >
					<label>Sim</label>&nbsp;<br/>
					<input class="w3-radio"  type="radio" name="encerrar" value='2' >
					<label>Não</label>
					<br/>
					<br/>
				<br/>
				<div class="w3-row">
					<input type="submit" class="w3-btn w3-green w3-center" value="Confirmar" name="confirmar">&nbsp;
					<a href="vd_jogo.php" class="w3-btn w3-red w3-center" >Voltar</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>
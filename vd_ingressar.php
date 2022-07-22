<?php
	include "vd_checa.php";
	include "vd_cabecalho.php";
	include "vd_topo.php";
	include "vd_conexao.php";
	$nome = $_SESSION["nome_vida"];
	$idjogador = $_SESSION["id_vida"];
?>

<?php
	
	if(isset($_POST['ir'])){
        $dados = $_POST;
        $cod = $_POST['codigo'];

        //verifica se o codigo existe
        $select = "select * from partida where codigoPartida = '".$cod."' and estadoPartida <> 2";
        $query = mysqli_query($con,$select);
        if(mysqli_num_rows($query) == 0){
            $validacao = false;
            $msg_error = "Este código não existe ou essa partida ja se encerrou";
        }else{

        	$partida = mysqli_fetch_array($query);
	        echo "<script language='javascript'>location.href='vd_iniciar.php?origem=entrar&idpartida=".$partida['idPartida']."'</script>";
        }
    }


?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<form class="w3-container w3-white w3-center" action="" method="POST">
	<?php include "mensagem.php"; ?>
		<p>
			<div class="w3-row-padding">
				<div class="w3-row">
					<label class="w3-text-teal"><b>Informe o código do jogo</b></label>
					<input class="w3-input w3-center" name="codigo" type="text" style="text-decoration: uppercase;" required>
				</div>
				
				<br/>
				<div class="w3-row">
					<input type="submit" class="w3-btn w3-green w3-center" value="Entrar" name="ir">&nbsp;
					<a href="vd_index.php" class="w3-btn w3-red w3-center" >Voltar</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>
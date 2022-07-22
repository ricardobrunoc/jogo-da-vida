<?php
	session_start();
	//include "vd_checa.php";
	include "vd_conexao.php";
	include "vd_cabecalho.php";
	include "vd_topo.php";

		//se existe um login ativo então não pode fazer login
	if(isset($_SESSION["acesso_vida"]))
    {
        if($_SESSION["acesso_vida"] == true)
        {
            echo "<script language='javascript'>location.href='vd_index.php'</script>";
        }
    }

?>

<?php
	
	//Seleciona os users
	$select = "select * from jogador order by userJogador asc";
	$query = mysqli_query($con, $select);

	if(isset($_POST['entrar'])){
        $dados = $_POST;

        if($dados['idJogador'] != ''){

	        //verifica se o usuario existe
	        $select_jogador = "select * from jogador G where idJogador = '".$dados['idJogador']."'";
	        $query_jogador = mysqli_query($con,$select_jogador);
	        $jogador = mysqli_fetch_array($query_jogador);

	        //Valida user
	        $_SESSION["acesso_vida"] = true;
	        $_SESSION["nome_vida"] = $jogador['nomeJogador'];
	        $_SESSION["id_vida"] = $jogador['idJogador'];

	        echo "<script language='javascript'>location.href='vd_index.php'</script>";
        }else{
        	$msg_error = "Você deve selecionar um usuário";
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
					<label class="w3-text-teal"><b>Selecione seu usuário</b></label>
					<select class="w3-select" name="idJogador" required>
						<option></option>
						<?php
							while ($user = mysqli_fetch_array($query)) {
								echo "<option class='w3-select' value='".$user['idJogador']."' >".$user['userJogador']."</option>";
							}
						?>
					</select>
				</div>
				
				<br/>
				<div class="w3-row">
					<input type="submit" class="w3-btn w3-green w3-center" value="Entrar" name="entrar">&nbsp;
					<a href="vd_welcome.php" class="w3-btn w3-red w3-center" >Voltar</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>
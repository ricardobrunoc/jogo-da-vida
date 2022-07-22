<?php
	session_start();
	//include "vd_checa.php";
	include "vd_cabecalho.php";
	include "vd_topo.php";
	include "vd_conexao.php";

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

	$user = array('nome' => '', 'usuario' => '');

	if(isset($_POST['save'])){
        $dados = $_POST;
        $validacao = true;
       

        //verifica se o usuario existe
        $select = "select * from jogador G where userJogador = '".$dados['usuario']."'";
        $query = mysqli_query($con,$select);
        if(mysqli_num_rows($query) > 0){
            $validacao = false;
            $msg_error = "Nome de usuário já existente!";
        }

        if($validacao == true){
            
            $new_pessoa = array();
            $new_pessoa['nomeJogador'] = mb_strtoupper(addslashes($dados['nome']));
            $new_pessoa['userJogador'] = mb_strtolower($dados['usuario']);

            $insert = "insert into jogador ".montaInsert($new_pessoa);
            if(mysqli_query($con,$insert)){
                $id = mysqli_insert_id($con);

                //Valida user
                $_SESSION["acesso_vida"] = true;
                $_SESSION["nome_vida"] = $new_pessoa['nomeJogador'];
                $_SESSION["id_vida"] = $id;

                echo "<script language='javascript'>location.href='vd_index.php?acao=cadastrado'</script>";
            }else{
                $msg_error = "Erro ao cadastrar";
            }
        }
        // die();
    }



?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">
	<form class="w3-container w3-white w3-center" action="" method="POST">
	<?php include "mensagem.php"; ?>
		<p>
			<div class="w3-row-padding">
				<div class="w3-row">
					<label class="w3-text-teal"><b>Nome</b></label>
					<input class="w3-input" name="nome" type="text" value="<?php echo $user["nome"]; ?>" required>
				</div>
				<br/>
				<div class="w3-row">
					<label class="w3-text-teal"><b>Usuário</b></label>
					<input class="w3-input" name="usuario" type="text" value="<?php echo $user["usuario"]; ?>" required >
				</div>
				<br/>
				<div class="w3-row">
					<input type="submit" class="w3-btn w3-green w3-center" value="Cadastrar" name="save">&nbsp;
					<a href="vd_welcome.php" class="w3-btn w3-red w3-center" >Voltar</a>
				</div>
			</div>

		</p>
	</form>
	<br/>
</div>

</body>
</html>
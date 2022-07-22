<?php session_start(); ?>
<?php
	// include "vd_checa.php";
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


<!-- !PAGE CONTENT! -->
<div class="w3-main  w3-white" style="margin-left:300px;margin-top:43px;">
	<br/>
	<div class="w3-panel w3-center w3-container ">
		<a href="vd_cadastrar.php" class="w3-button w3-green w3-center">
			<i class="fa fa-plus"></i> Cadastrar
		</a>
		<br/>
		<br/>
		<a href="vd_entrar.php" class="w3-button w3-orange w3-center">
			<i class="fa fa-user-tag"></i> Já sou cadastrado
		</a>
	</div>
	<br/>
</div>

</body>
</html>
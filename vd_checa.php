<?php 
    
    session_start();

    if(isset($_SESSION["acesso_vida"]))
    {
        if($_SESSION["acesso_vida"] != true)
        {
            echo "<script language='javascript'>location.href='vd_welcome.php'</script>";
            die;
        }
    }
    else
    {
        echo "<script language='javascript'>location.href='vd_welcome.php'</script>";
        die;
    }

    date_default_timezone_set('America/Sao_Paulo');
?>
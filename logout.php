<?php
    session_start();
    session_destroy();
    echo "<script language='javascript'>location.href='vd_index.php'</script>";
?>
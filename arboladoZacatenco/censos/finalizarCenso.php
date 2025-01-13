<?php

include ("../db.php");

if(isset($_POST['finalizarReporte'])){
    $idCenso = $_GET['id'];

    $query = "UPDATE censos SET estado = 'Finalizado' WHERE id = '$idCenso';";
    $resultado = mysqli_query($conn, $query);

    if (!$resultado){
        $_SESSION['message'] = "M.toast({html: 'Ocurrió un problema al finalizar el censo'})";
        header("Location: vista_censos.php");
        die("Query failed");
    }

    $_SESSION['message'] = "M.toast({html: 'El censo ha sido finalizado', displayLength: 4000})";
    header("Location: vista_censos.php");
}

?>
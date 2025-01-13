<?php

include ("../db.php");

if(isset($_POST['editar_fecha'])){
    $fechaFin = $_POST['fechaFin'];
    $idCenso = $_GET['id'];

    $query = "SELECT fecha_fin FROM censos WHERE id = $idCenso;";
    $resultado = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($resultado);

    if(strcmp($row['fecha_fin'], $fechaFin) == 0){
        $_SESSION['message'] = "M.toast({html: 'Debes seleccionar una fecha diferente a la actual', displayLength: 8000})";
        header("Location: vista_censos.php");
        die();
    }

    $query = "UPDATE censos SET fecha_fin = '$fechaFin' WHERE id = '$idCenso';";
    $resultado = mysqli_query($conn, $query);

    if (!$resultado){
        $_SESSION['message'] = "M.toast({html: 'Ocurrió un problema al editar la fecha de finalización del censo'})";
        header("Location: vista_censos.php");
        die("Query failed");
    }

    $_SESSION['message'] = "M.toast({html: 'La fecha de finalización del censo se ha modificado', displayLength: 4000})";
    header("Location: vista_censos.php");
}

?>
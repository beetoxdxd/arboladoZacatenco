<?php

include ("../db.php");

if(isset($_POST['asignarB'])){
    $idBrigada = $_POST['seccionBrigada'];
    $idCenso = $_GET['censoId'];

    $query = "SELECT brigada FROM censos WHERE id = $idCenso;";
    $resultado = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($resultado);

    if(strcmp($row['brigada'], $idBrigada) == 0){
        $_SESSION['message'] = "M.toast({html: 'Debes seleccionar una brigada diferente a la actual', displayLength: 8000})";
        header("Location: vista_censos.php");
        die();
    }

    $query = "UPDATE censos SET brigada = '$idBrigada' WHERE id = '$idCenso';";
    $resultado = mysqli_query($conn, $query);

    if (!$resultado){
        $_SESSION['message'] = "M.toast({html: 'Ocurrió un problema al editar la brigada asignada al censo'})";
        header("Location: vista_censos.php");
        die("Query failed");
    }

    $_SESSION['message'] = "M.toast({html: 'La brigada asignada al censo se ha modificado', displayLength: 4000})";
    header("Location: vista_censos.php");
}

?>
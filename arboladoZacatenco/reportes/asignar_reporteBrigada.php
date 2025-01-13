<?php

include ("../db.php");

if(isset($_POST['asignarB'])){
    $reporteId = $_GET['reporteId'];
    $brigadaId = $_POST['seccionBrigada'];

    $query = "SELECT brigada_asignada FROM reporte WHERE id = $reporteId;";
    $resultado = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($resultado);

    if(strcmp($row['brigada_asignada'], $brigadaId) == 0){
        $_SESSION['message'] = "M.toast({html: 'Debes seleccionar una brigada diferente a la actual', displayLength: 8000})";
        header("Location: seguimiento_reporte.php");
        die();
    }

    $query = "UPDATE reporte SET brigada_asignada = '$brigadaId' WHERE reporte.id = '$reporteId';";
    $resultado = mysqli_query($conn, $query);

    if (!$resultado){
        $_SESSION['message'] = "M.toast({html: 'Ocurrió un problema al asignar la brigada al reporte'})";
        header("Location: seguimiento_reporte.php");
        die("Query failed");
    }

    $query = "SELECT nombre, seccion FROM brigadas WHERE id = '$brigadaId';";
    $resultado = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($resultado);
    $brig = $row['nombre'];
    $sec = $row['seccion'];

    $query = "UPDATE reporte set estado_reporte = 'En proceso' WHERE id = $reporteId";
    mysqli_query($conn, $query);
    $query = "INSERT INTO estado_reporte(reporte_id, notas) values ('$reporteId', CONCAT('El reporte está en el estado de En proceso. Ha sido asignado a la brigada ', '$brig', ', perteneciente a la sección ', '$sec', '.'))";
    mysqli_query($conn, $query);

    $_SESSION['message'] = "M.toast({html: 'La brigada se ha asignado correctamente', displayLength: 8000})";
    header("Location: seguimiento_reporte.php");
}

?>
<?php

include ("../db.php");

if(isset($_POST['borrar_brigada'])){
    $id = $_GET['id'];

    $query = "SELECT id FROM reporte WHERE brigada_asignada = '$id';";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_array($result)){
        $report = $row['id'];
        $query = "INSERT INTO estado_reporte(reporte_id, notas) values ('$report', 'La brigada a la que estaba asignada este reporte fue eliminada, por lo que el reporte pasó a un estado de Pendiente');";
        mysqli_query($conn, $query);
    }

    $query = "UPDATE reporte SET brigada_asignada = NULL, estado_reporte = 'Pendiente' WHERE brigada_asignada = '$id';";
    mysqli_query($conn, $query);

    $query = "UPDATE censos SET brigada = NULL WHERE brigada = '$id';";
    mysqli_query($conn, $query);

    $query = "DELETE FROM brigadas WHERE id='$id' ";
    $resultado = mysqli_query($conn, $query);

    if (!$resultado){
        $_SESSION['message'] = "M.toast({html: 'Ocurrió un problema al eliminar la brigada'})";
        header("Location: vista_brigadas.php");
        die("Query failed");
    }

    $_SESSION['message'] = "M.toast({html: 'La brigada se ha eliminado correctamente', displayLength: 8000})";
    header("Location: vista_brigadas.php");
}

?>
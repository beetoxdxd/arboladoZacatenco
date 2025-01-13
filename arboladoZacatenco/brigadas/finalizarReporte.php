<?php

include("../db.php"); 

// Verificar si los datos fueron enviados
if (isset($_POST['finalizarReporte'])) {
    $idReporte = $_GET['id'];
    $idBrigada = $_POST['idBrigada'];

    $sql = "UPDATE reporte SET estado_reporte = 'Resuelto' WHERE id = '$idReporte';";
    $_SESSION['idBrigada'] = $idBrigada;

    // Ejecutar la consulta
    if (mysqli_query($conn, $sql)) {
        $_SESSION['correcto'] = "M.toast({html: 'El reporte ha sido finalizado.', displayLength: 4000});";
        $query = "SELECT nombre, seccion FROM brigadas WHERE id = '$idBrigada';";
        $resultado = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($resultado);
        $brig = $row['nombre'];
        $sec = $row['seccion'];
        $query = "INSERT INTO estado_reporte(reporte_id, notas) values ('$idReporte', CONCAT('El reporte fue finalizado por la brigada ', '$brig', ', perteneciente a la sección ', '$sec', '.'));";
        mysqli_query($conn, $query);
        echo $brig . "  " . $sec;
        header("Location: monitoreoActividades.php");
    } else {
        echo "Error al guardar los datos: " . mysqli_error($conn);
        $_SESSION['correcto'] = "M.toast({html: 'Ocurrió un error al tratar de registrar el incidente.', displayLength: 4000});";
        header("Location: monitoreoActividades.php");
    }
}

?>

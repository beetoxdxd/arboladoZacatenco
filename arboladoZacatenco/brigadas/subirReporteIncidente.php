<?php

include("../db.php"); 

// Verificar si los datos fueron enviados
if (isset($_POST['registrarIncidente'])) {
    $idReporte = $_GET['id'];
    $opcion = $_POST["opcionIncidente$idReporte"];
    $fecha = $_POST["fechaIncidente$idReporte"];
    $hora = $_POST["horaIncidente$idReporte"];
    $descripcion = $_POST["descripcionIncidente$idReporte"];
    $accionTomada = $_POST["accionTomada$idReporte"];
    $idBrigada = $_POST['idBrigada'];

    // Escapar los datos para evitar inyecciones SQL
    $opcion = mysqli_real_escape_string($conn, $opcion);
    $fecha = mysqli_real_escape_string($conn, $fecha);
    $hora = mysqli_real_escape_string($conn, $hora);
    $descripcion = mysqli_real_escape_string($conn, $descripcion);
    $accionTomada = mysqli_real_escape_string($conn, $accionTomada);

    // Consulta SQL para insertar los datos
    $sql = "INSERT INTO reporteIncidente (opcion, fecha, hora, descripcion, accionTomada, reporte) VALUES ('$opcion', '$fecha', '$hora', '$descripcion', '$accionTomada', $idReporte)";
    $_SESSION['idBrigada'] = $idBrigada;

    // Ejecutar la consulta
    if (mysqli_query($conn, $sql)) {
        echo "¡Datos guardados exitosamente!";
        $_SESSION['correcto'] = "M.toast({html: 'El incidente se ha registrado correctamente.', displayLength: 4000});";
        header("Location: monitoreoActividades.php");
    } else {
        echo "Error al guardar los datos: " . mysqli_error($conn);
        $_SESSION['correcto'] = "M.toast({html: 'Ocurrió un error al tratar de registrar el incidente.', displayLength: 4000});";
        header("Location: monitoreoActividades.php");
    }
}

?>

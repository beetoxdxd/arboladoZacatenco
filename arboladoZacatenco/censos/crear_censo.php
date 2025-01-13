<?php

include ("../db.php");

if(isset($_POST['crear_censo'])){
    $nombreCenso = $_POST['nombreCenso'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];
    $brigadaCenso = $_POST['brigadaCenso'];

    $query = "INSERT INTO censos(nombre, fecha_inicio, fecha_fin, brigada, estado) VALUES (
              '$nombreCenso', '$fechaInicio', '$fechaFin', '$brigadaCenso', 'Activo');";

    $resultado = mysqli_query($conn, $query);

    if (!$resultado){
        $_SESSION['message'] = "M.toast({html: 'Ocurrió un problema al crear el censo'})";
        header("Location: vista_censos.php");
        die("Query failed");
    }

    $_SESSION['message'] = "M.toast({html: 'El censo se ha creado correctamente', displayLength: 4000})";
    header("Location: vista_censos.php");
}

?>
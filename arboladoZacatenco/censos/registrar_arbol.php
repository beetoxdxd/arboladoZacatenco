<?php

include("../db.php");

if(isset($_POST['subir_arbol'])){
    $especie = $_POST['especieSelect'];
    $altura = $_POST['altura'];
    $diametro = $_POST['diametro'];
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];
    $idCenso = $_GET['idCenso'];
    $condicion = $_POST["condicion$idCenso"];
    $idBrigada = $_POST['idBrigada'];


    $especie = mysqli_real_escape_string($conn, $especie);
    $altura = mysqli_real_escape_string($conn, $altura);
    $diametro = mysqli_real_escape_string($conn, $diametro);
    $latitud = mysqli_real_escape_string($conn, $latitud);
    $longitud = mysqli_real_escape_string($conn, $longitud);
    $condicion = mysqli_real_escape_string($conn, $condicion);

    // Consulta SQL para insertar los datos
    $sql = "INSERT INTO censo_arboles(especie, altura, diametro, latitud, longitud, condicion, censo) VALUES 
            ('$especie', '$altura', '$diametro', '$latitud', '$longitud', '$condicion', '$idCenso');";
    $_SESSION['idBrigada'] = $idBrigada;

    // Ejecutar la consulta
    if (mysqli_query($conn, $sql)) {
        echo "¡Datos guardados exitosamente!";
        $_SESSION['correcto'] = "M.toast({html: 'Los datos del arbol se han registrado correctamente.', displayLength: 4000});";
        header("Location: ../brigadas/monitoreoActividades.php");
    } else {
        echo "Error al guardar los datos: " . mysqli_error($conn);
        $_SESSION['correcto'] = "M.toast({html: 'Ocurrió un error al registrar el arbol.', displayLength: 4000});";
        header("Location: ../brigadas/monitoreoActividades.php");
    }
}

?>
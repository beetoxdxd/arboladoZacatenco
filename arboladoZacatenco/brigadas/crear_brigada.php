<?php

include ("../db.php");

if(isset($_POST['crear_brigada'])){
    $nombre = $_POST['nombreBrigada'];
    $seccion = $_POST['seccionBrigada'];

    $query = "INSERT INTO brigadas(nombre, seccion) VALUES (
              '$nombre', '$seccion');";

    $resultado = mysqli_query($conn, $query);

    if (!$resultado){
        $_SESSION['message'] = "M.toast({html: 'Ocurrió un problema al crear la brigada'})";
        header("Location: vista_brigadas.php");
        die("Query failed");
    }

    $_SESSION['message'] = "M.toast({html: 'La brigada se ha creado correctamente', displayLength: 8000})";
    header("Location: vista_brigadas.php");
}

?>
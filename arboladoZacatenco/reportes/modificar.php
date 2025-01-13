<?php
    include("../db.php");

    if(isset($_POST['editar_reporte'])){
        $id = $_GET['id'];
        $nota = $_POST["notas$id"];
        $estado = $_POST['estado'];

        $query = "SELECT estado_reporte FROM reporte WHERE id = $id;";
        $resultado = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($resultado);

        if(strcmp($row['estado_reporte'], $estado) == 0){
            $_SESSION['message'] = "M.toast({html: 'Debes seleccionar un estado diferente al actual', displayLength: 8000})";
            header("Location: seguimiento_reporte.php");
            die();
        }
        
        $query = "SELECT estado_reporte FROM reporte WHERE id = $id;";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        $prev_estado = $row['estado_reporte'];

        $query = "UPDATE reporte set estado_reporte = '$estado' WHERE id = $id;";
        $resultado = mysqli_query($conn, $query);

        if (!$resultado){
            $_SESSION['message'] = "M.toast({html: 'Ocurrió un problema al modificar el estado del reporte'})";
            header("Location: seguimiento_reporte.php");
            die("Query failed");
        }

        $query = "INSERT INTO estado_reporte(reporte_id, notas) values ('$id', CONCAT('El reporte pasó de un estado ', '$prev_estado', ' a ', '$estado', '. Los comentarios que se registaron son \"', '$nota', '\".'));";
        mysqli_query($conn, $query);

        $_SESSION['message'] = "M.toast({html: 'El estado del reporte ha sido modificado correctamente', displayLength: 8000})";
        header("Location: seguimiento_reporte.php");
    }
?>




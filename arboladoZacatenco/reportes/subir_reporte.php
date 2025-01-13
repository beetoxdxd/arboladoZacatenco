<?php

include ("../db.php");

if(isset($_POST['subir_reporte'])){
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];
    $tipoReporte = $_POST['tipo'];
    $descripcion = $_POST['descripcion'];
    $imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];

    $query = "INSERT INTO reporte(tipoReporte, latitud, longitud, descripcion, imagen, nombre, correo) VALUES (
              '$tipoReporte', '$latitud', '$longitud', '$descripcion', '$imagen', '$nombre', aes_encrypt('$correo', 'AES'));";

    $resultado = mysqli_query($conn, $query);

    if (!$resultado){
        $_SESSION['message'] = "M.toast({html: 'Ocurrió un problema al registrar el reporte'})";
        header("Location: reporte.php");
        die("Query failed");
    }

    $_SESSION['message'] = "M.toast({html: 'El reporte se ha registrado correctamente', displayLength: 8000})";

    if(!empty($correo)){
        ini_set('SMTP','smtp.gmail.com');
        ini_set('smtp_port', 587);
        // Modificar de acuerdo a la ubicación donde se encuentra instalado XAMPP
        ini_set('sendmail_path', "D:\xampp\sendmail\sendmail.exe -t"); 
        $subject = "Reporte de arbolado";
        if(!empty($nombre)){
            $remitente = $nombre;
        } else {
            $remitente = $correo;
        }
        $message = '
        <html>
            <head>
                <title>REPORTE REGISTRADO CON ÉXITO</title>
            </head>
            <body>
                <h1>Estimado '.$remitente.'...</h1>
                <p>Te informamos que se ha registrado correctamente un reporte de arbolado en la ubicación 
                ['.$latitud.', '.$longitud.'], correspondiente a la categoría: '.$tipoReporte.'. Más adelante,
                te informaremos la actualización en el estado de tu reporte.</p>
                <br><br>
                <p>Muchas gracias por tu colaboración.</p>
            </body>
        </html>
        ';
        $headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\n";
        $headers .= "From: betovegamon@gmail.com\r\nReturn-path: $correo\r\n";
        mail($correo,$subject,$message, $headers);

        $_SESSION['message_correo'] = "M.toast({html: 'Se ha enviado un mensaje a tu correo electrónico', displayLength: 8000})";
    }

    header("Location: reporte.php");
}

?>
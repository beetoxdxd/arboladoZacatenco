<?php
include("../db.php");

// Función para generar la contraseña aleatoria
function generarContrasenaAleatoria($longitud = 8) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $contrasena = '';
    $maxIndex = strlen($caracteres) - 1;

    for ($i = 0; $i < $longitud; $i++) {
        $contrasena .= $caracteres[random_int(0, $maxIndex)];
    }

    return $contrasena;
}

// Función para verificar si un correo está registrado
function verificarCorreo($correoCoord, $conn) {
    $query = "SELECT COUNT(*) as total FROM coordinadores WHERE correo = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("s", $correoCoord);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        return $fila['total'] > 0;
    } else {
        die("Error al preparar la consulta: " . $conn->error);
    }
}

// Función para verificar si el correo pertenece a un coordinador superior
function verificarCorreoRegistrador($registrador, $conn) {
    $sql = "SELECT coordSuper FROM coordinadores WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $registrador);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            return $usuario['coordSuper'] == 1 ? 1 : 0;
        }
    }
    return 0;
}

// Función para enviar la confirmación de registro por correo
function enviarConfirmacionRegistro($correoCoord, $contrasena) {
    $subject = "REGISTRO EXITOSO";
    $message = "
    <html>
        <head>
            <title>COORDINADOR REGISTRADO CON EXITO</title>
        </head>
        <body>
            <p>Su registro en la plataforma ArboladoZacatenco ha sido exitosa</p>
            <p>Inicie sesión con el correo $correoCoord y la contraseña $contrasena</p>
            <br>
        </body>
    </html>
    ";
    $headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\n";
    $headers .= "From: arboladoisw@gmail.com\r\nReturn-path: $correoCoord\r\n";
    mail($correoCoord, $subject, $message, $headers);

}

// Verificar si los datos fueron enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //$registrador = $_POST['registrador'];
    $registrador = "rodriguez.ayala.braulioemilio@gmail.com";
    $nombreCoord = $_POST['nombreCoord'];
    $apellidoCoord = $_POST['apellidoCoord'];
    $telefonoCoord = $_POST['telefonoCoord'];
    $correoCoord = $_POST['correoCoord'];
    //$coordSuper = isset($_POST['coordSuper']) ? "1" : "0"; 
    $coordSuper = "0";

    $contrasena = generarContrasenaAleatoria();
    $contrasenaCoord = password_hash($contrasena, PASSWORD_DEFAULT);

    // Verificar que el registrador sea válido
    /*if (!verificarCorreoRegistrador($registrador, $conn)) {
        $_SESSION['message'] = 'error_registrador';
        header("Location: registroCoordinador.php");
        exit();
    }*/

    // Verificar si el correo ya está registrado
    if (verificarCorreo($correoCoord, $conn)) {
        $_SESSION['message'] = 'correo_existente';
        header("Location: registroCoordinador.php");
        exit();
    }

    // Insertar los datos del nuevo coordinador
    $sql = "INSERT INTO coordinadores (nombre, apellido, correo, contrasena, telefono) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sssss", $nombreCoord, $apellidoCoord, $correoCoord, $contrasenaCoord, $telefonoCoord);
        
        if ($stmt->execute()) {
            enviarConfirmacionRegistro($correoCoord, $contrasena);
            $_SESSION['message'] = 'registro_exitoso';
            header("Location: registroCoordinador.php");
            exit();
        } else {
            echo "Error al guardar los datos: " . $conn->error;
        }
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
    }
}
?>

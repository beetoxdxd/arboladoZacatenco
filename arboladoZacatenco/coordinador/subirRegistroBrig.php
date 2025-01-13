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
function verificarCorreo($correoBrig, $conn) {
    $query = "SELECT COUNT(*) as total FROM brigadistas WHERE correo = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("s", $correoBrig);
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
function enviarConfirmacionRegistro($correoBrig, $contrasenaBrig) {
    $subject = "REGISTRO EXITOSO";
    $message = "
    <html>
        <head>
            <title>BRIGADISTA REGISTRADO CON EXITO</title>
        </head>
        <body>
            <p>Su registro en la plataforma ArboladoZacatenco ha sido exitosa</p>
            <p>Inicie sesión con el correo $correoBrig y la contraseña $contrasenaBrig</p>
            <br>
        </body>
    </html>
    ";
    $headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\n";
    $headers .= "From: arboladoisw@gmail.com\r\nReturn-path: $correoBrig\r\n";
    mail($correoBrig, $subject, $message, $headers);

}

// Verificar si los datos fueron enviados
if (isset($_POST['crear_brigadista'])) {
    $registrador = "rodriguez.ayala.braulioemilio@gmail.com";
    $nombreBrig = $_POST['nombreBrig'];
    $apellidoBrig = $_POST['apellidoBrig'];
    $telefonoBrig = $_POST['telefonoBrig'];
    $correoBrig = $_POST['correoBrig'];
    $idBrigada = $_POST['idBrigada'];

    $contrasena = generarContrasenaAleatoria();
    $contrasenaBrig = password_hash($contrasena, PASSWORD_DEFAULT);
    $_SESSION['idBrigada'] = $idBrigada;

    // Verificar que el registrador sea válido
    /*if (!verificarCorreoRegistrador($registrador, $conn)) {
        $_SESSION['message'] = 'error_registrador';
        header("Location: registroBrigadistas.php");
        exit();
    }*/

    // Verificar si el correo ya está registrado
    if (verificarCorreo($correoBrig, $conn)) {
        $_SESSION['message'] = 'correo_existente';
        header("Location: ../brigadas/monitoreoActividades.php");
        exit();
    }

    // Insertar los datos del nuevo coordinador
    $sql = "INSERT INTO brigadistas(nombre, apellidos, telefono, correo, contrasena, brigada) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sssssd", $nombreBrig, $apellidoBrig, $telefonoBrig, $correoBrig, $contrasenaBrig, $idBrigada);
        
        if ($stmt->execute()) {
            enviarConfirmacionRegistro($correoBrig, $contrasenaBrig);
            $_SESSION['message'] = 'registro_exitoso';
            header("Location: ../brigadas/monitoreoActividades.php");
            exit();
        } else {
            echo "Error al guardar los datos: " . $conn->error;
            $_SESSION['message'] = 'registro_fallido';
        }
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
        $_SESSION['message'] = 'registro_fallido';
    }

    header("Location: ../brigadas/monitoreoActividades.php");
}
?>

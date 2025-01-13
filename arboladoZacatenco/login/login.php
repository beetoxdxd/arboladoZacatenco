<?php
include("../db.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verificar si los datos fueron enviados mediante POST
if (isset($_POST['iniciar_sesion'])) {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Escapar los datos para evitar inyecciones SQL
    $correo = mysqli_real_escape_string($conn, $correo);
    $contrasena = mysqli_real_escape_string($conn, $contrasena);

    $sql = "SELECT * FROM brigadistas WHERE correo = '$correo' AND contrasena = '$contrasena'";
    $resultado = mysqli_query($conn, $sql);

    // Verificar si la consulta es exitosa
    if ($resultado && mysqli_num_rows($resultado) == 1) {
        // Usuario encontrado, redirigir al dashboard con un mensaje de éxito
        $_SESSION['message'] = "Inicio de sesión exitoso";
        $_SESSION['message_type'] = "success"; // Tipo de mensaje
        $_SESSION['correo'] = $correo; // Persistir el correo ingresado
        $_SESSION['tipoUsuario'] = 'brigadista';
        header("Location: ../index.php");
        exit();
    }

    // Consulta SQL para verificar las credenciales
    $sql = "SELECT * FROM coordinadores WHERE correo = '$correo' AND contrasena = '$contrasena'";
    $resultado = mysqli_query($conn, $sql);

    // Verificar si la consulta es exitosa
    if ($resultado && mysqli_num_rows($resultado) == 1) {
        // Usuario encontrado, redirigir al dashboard con un mensaje de éxito
        $_SESSION['message'] = "Inicio de sesión exitoso";
        $_SESSION['message_type'] = "success"; // Tipo de mensaje
        $_SESSION['correo'] = $correo; // Persistir el correo ingresado
        $_SESSION['tipoUsuario'] = 'coordinador';
        header("Location: ../index.php");
        exit();
    } else {
        // Usuario no encontrado, redirigir a indexa.php con un mensaje de error
        $_SESSION['message'] = "Correo o contraseña incorrectos";
        $_SESSION['message_type'] = "error"; // Tipo de mensaje
        $_SESSION['correo'] = $correo; // Persistir el correo ingresado
        header("Location: indexa.php");
    }
}
?>

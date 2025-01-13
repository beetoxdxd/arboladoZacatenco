
<?php
include ("../db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $seccion = $_POST['seccion'] ?? '';

    if (empty($nombre) || empty($apellidos) || empty($correo) || empty($seccion)) {
        echo json_encode(['error' => 'Por favor, llena todos los campos.']);
        exit();
    }

    try {
        $query = "INSERT INTO brigadistas (nombre, apellidos, correo, seccion) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $nombre, $apellidos, $correo, $seccion);
        $stmt->execute();

        echo json_encode(['message' => 'Brigadista registrado con éxito.']);
        header("Location: b.html");
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            echo json_encode(['error' => 'El correo ya está registrado.']);
        } else {
            echo json_encode(['error' => 'Error al registrar brigadista.']);
        }
    }
}
?>


<?php
include ("../db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['consultaCorreo'] ?? '';

    if (empty($correo)) {
        echo json_encode(['error' => 'Por favor, introduce un correo.']);
        exit();
    }

    try {
        $query = "SELECT * FROM brigadistas WHERE correo = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data) {
            echo json_encode($result);
            header("Location: b.html");
        } else {
            echo json_encode(['error' => 'No se encontró ningún brigadista con ese correo.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al consultar brigadista.']);
    }
}
?>


<?php
include ("../db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seccion = $_POST['consultaSeccion'] ?? '';

    if (empty($seccion)) {
        echo json_encode(['error' => 'Por favor, selecciona una sección.']);
        exit();
    }

    try {
        $query = "SELECT * FROM brigadistas WHERE seccion = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $seccion);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data) {
            echo json_encode($results);
            header("Location: b.html");
        } else {
            echo json_encode(['error' => 'No hay brigadistas en esta sección.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al consultar brigada.']);
    }
}
?>

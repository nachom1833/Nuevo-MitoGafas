<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

// Configuración de la conexión
$servername = "localhost";
$username = "admin_mitoGafas";
$password = "8D/RC3L4mX";
$database = "admin_mitoGafas";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    echo json_encode(["error" => "Error al conectarse a la base de datos: " . $conn->connect_error]);
    exit;
}

// Establecer el juego de caracteres a UTF-8
$conn->set_charset("utf8");

// Verificar si se pasó una localidad como parámetro en la URL
$opticas = array();
if (isset($_GET['localidad'])) {
    $sql = "SELECT * FROM opticas WHERE localidad = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_GET['localidad']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $opticas[] = $row;
        }
    } else {
        $opticas = ["message" => "No se encontraron resultados"];
    }

    $stmt->close();
} else {
    $sql = "SELECT * FROM opticas";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $opticas[] = $row;
        }
    } else {
        $opticas = ["message" => "No se encontraron resultados"];
    }
}

// Cerrar la conexión
$conn->close();

// Devolver los resultados como JSON
echo json_encode($opticas);
?>

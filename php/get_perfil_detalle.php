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

// Obtener el parámetro "id" de la URL
$imagenId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$imagenId) {
    echo json_encode(["error" => "No se especificó un ID de imagen."]);
    exit;
}

// Consulta SQL para obtener las imágenes relacionadas
$sql = "
    SELECT 
        i.id AS imagen_id,
        i.url AS imagen_principal,
        r1.perfil AS perfil,
        r2.detalle AS detalle
    FROM imagenes i
    LEFT JOIN imagenes r1 ON r1.id = i.relacion_1_id
    LEFT JOIN imagenes r2 ON r2.id = i.relacion_2_id
    WHERE i.id = ?
";

// Preparar la consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $imageId);
$stmt->execute();

// Obtener el resultado
$result = $stmt->get_result();

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
    $images = $result->fetch_assoc(); // Se asume un único registro por ID
    echo json_encode($images);
} else {
    echo json_encode(["error" => "No se encontraron imágenes para el ID especificado."]);
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>

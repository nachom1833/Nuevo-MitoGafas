<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

// Mostrar errores para depuración (desactiva esto en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
if (!$conn->set_charset("utf8")) {
    echo json_encode(["error" => "Error al configurar el juego de caracteres UTF-8: " . $conn->error]);
    exit;
}

// Obtener los parámetros de la URL
$categoria = $_GET['categoria'] ?? '';
$material = $_GET['material'] ?? '';
$coleccion = $_GET['coleccion'] ?? '';

// Comprobar que al menos un filtro esté presente
if (empty($categoria) && empty($material) && empty($coleccion)) {
    echo json_encode(["error" => "Debe proporcionar al menos un filtro (categoría, material o colección)."]);
    exit;
}

// Construir la consulta SQL con los filtros
$sql = "SELECT 
    p.id, 
    p.codigo, 
    p.descripcion, 
    p.coleccion, 
    p.categoria, 
    p.color, 
    p.material, 
    p.imagen,
    GROUP_CONCAT(i.url) AS imagenes_secundarias
FROM productos p
LEFT JOIN imagenes i ON p.id = i.producto_id
WHERE 1=1"; // Asegura que puedes concatenar condiciones dinámicas

// Agregar filtros dinámicamente
$params = [];
$types = "";
if (!empty($categoria)) {
    $sql .= " AND p.categoria = ?";
    $params[] = $categoria;
    $types .= "s";
}
if (!empty($material)) {
    $sql .= " AND p.material = ?";
    $params[] = $material;
    $types .= "s";
}
if (!empty($coleccion)) {
    $sql .= " AND p.coleccion = ?";
    $params[] = $coleccion;
    $types .= "s";
}
// Asegurarse de que el producto tenga una imagen principal
$sql .= " AND p.imagen IS NOT NULL"; // Solo productos con imagen principal

// Agrupar después de aplicar los filtros
$sql .= " GROUP BY p.id";

// Preparar la consulta
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Error al preparar la consulta: " . $conn->error]);
    exit;
}

// Vincular los parámetros si los hay
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// Ejecutar la consulta
if (!$stmt->execute()) {
    echo json_encode(["error" => "Error al ejecutar la consulta: " . $stmt->error]);
    exit;
}

// Obtener el resultado
$result = $stmt->get_result();

// Crear un array para almacenar los productos
$productos = [];

while ($row = $result->fetch_assoc()) {
    // Separar las imágenes secundarias en un array
    $row['imagenes_secundarias'] = !empty($row['imagenes_secundarias']) ? explode(',', $row['imagenes_secundarias']) : [];
    $productos[] = $row;
}

// Devolver los productos en formato JSON
if (!empty($productos)) {
    echo json_encode($productos);
} else {
    echo json_encode(["error" => "No se encontraron productos."]);
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>

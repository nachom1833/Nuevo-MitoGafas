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

// Obtener el parámetro "id" de la URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$productId) {
    echo json_encode(["error" => "No se especificó un ID de producto válido."]);
    exit;
}

// Consulta SQL para obtener el producto con sus imágenes
$sql = "SELECT 
    p.id, 
    p.codigo, 
    p.descripcion, 
    p.coleccion, 
    p.categoria, 
    p.color, 
    p.material,
    p.imagen, 
    i.id AS imagen_id,
    i.url AS imagen_url,
    i.perfil,
    i.stock,
    i.color
FROM productos p
LEFT JOIN imagenes i ON p.id = i.producto_id
WHERE p.id = ?";

// Preparar la consulta
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error al preparar la consulta: " . $conn->error]);
    exit;
}

// Vincular parámetros y ejecutar la consulta
$stmt->bind_param("i", $productId);

if (!$stmt->execute()) {
    echo json_encode(["error" => "Error al ejecutar la consulta: " . $stmt->error]);
    $stmt->close();
    $conn->close();
    exit;
}

// Obtener el resultado
$result = $stmt->get_result();

// Verificar si se encontró el producto
if ($result->num_rows > 0) {
    $producto = null;
    $imagenes = [];
    
    while ($row = $result->fetch_assoc()) {
        // Si es la primera fila, inicializar los datos del producto
        if (!$producto) {
            $producto = [
                "id" => $row["id"],
                "codigo" => $row["codigo"],
                "descripcion" => $row["descripcion"],
                "coleccion" => $row["coleccion"],
                "categoria" => $row["categoria"],
                "color" => $row["color"],
                "material" => $row["material"],
                "imagen" => $row["imagen"],
                "imagenes_secundarias" => []
            ];
        }

        // Agregar cada imagen secundaria con su perfil y detalle
        if (!empty($row["imagen_id"])) {
            $imagenes[] = [
                "id" => $row["imagen_id"],
                "url" => $row["imagen_url"],
                "perfil" => $row["perfil"],
                "stock" => $row["stock"],
                "color" => $row["color"]
            ];
        }
    }
    
    // Agregar las imágenes secundarias al producto
    $producto["imagenes_secundarias"] = $imagenes;

    // Devolver los datos del producto en formato JSON
    echo json_encode($producto);
} else {
    echo json_encode(["error" => "Producto no encontrado."]);
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>

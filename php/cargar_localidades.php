<?php

// Configuración de la conexión
$servername = "localhost";
$username = "admin_mitoGafas";
$password = "8D/RC3L4mX";
$database = "admin_mitoGafas";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

if (!$conn) {
    echo json_encode(["error" => "Error al conectarse a la base de datos: " . mysqli_connect_error()]);
    exit;
}

mysqli_set_charset($conn, "utf8");

// Consulta para obtener localidades únicas
$sql = "SELECT DISTINCT localidad FROM `opticas` ORDER BY `opticas`.`localidad` ASC";  // Cambia 'opticas' y 'localidad' a tus nombres de tabla y columna reales
$result = mysqli_query($conn, $sql);

$localidades = [];
while ($row = mysqli_fetch_assoc($result)) {
    $localidades[] = $row['localidad'];
}

echo json_encode($localidades);
?>

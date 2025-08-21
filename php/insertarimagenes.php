<?php/*
// Configuración de la base de datos
$servername = "localhost";
$username = "admin_mitoGafas";
$password = "8D/RC3L4mX";
$database = "admin_mitoGafas";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Directorios de las imágenes
$directorioFrente = __DIR__ . '/img/frente cuadrado/';
$directorioPerfil = __DIR__ . '/img/perfil/';
$webPathFrente = 'img/frente cuadrado/';
$webPathPerfil = 'img/perfil/';

$archivos = scandir($directorioFrente);

foreach ($archivos as $archivo) {
    if (!preg_match('/\.jpe?g$/i', $archivo)) continue;

    // Extraer código del archivo (buscar máximo match posible)
    if (preg_match('/([A-Z0-9]{3,}\s?[A-Z0-9]*)(?=\s*C\d{0,2})?/i', $archivo, $coincidencias)) {
        $codigo = trim($coincidencias[1]);

        // Buscar producto_id
        $stmt = $pdo->prepare("SELECT id FROM productos WHERE REPLACE(codigo, ' ', '') = REPLACE(?, ' ', '') LIMIT 1");
        $stmt->execute([$codigo]);
        $producto = $stmt->fetch();

        if ($producto) {
            $producto_id = $producto['id'];

            // Verificar si ya existe una imagen para ese producto
            $verificar = $pdo->prepare("SELECT COUNT(*) FROM imagenes WHERE producto_id = ?");
            $verificar->execute([$producto_id]);
            if ($verificar->fetchColumn() == 0) {
                $rutaFrente = $webPathFrente . $archivo;

                // Intentar encontrar imagen de perfil
                $nombreSinExtension = pathinfo($archivo, PATHINFO_FILENAME);
                $perfilPath = $directorioPerfil . $nombreSinExtension . ' perfil.jpg';
                $rutaPerfil = null;

                if (file_exists($perfilPath)) {
                    $rutaPerfil = $webPathPerfil . $nombreSinExtension . ' perfil.jpg';
                }

                $insert = $pdo->prepare("INSERT INTO imagenes (producto_id, url, perfil, stock, color) VALUES (?, ?, ?, 1, '')");
                $insert->execute([$producto_id, $rutaFrente, $rutaPerfil]);

                echo "Insertado: $codigo -> $rutaFrente";
                if ($rutaPerfil) echo " + perfil: $rutaPerfil";
                echo "\n";
            } else {
                echo "Ya existe imagen para: $codigo\n";
            }
        } else {
            echo "No encontrado en productos: $codigo\n";
        }
    } else {
        echo "No se pudo extraer código de: $archivo\n";
    }
}

echo "\n--- Proceso terminado ---";

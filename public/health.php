<?php
/**
 * Health Check - CBAV CRM Ministerial
 * Desenvolvido por Vertex Solutions - CEO Reinan Rodrigues
 */

header("Content-Type: application/json");

$status = [
    "status" => "healthy",
    "timestamp" => date("Y-m-d H:i:s"),
    "version" => "1.0.0",
    "environment" => $_ENV["APP_ENV"] ?? "unknown",
    "database" => "unknown",
    "cache" => "unknown",
    "storage" => "unknown"
];

// Verificar conexão com banco
try {
    $pdo = new PDO(
        "mysql:host=" . ($_ENV["DB_HOST"] ?? "localhost") . 
        ";dbname=" . ($_ENV["DB_DATABASE"] ?? "cbav"),
        $_ENV["DB_USERNAME"] ?? "root",
        $_ENV["DB_PASSWORD"] ?? ""
    );
    $status["database"] = "connected";
} catch (Exception $e) {
    $status["database"] = "error";
    $status["status"] = "unhealthy";
}

// Verificar cache
if (is_writable(__DIR__ . "/../storage/framework/cache")) {
    $status["cache"] = "writable";
} else {
    $status["cache"] = "error";
    $status["status"] = "unhealthy";
}

// Verificar storage
if (is_writable(__DIR__ . "/../storage")) {
    $status["storage"] = "writable";
} else {
    $status["storage"] = "error";
    $status["status"] = "unhealthy";
}

// Verificar arquivos essenciais
$essentialFiles = [
    __DIR__ . "/../vendor/autoload.php",
    __DIR__ . "/../bootstrap/app.php",
    __DIR__ . "/index.php"
];

foreach ($essentialFiles as $file) {
    if (!file_exists($file)) {
        $status["status"] = "unhealthy";
        $status["missing_files"] = true;
        break;
    }
}

echo json_encode($status, JSON_PRETTY_PRINT);

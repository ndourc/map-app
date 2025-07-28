<?php
/**
 * City Business Map Configuration
 * 
 * This file contains all the configuration settings for the application.
 * Update these values according to your environment.
 */

// Database Configuration
$config = [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'city_business_map',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ],
    
    // Application Settings
    'app' => [
        'name' => 'City Business Map',
        'version' => '1.0.0',
        'debug' => false,
        'timezone' => 'UTC'
    ],
    
    // API Settings
    'api' => [
        'cors_origin' => '*',
        'max_results' => 100,
        'cache_duration' => 300 // 5 minutes
    ],
    
    // Map Settings
    'map' => [
        'default_zoom' => 13,
        'max_zoom' => 18,
        'tile_layer' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        'attribution' => '© OpenStreetMap contributors'
    ],
    
    // Geocoding Settings
    'geocoding' => [
        'nominatim_url' => 'https://nominatim.openstreetmap.org/reverse',
        'timeout' => 10,
        'user_agent' => 'CityBusinessMap/1.0'
    ]
];

// Set timezone
date_default_timezone_set($config['app']['timezone']);

// Error reporting (disable in production)
if ($config['app']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Helper function to get config values
function getConfig($key) {
    global $config;
    $keys = explode('.', $key);
    $value = $config;
    
    foreach ($keys as $k) {
        if (isset($value[$k])) {
            $value = $value[$k];
        } else {
            return null;
        }
    }
    
    return $value;
}

// Database connection helper
function getDatabaseConnection() {
    $db = getConfig('database');
    
    try {
        $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}";
        $pdo = new PDO($dsn, $db['username'], $db['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        return null;
    }
}

// CORS headers helper
function setCorsHeaders() {
    $origin = getConfig('api.cors_origin');
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Content-Type: application/json; charset=utf-8');
}

// Response helper
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Error response helper
function sendErrorResponse($message, $statusCode = 400) {
    sendJsonResponse(['error' => $message], $statusCode);
}

// Validation helper
function validateCity($city) {
    return !empty($city) && strlen($city) <= 100;
}

function validateBusinessType($type) {
    $validTypes = ['restaurant', 'fast_food', 'tourism'];
    return empty($type) || in_array($type, $validTypes);
}
?> 
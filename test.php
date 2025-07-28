<?php
/**
 * City Business Map - Test File
 * 
 * This file helps verify that the application is set up correctly.
 * Access this file in your browser to run the tests.
 */

require_once 'config.php';

echo "<h1>🏗️ City Business Map - System Test</h1>";

// Test 1: Configuration
echo "<h2>✅ Configuration Test</h2>";
echo "<p>App Name: " . getConfig('app.name') . "</p>";
echo "<p>Version: " . getConfig('app.version') . "</p>";
echo "<p>Debug Mode: " . (getConfig('app.debug') ? 'Enabled' : 'Disabled') . "</p>";

// Test 2: Database Connection
echo "<h2>🗄️ Database Connection Test</h2>";
$pdo = getDatabaseConnection();
if ($pdo) {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test query
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM businesses");
        $result = $stmt->fetch();
        echo "<p>Total businesses in database: " . $result['count'] . "</p>";
    } catch (Exception $e) {
        echo "<p style='color: orange;'>⚠️ Database query failed: " . $e->getMessage() . "</p>";
        echo "<p>This is normal if you haven't set up the database yet.</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ Database connection failed - using sample data mode</p>";
}

// Test 3: API Endpoint Test
echo "<h2>🔌 API Endpoint Test</h2>";
$apiUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/api/businesses.php?city=New%20York';
echo "<p>Testing API endpoint: <a href='$apiUrl' target='_blank'>$apiUrl</a></p>";

// Test 4: File Structure
echo "<h2>📁 File Structure Test</h2>";
$requiredFiles = [
    'index.html',
    'css/style.css',
    'js/app.js',
    'api/businesses.php',
    'config.php',
    'database/setup.sql'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $file</p>";
    } else {
        echo "<p style='color: red;'>❌ $file (missing)</p>";
    }
}

// Test 5: PHP Extensions
echo "<h2>🔧 PHP Extensions Test</h2>";
$requiredExtensions = ['pdo', 'pdo_mysql', 'json'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p style='color: green;'>✅ $ext</p>";
    } else {
        echo "<p style='color: red;'>❌ $ext (missing)</p>";
    }
}

// Test 6: Sample Data Generation
echo "<h2>📊 Sample Data Test</h2>";
require_once 'api/businesses.php';
$sampleData = generateSampleData('Test City', 'restaurant');
echo "<p>Sample data generated: " . count($sampleData) . " businesses</p>";
echo "<pre>" . json_encode($sampleData, JSON_PRETTY_PRINT) . "</pre>";

// Test 7: Application Link
echo "<h2>🚀 Application Test</h2>";
$appUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/index.html';
echo "<p><a href='$appUrl' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🚀 Launch City Business Map</a></p>";

echo "<hr>";
echo "<p><strong>Note:</strong> If you see any ❌ marks, please check the installation instructions in the README.md file.</p>";
echo "<p><strong>Tip:</strong> The application will work with sample data even if the database is not set up.</p>";
?> 
<?php

/**
 * debug_view.php
 *
 * Debug-Script für die View Engine
 */

require_once __DIR__ . '/brick/Core/Autoloader.php';

$autoloader = new Brick\Core\Autoloader();
$autoloader->addNamespace('App', __DIR__ . '/app');
$autoloader->addNamespace('Brick', __DIR__ . '/brick');
$autoloader->register();

use Brick\Core\View;

// View-System initialisieren
$view = new View(
    viewPath: __DIR__ . '/app/Views',
    cachePath: sys_get_temp_dir() . '/brick_views_debug',
    debug: true
);

echo "📂 View Path: " . __DIR__ . '/app/Views' . "\n";
echo "📁 Layout Path: " . __DIR__ . '/app/Views/layouts' . "\n";
echo "🗂️ Cache Path: " . sys_get_temp_dir() . '/brick_views_debug' . "\n";

// Verfügbare Templates prüfen
echo "\n📋 Available Templates:\n";
$viewDir = __DIR__ . '/app/Views';
$files = glob($viewDir . '/*.php');
foreach ($files as $file) {
    echo "  - " . basename($file, '.php') . "\n";
}

echo "\n📋 Available Layouts:\n";
$layoutDir = __DIR__ . '/app/Views/layouts';
$layouts = glob($layoutDir . '/*.php');
foreach ($layouts as $layout) {
    echo "  - " . basename($layout, '.php') . "\n";
}

// Test-Rendering
echo "\n🧪 Testing simple template WITHOUT layout...\n";
try {
    $html = $view->render('simple', []);
    echo "✅ Rendering successful!\n";
    echo "📏 Output length: " . strlen($html) . " characters\n";
    echo "🔍 Contains SIMPLE TEST: " . (strpos($html, 'SIMPLE TEST') !== false ? 'YES' : 'NO') . "\n";
    
    // Zeige die ersten 200 Zeichen
    echo "\n📄 Output:\n";
    echo $html . "\n";
} catch (Exception $e) {
    echo "❌ Rendering failed: " . $e->getMessage() . "\n";
}
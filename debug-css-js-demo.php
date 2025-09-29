<?php
/**
 * Debug CSS-JS Demo Route
 */

// Simulate the same environment as the route
require_once __DIR__ . '/brick/Core/Autoloader.php';

$autoloader = new Brick\Core\Autoloader();
$autoloader->addNamespace('App', __DIR__ . '/app');
$autoloader->addNamespace('Brick', __DIR__ . '/brick');
$autoloader->register();

use Brick\Core\View;
use Brick\Http\Response;

echo "🔍 Debugging CSS-JS Demo Route\n";
echo "═════════════════════════════════\n\n";

try {
    // Exactly the same code as in the route
    $response = new Response();
    
    $view = new View(
        viewPath: __DIR__ . '/app/Views',
        cachePath: sys_get_temp_dir() . '/brick_views',
        debug: true
    );
    
    $view->shareAll([
        'appName' => $_ENV['APP_NAME'] ?? 'Brick Framework',
        'version' => '1.0.0',
        'debug' => $_ENV['APP_DEBUG'] ?? true,
        'currentYear' => date('Y')
    ]);

    echo "✅ View system initialized\n";
    echo "📁 View path: " . __DIR__ . '/app/Views' . "\n";
    echo "💾 Cache path: " . sys_get_temp_dir() . '/brick_views' . "\n\n";

    // Check if template exists
    $templatePath = __DIR__ . '/app/Views/css-js-demo.php';
    echo "📄 Template exists: " . (file_exists($templatePath) ? 'YES' : 'NO') . "\n";
    echo "📄 Template size: " . filesize($templatePath) . " bytes\n\n";

    echo "🔄 Attempting to render template...\n";
    
    $html = $view->render('css-js-demo', [
        'title' => 'CSS & JavaScript Demo',
        'showDemo' => true
    ]);
    
    echo "✅ Template rendered successfully!\n";
    echo "📏 Output length: " . strlen($html) . " characters\n";
    echo "🔍 Contains HTML: " . (strpos($html, '<html>') !== false ? 'YES' : 'NO') . "\n";
    echo "🎨 Contains CSS: " . (strpos($html, '<style>') !== false ? 'YES' : 'NO') . "\n";
    echo "⚡ Contains JS: " . (strpos($html, '<script>') !== false ? 'YES' : 'NO') . "\n\n";

    // Show first 200 characters
    echo "📄 First 200 characters of output:\n";
    echo "─────────────────────────────────────\n";
    echo substr($html, 0, 200) . "...\n\n";
    
    // Save to file for inspection
    file_put_contents('/tmp/brick_debug_output.html', $html);
    echo "💾 Full output saved to: /tmp/brick_debug_output.html\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
    echo "\n🔍 Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
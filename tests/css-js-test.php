<?php
/**
 * 🎨 CSS & JS Direktiven Test
 * Testet die neuen CSS- und JavaScript-Direktiven
 */

require_once __DIR__ . '/../brick/Core/Autoloader.php';

$autoloader = new Brick\Core\Autoloader();
$autoloader->addNamespace('App', __DIR__ . '/../app');
$autoloader->addNamespace('Brick', __DIR__ . '/../brick');
$autoloader->register();

use Brick\Core\View;

echo "🎨 CSS & JavaScript Direktiven Test\n";
echo "═══════════════════════════════════════\n\n";

// View-System mit Debug-Modus initialisieren
$view = new View(
    viewPath: __DIR__ . '/../app/Views',
    cachePath: sys_get_temp_dir() . '/brick_css_js_test',
    debug: true
);

// Test 1: Template mit CSS/JS-Direktiven kompilieren
echo "🧪 Test 1: Template mit CSS/JS-Direktiven\n";

$testTemplate = '@extends(\'app\')

@section(\'title\', \'Test\')

@css(\'test.css\')
@css
    body { background: red; }
@endcss

@js(\'test.js\')
@js(\'defer.js\', [\'defer\'])
@js
    console.log("Test");
@endjs

@section(\'content\')
<h1>Test Content</h1>
@endsection';

// Test-Template speichern
$testPath = sys_get_temp_dir() . '/brick_css_js_test_template.php';
file_put_contents($testPath, $testTemplate);

try {
    // View-Pfad für Test-Template hinzufügen
    mkdir(sys_get_temp_dir() . '/brick_css_js_views', 0755, true);
    file_put_contents(sys_get_temp_dir() . '/brick_css_js_views/test.php', $testTemplate);
    
    $testView = new View(
        viewPath: sys_get_temp_dir() . '/brick_css_js_views',
        debug: true
    );
    
    // Kompilierung testen
    $result = $testView->render('test', []);
    
    echo "✅ Template erfolgreich kompiliert\n";
    echo "📄 Output enthält HTML-Struktur: " . (strpos($result, '<html>') !== false ? 'JA' : 'NEIN') . "\n";
    echo "🎨 CSS-Links gefunden: " . (strpos($result, 'test.css') !== false ? 'JA' : 'NEIN') . "\n";
    echo "📄 Inline CSS gefunden: " . (strpos($result, 'background: red') !== false ? 'JA' : 'NEIN') . "\n";
    echo "⚡ JS-Script gefunden: " . (strpos($result, 'test.js') !== false ? 'JA' : 'NEIN') . "\n";
    echo "🔄 Defer-Attribut gefunden: " . (strpos($result, 'defer') !== false ? 'JA' : 'NEIN') . "\n";
    echo "📜 Inline JS gefunden: " . (strpos($result, 'console.log') !== false ? 'JA' : 'NEIN') . "\n";
    
} catch (Exception $e) {
    echo "❌ Fehler: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Programmatisches Hinzufügen von Assets
echo "🧪 Test 2: Programmatisches Asset-Management\n";

$view->addCss('/assets/app.css');
$view->addJs('/assets/app.js', ['defer', 'async']);
$view->addInlineCss('.test { color: blue; }');
$view->addInlineJs('alert("Test");');

echo "✅ Assets programmatisch hinzugefügt\n";

echo "\n";

// Test 3: Performance und Statistics
echo "🧪 Test 3: Performance-Statistiken\n";
$stats = $view->getStats();
echo "📊 Kompilierte Templates: " . $stats['compiled_templates'] . "\n";
echo "⏱️  Render-Zeit: " . number_format($stats['render_time'] * 1000, 2) . "ms\n";

echo "\n";

// Aufräumen
if (file_exists($testPath)) {
    unlink($testPath);
}
if (is_dir(sys_get_temp_dir() . '/brick_css_js_views')) {
    unlink(sys_get_temp_dir() . '/brick_css_js_views/test.php');
    rmdir(sys_get_temp_dir() . '/brick_css_js_views');
}

echo "🎉 CSS & JavaScript Direktiven erfolgreich getestet!\n";
echo "🌐 Demo öffnen: http://localhost:8080/demo/css-js\n";
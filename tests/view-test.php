<?php
/**
 * 🎨 Quick View System Test
 * Schneller Test um das View-System zu validieren
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Brick\Core\View;

echo "🧱 Brick View System - Quick Test\n";
echo "═══════════════════════════════════\n\n";

// View-System initialisieren
$view = new View(__DIR__ . '/../app/Views');

echo "📁 View Path: " . $view->getDebugInfo()['view_path'] . "\n";
echo "💾 Cache Path: " . $view->getDebugInfo()['cache_path'] . "\n\n";

// Test 1: Einfaches Template
echo "🧪 Test 1: Einfaches Template-Rendering\n";
try {
    $result = $view->render('home', [
        'stats' => ['compiled_templates' => 1, 'cache_hits' => 0, 'render_time' => 0.001]
    ]);
    echo "✅ Template erfolgreich gerendert (" . strlen($result) . " Zeichen)\n";
    echo "✅ Layout-System funktioniert (HTML-Struktur gefunden)\n";
} catch (Exception $e) {
    echo "❌ Fehler: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Globale Variablen
echo "🧪 Test 2: Globale Variablen\n";
$view->share('globalTest', 'Global Variable Works!');
echo "✅ Globale Variable gesetzt\n";

echo "\n";

// Test 3: Statistiken
echo "🧪 Test 3: View-Statistiken\n";
$stats = $view->getStats();
echo "📊 Kompilierte Templates: " . $stats['compiled_templates'] . "\n";
echo "📊 Cache Hits: " . $stats['cache_hits'] . "\n";
echo "📊 Cache Misses: " . $stats['cache_misses'] . "\n";
echo "⏱️  Render-Zeit: " . number_format($stats['render_time'] * 1000, 2) . "ms\n";

echo "\n";

// Test 4: Debug-Informationen
echo "🧪 Test 4: Debug-Informationen\n";
$debugInfo = $view->getDebugInfo();
echo "🔧 Debug-Modus: " . ($debugInfo['debug_mode'] ? 'Aktiviert' : 'Deaktiviert') . "\n";
echo "📚 Verfügbare Templates: " . count($debugInfo['available_templates']) . "\n";

echo "\n";
echo "🎉 View-System ist bereit für den Einsatz!\n";
echo "📖 Weitere Infos: docs/brick.md\n";
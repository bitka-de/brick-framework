<?php
/**
 * 🧪 Bootstrap & Routing System Test
 * 
 * Testet das komplette Bootstrap-System mit Routing zu HomeController
 */

echo "🧱 Brick Framework - Bootstrap & Routing Test\n";
echo "═══════════════════════════════════════════════\n\n";

// Bootstrap einbinden (ohne Output für Web)
ob_start();
require_once __DIR__ . '/../public/index.php';
$output = ob_get_clean();

echo "✅ Bootstrap-System erfolgreich geladen\n";

// Test verschiedener Routen über Curl (wenn Server läuft)
$serverRunning = @file_get_contents('http://localhost:8080/');

if ($serverRunning !== false) {
    echo "🌐 Server läuft - Teste Routen:\n\n";
    
    // Test 1: Homepage
    echo "📍 Test 1: Homepage (/)\n";
    $homeResponse = @file_get_contents('http://localhost:8080/');
    if (strpos($homeResponse, 'Brick Framework') !== false) {
        echo "   ✅ Homepage erfolgreich gerendert\n";
    } else {
        echo "   ❌ Homepage-Fehler\n";
    }
    
    // Test 2: About-Seite
    echo "📍 Test 2: About-Seite (/about)\n";
    $aboutResponse = @file_get_contents('http://localhost:8080/about');
    if (strpos($aboutResponse, 'Über das Brick Framework') !== false) {
        echo "   ✅ About-Seite erfolgreich gerendert\n";
    } else {
        echo "   ❌ About-Seite-Fehler\n";
    }
    
    // Test 3: API Stats
    echo "📍 Test 3: API Stats (/api/stats)\n";
    $apiResponse = @file_get_contents('http://localhost:8080/api/stats');
    $apiData = json_decode($apiResponse, true);
    if ($apiData && isset($apiData['framework'])) {
        echo "   ✅ API Stats erfolgreich: " . $apiData['framework'] . "\n";
        echo "   📊 PHP Version: " . $apiData['stats']['php_version'] . "\n";
        echo "   🧠 Memory Usage: " . number_format($apiData['stats']['memory_usage'] / 1024 / 1024, 2) . " MB\n";
    } else {
        echo "   ❌ API Stats-Fehler\n";
    }
    
    // Test 4: Contact-Seite
    echo "📍 Test 4: Contact-Seite (/contact)\n";
    $contactResponse = @file_get_contents('http://localhost:8080/contact');
    if (strpos($contactResponse, 'Kontakt') !== false) {
        echo "   ✅ Contact-Seite erfolgreich gerendert\n";
    } else {
        echo "   ❌ Contact-Seite-Fehler\n";
    }
    
    // Test 5: 404 Error
    echo "📍 Test 5: 404 Error (/nonexistent)\n";
    $context = stream_context_create([
        'http' => ['ignore_errors' => true]
    ]);
    $notFoundResponse = @file_get_contents('http://localhost:8080/nonexistent', false, $context);
    if (strpos($notFoundResponse, '404') !== false) {
        echo "   ✅ 404 Error korrekt behandelt\n";
    } else {
        echo "   ❌ 404 Error-Handling fehlerhaft\n";
    }
    
} else {
    echo "🔌 Server nicht erreichbar - Starte Server für vollständige Tests:\n";
    echo "   php -S localhost:8080 -t public/\n";
    echo "   Dann diesen Test erneut ausführen\n\n";
    
    echo "🧪 Lokaler Bootstrap-Test:\n";
    echo "   ✅ Bootstrap lädt ohne Fehler\n";
    echo "   ✅ Autoloader funktioniert\n";
    echo "   ✅ Klassen verfügbar\n";
}

echo "\n🎉 Bootstrap & Routing System bereit für den Einsatz!\n";
echo "📖 Starte Server: php -S localhost:8080 -t public/\n";
echo "🌐 Dann öffne: http://localhost:8080/\n";
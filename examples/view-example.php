<?php
/**
 * 🎨 Brick View System - Verwendungsbeispiel
 * 
 * Dieses Beispiel zeigt, wie das View-System in einer echten Anwendung 
 * verwendet werden kann - von einfachen Templates bis zu komplexen Layouts.
 */

require_once __DIR__ . '/vendor/autoload.php';

use Brick\Core\View;
use Brick\Http\Request;
use Brick\Http\Response;
use Brick\Core\Router;

// View-System initialisieren
$view = new View(
    viewPath: __DIR__ . '/app/Views',
    cachePath: __DIR__ . '/storage/cache/views',
    debug: true // Für Entwicklung aktivieren
);

// Globale Variablen für alle Templates
$view->shareAll([
    'appName' => 'Brick Framework Demo',
    'version' => '1.0.0',
    'debug' => true,
    'currentYear' => date('Y')
]);

// HTTP-Komponenten
$request = new Request();
$response = new Response();
$router = new Router();

// Route für Homepage
$router->get('/', function() use ($view, $response) {
    // View-Statistiken für die Homepage
    $stats = $view->getStats();
    
    $html = $view->render('home', [
        'title' => 'Willkommen bei Brick Framework',
        'stats' => $stats,
        'features' => [
            [
                'icon' => '🚀',
                'title' => 'Blitzschnell',
                'description' => 'O(1) Routing und optimierte Performance'
            ],
            [
                'icon' => '🎨', 
                'title' => 'Schöne Templates',
                'description' => 'Blade-ähnliche Syntax mit Layouts'
            ],
            [
                'icon' => '🛡️',
                'title' => 'Sicher',
                'description' => 'Automatisches Escaping und Sicherheitsfeatures'
            ]
        ]
    ]);
    
    return $response->html($html);
});

// Route für About-Seite
$router->get('/about', function() use ($view, $response) {
    $html = $view->render('about', [
        'stats' => $view->getStats()
    ]);
    
    return $response->html($html);
});

// API-Route für View-Statistiken
$router->get('/api/view-stats', function() use ($view, $response) {
    return $response->json([
        'stats' => $view->getStats(),
        'debug_info' => $view->getDebugInfo()
    ]);
});

// Route für Template-Demo
$router->get('/demo/{template}', function($params) use ($view, $response) {
    $templateName = $params['template'];
    
    try {
        // Beispiel-Daten für verschiedene Templates
        $demoData = [
            'users' => [
                ['name' => 'Max Mustermann', 'email' => 'max@example.com', 'role' => 'Admin'],
                ['name' => 'Anna Schmidt', 'email' => 'anna@example.com', 'role' => 'User'],
                ['name' => 'Tom Weber', 'email' => 'tom@example.com', 'role' => 'Editor']
            ],
            'products' => [
                ['name' => 'Smartphone', 'price' => 599.99, 'category' => 'Electronics'],
                ['name' => 'Laptop', 'price' => 1299.99, 'category' => 'Electronics'],
                ['name' => 'Buch', 'price' => 19.99, 'category' => 'Books']
            ],
            'message' => 'Das ist eine Demo-Nachricht!',
            'showAlert' => true,
            'user' => ['name' => 'Demo User', 'isAdmin' => true]
        ];
        
        $html = $view->render($templateName, $demoData);
        return $response->html($html);
        
    } catch (Exception $e) {
        return $response->setStatusCode(404)->json([
            'error' => 'Template nicht gefunden',
            'template' => $templateName,
            'message' => $e->getMessage(),
            'available_templates' => $view->getDebugInfo()['available_templates'] ?? []
        ]);
    }
});

// Route für Cache-Management
$router->post('/admin/clear-cache', function() use ($view, $response) {
    $view->clearCache();
    
    return $response->json([
        'message' => 'Template-Cache geleert',
        'stats' => $view->getStats()
    ]);
});

// Fehlerbehandlung für nicht gefundene Routen
$router->get('/.*', function() use ($view, $response) {
    $html = $view->render('errors.404', [
        'requestUri' => $_SERVER['REQUEST_URI'] ?? '/',
        'suggestions' => [
            '/' => 'Homepage',
            '/about' => 'Über uns',
            '/demo/home' => 'Template Demo'
        ]
    ]);
    
    return $response->setStatusCode(404)->html($html);
});

// Request verarbeiten
$result = $router->dispatch($request->method(), $request->uri());

if ($result['found']) {
    try {
        $result['handler']($result['params'] ?? []);
    } catch (Exception $e) {
        // Fehlerseite mit View-System rendern
        $html = $view->render('errors.500', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        $response->setStatusCode(500)->html($html);
    }
} else {
    // 404-Fehlerseite
    $html = $view->render('errors.404', [
        'requestUri' => $request->uri()
    ]);
    
    $response->setStatusCode(404)->html($html);
}

// Performance-Statistiken ausgeben (nur in Debug-Modus)
if ($view->getDebugInfo()['debug_mode']) {
    $stats = $view->getStats();
    error_log("🎨 View Performance: " . json_encode($stats));
}
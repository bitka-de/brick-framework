<?php

/**
 * 🛤️ Application Routes
 * 
 * Zentrale Routing-Konfiguration für das Brick Framework.
 * Alle Routen werden hier definiert und vom Bootstrap geladen.
 * 
 * @package App
 * @author Jan P. Behrens <jp@bitka.de>
 * @since 29.09.2025
 */

use Brick\Core\Router;
use Brick\Http\Request;
use Brick\Http\Response;
use App\Controllers\HomeController;

/**
 * Routen registrieren
 * 
 * @param Router $router Router-Instanz
 * @param Request $request Request-Instanz  
 * @param Response $response Response-Instanz
 * @return void
 */
return function(Router $router, Request $request, Response $response): void {
    
    // ============================================
    // CONTROLLER INSTANZEN
    // ============================================
    
    $homeController = new HomeController();
    
    // ============================================
    // WEB ROUTES - FRONTEND
    // ============================================
    
    // Homepage - TEMPORÄR OHNE LAYOUT
    $router->get('/', function() use ($homeController, $request, $response) {
        // Temporärer Fix: Content direkt rendern ohne Layout-System
        $view = new \Brick\Core\View(
            viewPath: __DIR__ . '/Views',
            cachePath: sys_get_temp_dir() . '/brick_views',
            debug: true
        );
        
        $stats = $view->getStats();
        

        $view = new \Brick\Core\View(
            viewPath: __DIR__ . '/Views',
            cachePath: sys_get_temp_dir() . '/brick_views',
            debug: true
        );
        
         $view->shareAll([
            'appName' => $_ENV['APP_NAME'] ?? 'Brick Framework',
            'version' => '1.0.0',
            'debug' => $_ENV['APP_DEBUG'] ?? true,
            'currentYear' => date('Y')
        ]);

        return $response->html($view->render('home', [
            'stats' => $stats
        ]));
    });
    
    // Über uns
    $router->get('/about', function() use ($response) {
        $view = new \Brick\Core\View(
            viewPath: __DIR__ . '/Views',
            cachePath: sys_get_temp_dir() . '/brick_views',
            debug: true
        );
        
        $view->shareAll([
            'appName' => $_ENV['APP_NAME'] ?? 'Brick Framework',
            'version' => '1.0.0',
            'debug' => $_ENV['APP_DEBUG'] ?? true,
            'currentYear' => date('Y')
        ]);

        $stats = $view->getStats();

        return $response->html($view->render('about', [
            'stats' => $stats
        ]));
    });
    
    // Include Test Route (temporär)
    $router->get('/test/includes', function() use ($response) {
        $view = new \Brick\Core\View(
            viewPath: __DIR__ . '/Views',
            cachePath: sys_get_temp_dir() . '/brick_views',
            debug: true
        );
        
        $view->shareAll([
            'appName' => 'Brick Framework',
            'currentYear' => date('Y')
        ]);

        return $response->html($view->render('include-test', []));
    });
    
    // Kontakt
    $router->get('/contact', function() use ($homeController, $request) {
        return $homeController->contact($request);
    });
    
    // ============================================
    // API ROUTES - JSON ENDPOINTS
    // ============================================
    
    // Framework-Statistiken
    $router->get('/api/stats', function() use ($homeController, $request) {
        return $homeController->apiStats($request);
    });
    
    // System-Info (erweiterte Statistiken)
    $router->get('/api/info', function() use ($response) {
        return $response->json([
            'framework' => 'Brick Framework',
            'version' => '1.0.0',
            'php_version' => PHP_VERSION,
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'timezone' => date_default_timezone_get(),
            'timestamp' => time(),
            'date' => date('Y-m-d H:i:s'),
            'status' => 'running'
        ]);
    });
    
    // Health Check für Monitoring
    $router->get('/api/health', function() use ($response) {
        return $response->json([
            'status' => 'healthy',
            'uptime' => time() - $_SERVER['REQUEST_TIME'],
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true)
        ]);
    });
    
    // ============================================
    // DEMO ROUTES - ENTWICKLUNG & TESTS
    // ============================================
    
    // View-System Demo
    $router->get('/demo/templates', function() use ($homeController, $request) {
        // Demo-Template mit verschiedenen Features
        return $homeController->demo($request);
    });
    
    // Router-Debug (nur in Entwicklung)
    $router->get('/debug/routes', function() use ($router, $response) {
        if (!($_ENV['APP_DEBUG'] ?? true)) {
            return $response->status(404)->html('<h1>404 - Not Found</h1>');
        }
        
        ob_start();
        $router->dumpRoutes();
        $routesDump = ob_get_clean();
        
        return $response->html("
            <h1>🛤️ Registered Routes</h1>
            <pre>{$routesDump}</pre>
            <hr>
            <h2>📊 Router Statistics</h2>
            <pre>" . print_r($router->getStats(), true) . "</pre>
        ");
    });
    
    // ============================================
    // ERROR HANDLING ROUTES
    // ============================================
    
    // 404 Error Handler (Catch-all)
    $router->get('/.*', function() use ($response, $request) {
        $requestUri = $request->getUri();
        
        // JSON Response für API-Calls
        if (strpos($requestUri, '/api/') === 0) {
            return $response->status(404)->json([
                'error' => 'Endpoint not found',
                'path' => $requestUri,
                'method' => $request->getMethod(),
                'available_endpoints' => [
                    '/api/stats',
                    '/api/info', 
                    '/api/health'
                ]
            ]);
        }
        
        // HTML Response für Web-Anfragen
        return $response->status(404)->html('
            <!DOCTYPE html>
            <html lang="de">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>404 - Seite nicht gefunden</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body>
                <div class="container mt-5">
                    <div class="row justify-content-center">
                        <div class="col-md-6 text-center">
                            <h1 class="display-1">404</h1>
                            <h2>Seite nicht gefunden</h2>
                            <p class="lead">Die angeforderte Seite <code>' . htmlspecialchars($requestUri) . '</code> existiert nicht.</p>
                            <hr>
                            <a href="/" class="btn btn-primary">🏠 Zur Startseite</a>
                            <a href="/about" class="btn btn-outline-secondary">ℹ️ Über uns</a>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        ');
    });
};

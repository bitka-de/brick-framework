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

use Brick\Core\{Router, View};
use Brick\Http\{Request, Response};
use App\Controllers\{HomeController, DocumentationController};

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
        $view = new View(
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

        return $response->html($view->render('home', [
            'stats' => $stats
        ]));
    });
    
    // Über uns
    $router->get('/about', function() use ($response) {
        $view = new View(
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
        $view = new View(
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
    
    // ============================
    // Dokumentations-Routen
    // ============================
    
    // Test-Route für Debugging
    $router->get('/docs-test', function() use ($response) {
        return $response->html('<h1>Test: Docs Route funktioniert!</h1>');
    });
    
    // Dokumentations-Hauptseite
    $router->get('/docs', function() use ($response) {
        error_log('DOCS ROUTE HIT!');
        try {
            $controller = new DocumentationController();
            $content = $controller->index();
            
            if (empty($content)) {
                error_log('DOCS ROUTE: Empty content!');
                return $response->html('<h1>ERROR: Empty content from controller</h1>');
            }
            
            error_log('DOCS ROUTE: Content length: ' . strlen($content));
            return $response->html($content);
        } catch (Exception $e) {
            error_log('DOCS ROUTE ERROR: ' . $e->getMessage());
            return $response->html('<h1>ERROR: ' . $e->getMessage() . '</h1><pre>' . $e->getTraceAsString() . '</pre>');
        }
    });
    
    // Dokumentations-Unterseiten
    $router->get('/docs/overview', function() use ($response) {
        error_log('DOCS OVERVIEW ROUTE HIT!');
        try {
            $controller = new DocumentationController();
            $content = $controller->overview();
            
            if (empty($content)) {
                error_log('DOCS OVERVIEW: Empty content!');
                return $response->html('<h1>ERROR: Empty content from overview controller</h1>');
            }
            
            error_log('DOCS OVERVIEW: Content length: ' . strlen($content));
            return $response->html($content);
        } catch (Exception $e) {
            error_log('DOCS OVERVIEW ERROR: ' . $e->getMessage());
            return $response->html('<h1>ERROR: ' . $e->getMessage() . '</h1><pre>' . $e->getTraceAsString() . '</pre>');
        }
    });
    
    $router->get('/docs/installation', function() use ($response) {
        error_log('DOCS INSTALLATION ROUTE HIT!');
        try {
            $controller = new DocumentationController();
            $content = $controller->installation();
            
            if (empty($content)) {
                error_log('DOCS INSTALLATION: Empty content!');
                return $response->html('<h1>ERROR: Empty content from installation controller</h1>');
            }
            
            error_log('DOCS INSTALLATION: Content length: ' . strlen($content));
            return $response->html($content);
        } catch (Exception $e) {
            error_log('DOCS INSTALLATION ERROR: ' . $e->getMessage());
            return $response->html('<h1>ERROR: ' . $e->getMessage() . '</h1><pre>' . $e->getTraceAsString() . '</pre>');
        }
    });
    
    $router->get('/docs/routing', function() use ($response) {
        error_log('DOCS ROUTING ROUTE HIT!');
        try {
            $controller = new DocumentationController();
            $content = $controller->routing();
            
            if (empty($content)) {
                error_log('DOCS ROUTING: Empty content!');
                return $response->html('<h1>ERROR: Empty content from routing controller</h1>');
            }
            
            error_log('DOCS ROUTING: Content length: ' . strlen($content));
            return $response->html($content);
        } catch (Exception $e) {
            error_log('DOCS ROUTING ERROR: ' . $e->getMessage());
            return $response->html('<h1>ERROR: ' . $e->getMessage() . '</h1><pre>' . $e->getTraceAsString() . '</pre>');
        }
    });
    
    $router->get('/docs/views', function() use ($response) {
        error_log('DOCS VIEWS ROUTE HIT!');
        try {
            $controller = new DocumentationController();
            $content = $controller->views();
            
            if (empty($content)) {
                error_log('DOCS VIEWS: Empty content!');
                return $response->html('<h1>ERROR: Empty content from views controller</h1>');
            }
            
            error_log('DOCS VIEWS: Content length: ' . strlen($content));
            return $response->html($content);
        } catch (Exception $e) {
            error_log('DOCS VIEWS ERROR: ' . $e->getMessage());
            return $response->html('<h1>ERROR: ' . $e->getMessage() . '</h1><pre>' . $e->getTraceAsString() . '</pre>');
        }
    });
    
    $router->get('/docs/css-js', function() use ($response) {
        error_log('DOCS CSS-JS ROUTE HIT!');
        try {
            $controller = new DocumentationController();
            $content = $controller->cssJs();
            
            if (empty($content)) {
                error_log('DOCS CSS-JS: Empty content!');
                return $response->html('<h1>ERROR: Empty content from cssJs controller</h1>');
            }
            
            error_log('DOCS CSS-JS: Content length: ' . strlen($content));
            return $response->html($content);
        } catch (Exception $e) {
            error_log('DOCS CSS-JS ERROR: ' . $e->getMessage());
            return $response->html('<h1>ERROR: ' . $e->getMessage() . '</h1><pre>' . $e->getTraceAsString() . '</pre>');
        }
    });
    
    $router->get('/docs/middleware', function() use ($response) {
        error_log('DOCS MIDDLEWARE ROUTE HIT!');
        try {
            $controller = new DocumentationController();
            $content = $controller->middleware();
            
            if (empty($content)) {
                error_log('DOCS MIDDLEWARE: Empty content!');
                return $response->html('<h1>ERROR: Empty content from middleware controller</h1>');
            }
            
            error_log('DOCS MIDDLEWARE: Content length: ' . strlen($content));
            return $response->html($content);
        } catch (Exception $e) {
            error_log('DOCS MIDDLEWARE ERROR: ' . $e->getMessage());
            return $response->html('<h1>ERROR: ' . $e->getMessage() . '</h1><pre>' . $e->getTraceAsString() . '</pre>');
        }
    });
    
    $router->get('/docs/database', function() use ($response) {
        error_log('DOCS DATABASE ROUTE HIT!');
        try {
            $controller = new DocumentationController();
            $content = $controller->database();
            
            if (empty($content)) {
                error_log('DOCS DATABASE: Empty content!');
                return $response->html('<h1>ERROR: Empty content from database controller</h1>');
            }
            
            error_log('DOCS DATABASE: Content length: ' . strlen($content));
            return $response->html($content);
        } catch (Exception $e) {
            error_log('DOCS DATABASE ERROR: ' . $e->getMessage());
            return $response->html('<h1>ERROR: ' . $e->getMessage() . '</pre>');
        }
    });
    
    $router->get('/docs/api', function() use ($response) {
        error_log('DOCS API ROUTE HIT!');
        try {
            $controller = new DocumentationController();
            $content = $controller->api();
            
            if (empty($content)) {
                error_log('DOCS API: Empty content!');
                return $response->html('<h1>ERROR: Empty content from api controller</h1>');
            }
            
            error_log('DOCS API: Content length: ' . strlen($content));
            return $response->html($content);
        } catch (Exception $e) {
            error_log('DOCS API ERROR: ' . $e->getMessage());
            return $response->html('<h1>ERROR: ' . $e->getMessage() . '</h1><pre>' . $e->getTraceAsString() . '</pre>');
        }
    });
    
    // ============================
    // Test-Routen für CSS/JS
    // ============================
    
    // View-System Demo
    $router->get('/demo/templates', function() use ($homeController, $request) {
        // Demo-Template mit verschiedenen Features
        return $homeController->demo($request);
    });
    
    // CSS Test Route
    $router->get('/test/css', function() use ($response) {
        $view = new View(
            viewPath: __DIR__ . '/Views',
            cachePath: sys_get_temp_dir() . '/brick_views',
            debug: true
        );
        
        $view->shareAll([
            'app_name' => 'Brick Framework',
            'version' => '1.0.0'
        ]);
        
        echo $view->render('css-test', [], true);
    });
    
    // CSS Inline Test Route
    $router->get('/test/css-inline', function() use ($response) {
        $view = new View(
            viewPath: __DIR__ . '/Views',
            cachePath: sys_get_temp_dir() . '/brick_views',
            debug: true
        );
        
        $view->shareAll([
            'app_name' => 'Brick Framework',
            'version' => '1.0.0'
        ]);
        
        echo $view->render('css-inline-test', [], true);
    });
    
    // JavaScript Test Route
    $router->get('/test/js', function() use ($response) {
        $view = new View(
            viewPath: __DIR__ . '/Views',
            cachePath: sys_get_temp_dir() . '/brick_views',
            debug: true
        );
        
        $view->shareAll([
            'app_name' => 'Brick Framework',
            'version' => '1.0.0'
        ]);
        
        echo $view->render('js-test', [], true);
    });
    
    // CSS & JavaScript Direktiven Demo
    $router->get('/demo/css-js', function() use ($response) {
        $view = new View(
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

        return $response->html($view->render('css-js-demo', [
            'title' => 'CSS & JavaScript Demo',
            'showDemo' => true
        ]));
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

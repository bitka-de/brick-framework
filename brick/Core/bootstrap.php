<?php

/**
 * bootstrap.php
 *
 * Bootstrap-Datei zur Initialisierung des Brick-Frameworks.
 *
 * @author  Jan P. Behrens <jp@bitka.de>
 * @version 1.0
 */
 
// Autoloader einbinden
require_once __DIR__ . '/Autoloader.php';

// Autoloader initialisieren
$autoloader = new Brick\Core\Autoloader();
$autoloader->addNamespace('App', __DIR__ . '/../../app');  // App Namespace
$autoloader->addNamespace('Brick', __DIR__ . '/..');            // Core Namespace
$autoloader->register();

// Framework-Komponenten laden
use Brick\Http\Request;
use Brick\Http\Response;
use Brick\Core\Router;

try {
    // HTTP-Komponenten initialisieren
    $request = new Request();
    $response = new Response();
    $router = new Router();
    
    // Routen aus separater Datei laden
    $routesFile = __DIR__ . '/../../app/routes.php';
    if (file_exists($routesFile)) {
        $routeDefinitions = require $routesFile;
        if (is_callable($routeDefinitions)) {
            $routeDefinitions($router, $request, $response);
        }
    } else {
        throw new Exception("Routes file not found: $routesFile");
    }
    
    // Request verarbeiten
    $result = $router->dispatch($request->getMethod(), $request->getUri());
    
    if ($result['found']) {
        $handlerResult = $result['handler']($result['params'] ?? []);
        
        // Response senden
        if ($handlerResult instanceof Brick\Http\Response) {
            $handlerResult->send();
        }
    } else {
        // Fallback 404
        $response->status(404)->html('<h1>404 - Nicht gefunden</h1>')->send();
    }
    
} catch (Exception $e) {
    // Fehlerbehandlung
    $response = $response ?? new Brick\Http\Response();
    $response->status(500)->html("
        <h1>Interner Serverfehler</h1>
        <p>Es ist ein Fehler aufgetreten: " . htmlspecialchars($e->getMessage()) . "</p>
        <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>
    ");
    
    if (php_sapi_name() === 'cli') {
        echo "💥 Exception: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString() . "\n";
    }
}
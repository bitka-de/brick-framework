<?php

declare(strict_types=1);
namespace App\Controllers;

use Brick\Core\View;
use Brick\Http\Request;
use Brick\Http\Response;

/**
 * 🏠 Home Controller
 * 
 * Verwaltet die Hauptseiten der Anwendung:
 * - Homepage
 * - Über uns
 * - Kontakt
 * 
 * @package App\Controllers
 * @since 29.09.2025
 */
class HomeController
{
    private View $view;
    private Response $response;

    public function __construct()
    {
        // View-System initialisieren
        $this->view = new View(
            viewPath: __DIR__ . '/../Views',
            cachePath: sys_get_temp_dir() . '/brick_views',
            debug: true  // Cache deaktiviert für Entwicklung
        );
        
        $this->response = new Response();
        
        // Globale Template-Variablen setzen
        $this->view->shareAll([
            'appName' => $_ENV['APP_NAME'] ?? 'Brick Framework',
            'version' => '1.0.0',
            'debug' => $_ENV['APP_DEBUG'] ?? true,
            'currentYear' => date('Y')
        ]);
    }

    /**
     * Homepage anzeigen
     * 
     * @param Request $request HTTP-Request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // View-Statistiken für die Homepage
        $stats = $this->view->getStats();
        
        // Features für die Homepage
        $features = [
            [
                'icon' => '🚀',
                'title' => 'Blitzschnell',
                'description' => 'O(1) Routing für statische Routen und optimierte Template-Engine mit intelligentem Caching.'
            ],
            [
                'icon' => '🎨', 
                'title' => 'Blade-Templates',
                'description' => 'Vertraute Syntax mit @extends, @section, @yield und automatischem Escaping für sichere Ausgabe.'
            ],
            [
                'icon' => '🛡️',
                'title' => 'Sicherheit first',
                'description' => 'Eingebaute XSS-Protection, CSRF-Schutz und Sicherheitsheader für moderne Webanwendungen.'
            ]
        ];

        // Homepage rendern
        $html = $this->view->render('home', [
            'title' => 'Willkommen bei Brick Framework',
            'stats' => $stats,
            'features' => $features,
            'showWelcome' => true,
            'userName' => $request->session('user_name', 'Entwickler'),
            'flash_message' => $request->session('flash_message'),
            'flash_type' => $request->session('flash_type', 'info')
        ]);
        
        return $this->response->html($html);
    }

    /**
     * Über uns Seite
     * 
     * @param Request $request HTTP-Request
     * @return Response
     */
    public function about(Request $request): Response
    {
        $stats = $this->view->getStats();
        
        $html = $this->view->render('about', [
            'title' => 'Über das Brick Framework',
            'stats' => $stats
        ]);
        
        return $this->response->html($html);
    }

    /**
     * Kontakt-Seite
     * 
     * @param Request $request HTTP-Request
     * @return Response
     */
    public function contact(Request $request): Response
    {
        $html = $this->view->render('contact', [
            'title' => 'Kontakt'
        ]);
        
        return $this->response->html($html);
    }

    /**
     * API-Endpoint für Framework-Statistiken
     * 
     * @param Request $request HTTP-Request
     * @return Response
     */
    public function apiStats(Request $request): Response
    {
        $stats = array_merge($this->view->getStats(), [
            'framework_version' => '1.0.0',
            'php_version' => PHP_VERSION,
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true)
        ]);
        
        return $this->response->json([
            'framework' => 'Brick Framework',
            'status' => 'running',
            'stats' => $stats
        ]);
    }

    /**
     * Demo-Seite für Template-Features
     * 
     * @param Request $request HTTP-Request
     * @return Response
     */
    public function demo(Request $request): Response
    {
        $demoData = [
            'users' => [
                ['name' => 'Max Mustermann', 'email' => 'max@example.com', 'role' => 'Admin', 'active' => true],
                ['name' => 'Anna Schmidt', 'email' => 'anna@example.com', 'role' => 'Editor', 'active' => true],
                ['name' => 'Tom Weber', 'email' => 'tom@example.com', 'role' => 'User', 'active' => false]
            ],
            'products' => [
                ['name' => 'Smartphone Pro', 'price' => 899.99, 'category' => 'Electronics', 'stock' => 15],
                ['name' => 'Gaming Laptop', 'price' => 1599.99, 'category' => 'Electronics', 'stock' => 3],
                ['name' => 'Programming Book', 'price' => 49.99, 'category' => 'Books', 'stock' => 28]
            ],
            'showAlert' => true,
            'alertMessage' => 'Dies ist eine Demo-Seite für das Brick Framework Template-System!'
        ];
        
        $html = $this->view->render('demo', array_merge($demoData, [
            'title' => 'Template Demo'
        ]));
        
        return $this->response->html($html);
    }
}

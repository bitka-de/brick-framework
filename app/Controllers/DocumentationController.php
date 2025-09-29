<?php

namespace App\Controllers;

use Brick\Core\View;

/**
 * DocumentationController
 * 
 * Verwaltet die Framework-Dokumentation und stellt eine vollständige
 * interaktive Dokumentation für das Brick Framework bereit.
 */
class DocumentationController
{
    private View $view;
    
    public function __construct()
    {
        $this->view = new View(
            viewPath: __DIR__ . '/../Views',
            cachePath: sys_get_temp_dir() . '/brick_views',
            debug: true
        );
        
        $this->view->shareAll([
            'app_name' => 'Brick Framework',
            'version' => '1.0.0',
            'current_section' => 'documentation'
        ]);
    }
    
    /**
     * Dokumentations-Hauptseite
     */
    public function index()
    {
        $sections = [
            'overview' => [
                'title' => 'Framework Übersicht',
                'description' => 'Einführung in das Brick Framework',
                'icon' => '🧱'
            ],
            'installation' => [
                'title' => 'Installation',
                'description' => 'Setup und Konfiguration',
                'icon' => '⚙️'
            ],
            'routing' => [
                'title' => 'Routing System',
                'description' => 'URL-Routing und Route-Definitionen',
                'icon' => '🛣️'
            ],
            'views' => [
                'title' => 'View System',
                'description' => 'Templates und Blade-ähnliche Direktiven',
                'icon' => '🎨'
            ],
            'css-js' => [
                'title' => 'CSS/JS Direktiven',
                'description' => 'Asset-Management mit @css und @js',
                'icon' => '💎'
            ],
            'middleware' => [
                'title' => 'Middleware',
                'description' => 'Request/Response-Verarbeitung',
                'icon' => '🔄'
            ],
            'database' => [
                'title' => 'Database',
                'description' => 'Datenbankverbindungen und Queries',
                'icon' => '🗄️'
            ],
            'api' => [
                'title' => 'API Referenz',
                'description' => 'Vollständige Klassen- und Methoden-Referenz',
                'icon' => '📚'
            ]
        ];
        
        return $this->view->render('docs/index', [
            'sections' => $sections,
            'page_title' => 'Dokumentation'
        ], true);
    }
    
    /**
     * Framework Übersicht
     */
    public function overview()
    {
        return $this->view->render('docs/overview', [
            'page_title' => 'Framework Übersicht'
        ], true);
    }
    
    /**
     * Installations-Anleitung
     */
    public function installation()
    {
        return $this->view->render('docs/installation', [
            'page_title' => 'Installation'
        ], true);
    }
    
    /**
     * Routing-Dokumentation
     */
    public function routing()
    {
        return $this->view->render('docs/routing', [
            'page_title' => 'Routing System'
        ], true);
    }
    
    /**
     * View-System-Dokumentation
     */
    public function views()
    {
        return $this->view->render('docs/views', [
            'page_title' => 'View System'
        ], true);
    }
    
    /**
     * CSS/JS-Direktiven-Dokumentation
     */
    public function cssJs()
    {
        return $this->view->render('docs/css-js', [
            'page_title' => 'CSS/JS Direktiven'
        ], true);
    }
    
    /**
     * Middleware-Dokumentation
     */
    public function middleware()
    {
        return $this->view->render('docs/middleware', [
            'page_title' => 'Middleware'
        ], true);
    }
    
    /**
     * Database-Dokumentation
     */
    public function database()
    {
        return $this->view->render('docs/database', [
            'page_title' => 'Database'
        ], true);
    }
    
    /**
     * API-Referenz
     */
    public function api()
    {
        return $this->view->render('docs/api', [
            'page_title' => 'API Referenz'
        ], true);
    }
}
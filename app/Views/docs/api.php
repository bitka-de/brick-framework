@extends('docs')

@section('title', 'API Referenz')

@section('content')
<h1>📚 API Referenz</h1>

<div class="table-of-contents">
    <h5>🗺️ Inhaltsübersicht</h5>
    <ul>
        <li><a href="#core-klassen">Core-Klassen</a></li>
        <li><a href="#view-system">View System</a></li>
        <li><a href="#routing">Routing</a></li>
        <li><a href="#http">HTTP-Komponenten</a></li>
        <li><a href="#utilities">Utilities</a></li>
        <li><a href="#examples">Code-Beispiele</a></li>
    </ul>
</div>

<h2 id="core-klassen">🏠 Core-Klassen</h2>

<h3>Brick\Core\View</h3>

<p>Die Haupt-Template-Engine des Frameworks mit Blade-ähnlicher Syntax.</p>

<div class="code-block">
<pre><code class="language-php">
namespace Brick\Core;

class View
{
    /**
     * View-System initialisieren
     * 
     * Parameter:
     * - string viewPath: Pfad zu den Template-Dateien
     * - string|null cachePath: Cache-Pfad (null = temp)
     * - bool debug: Debug-Modus aktivieren
     */
    public function __construct(
        string $viewPath,
        ?string $cachePath = null,
        bool $debug = false
    );
    
    /**
     * Template rendern
     * 
     * Parameter:
     * - string template: Template-Name (ohne .php)
     * - array data: Daten für das Template
     * - bool return: Ausgabe zurückgeben statt direkt ausgeben
     * 
     * Return: string|null
     */
    public function render(
        string $template, 
        array $data = [], 
        bool $return = false
    ): ?string;
    
    /**
     * Globale Variablen setzen
     * 
     * Parameter:
     * - array globals: Globale Variablen
     */
    public function setGlobals(array $globals): void;
    
    /**
     * Variable hinzufügen
     * 
     * Parameter:
     * - string key: Variablen-Name
     * - mixed value: Wert
     */
    public function addVariable(string $key, $value): void;
    
    /**
     * Cache-Statistiken abrufen
     * 
     * Return: array Statistiken
     */
    public function getCacheStats(): array;
}
</code></pre>
</div>

<h3>Brick\Core\Router</h3>

<p>Das Routing-System für URL-Verarbeitung und Controller-Mapping.</p>

<div class="code-block">
<pre><code class="language-php">
namespace Brick\Core;

class Router
{
    /**
     * Router initialisieren
     */
    public function __construct();
    
    /**
     * GET-Route registrieren
     * 
     * Parameter:
     * - string path: URL-Pfad
     * - callable|array handler: Handler-Funktion oder Controller
     * 
     * Return: Route-Objekt
     */
    public function get(string $path, $handler): Route;
    
    /**
     * POST-Route registrieren
     */
    public function post(string $path, $handler): Route;
    
    /**
     * PUT-Route registrieren
     */
    public function put(string $path, $handler): Route;
    
    /**
     * DELETE-Route registrieren
     */
    public function delete(string $path, $handler): Route;
    
    /**
     * Route-Gruppe definieren
     * 
     * Parameter:
     * - array attributes: Gruppen-Attribute (prefix, middleware)
     * - callable callback: Callback für Routen-Definition
     */
    public function group(array $attributes, callable $callback): void;
    
    /**
     * Request verarbeiten
     * 
     * Parameter:
     * - string method: HTTP-Methode
     * - string uri: Request-URI
     * 
     * Return: Response
     */
    public function dispatch(string $method, string $uri): Response;
}
</code></pre>
</div>

<h2 id="view-system">🎨 View System</h2>

<h3>Template-Erstellung</h3>

<p>Beispiel für die Verwendung des View-Systems:</p>

<div class="code-block">
<pre><code class="language-php">
// Controller-Beispiel
namespace App\Controllers;

use Brick\Core\View;
use Brick\Core\Response;

class HomeController
{
    private $view;
    
    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../Views');
    }
    
    public function index()
    {
        $data = [
            'title' => 'Willkommen',
            'users' => $this->getUsers(),
            'stats' => $this->getStats()
        ];
        
        return $this->view->render('home/index', $data, true);
    }
    
    public function show($id)
    {
        $user = $this->findUser($id);
        
        if (!$user) {
            return new Response('Benutzer nicht gefunden', 404);
        }
        
        return $this->view->render('home/show', compact('user'), true);
    }
}
</code></pre>
</div>

<h3>Template-Syntax</h3>

<p>Übersicht der verfügbaren Template-Direktiven:</p>

<div class="code-block">
<pre><code class="language-blade">
<!-- Template-Vererbung -->
<!-- extends('layouts/app') -->

<!-- Bereiche definieren -->
<!-- section('content') -->
    Ihr Inhalt hier
<!-- endsection -->

<!-- Variablen ausgeben -->
{{ $variable }}
{!! $htmlContent !!}

<!-- Kontrollstrukturen -->
<!-- if($condition) -->
    Bedingter Inhalt
<!-- endif -->

<!-- Schleifen -->
<!-- foreach($items as $item) -->
    <div>{{ $item }}</div>
<!-- endforeach -->

<!-- Includes -->
<!-- include('partials/header') -->
<!-- include('partials/user', ['user' => $currentUser]) -->
</code></pre>
</div>

<h2 id="routing">🛣️ Routing</h2>

<h3>Route-Definition</h3>

<p>Verschiedene Arten von Routen definieren:</p>

<div class="code-block">
<pre><code class="language-php">
// Einfache Routen
$router->get('/', function() {
    return 'Homepage';
});

$router->post('/users', [UserController::class, 'store']);

// Parameter-Routen
$router->get('/user/{id}', function($id) {
    return "Benutzer: " . $id;
});

$router->get('/post/{slug}', [PostController::class, 'show']);

// Optionale Parameter
$router->get('/search/{term?}', [SearchController::class, 'search']);

// Route-Gruppen
$router->group(['prefix' => 'api'], function($router) {
    $router->get('/users', [ApiController::class, 'users']);
    $router->get('/posts', [ApiController::class, 'posts']);
});

// Middleware
$router->get('/admin', [AdminController::class, 'index'])
       ->middleware('auth');

// Parameter-Constraints
$router->get('/user/{id}', [UserController::class, 'show'])
       ->where('id', '[0-9]+');
</code></pre>
</div>

<h2 id="http">🌐 HTTP-Komponenten</h2>

<h3>Response-Klasse</h3>

<p>HTTP-Responses erstellen und verwalten:</p>

<div class="code-block">
<pre><code class="language-php">
namespace Brick\Core;

class Response
{
    /**
     * Response erstellen
     * 
     * Parameter:
     * - string content: Response-Inhalt
     * - int status: HTTP-Status-Code
     * - array headers: HTTP-Headers
     */
    public function __construct(
        string $content = '', 
        int $status = 200, 
        array $headers = []
    );
    
    /**
     * JSON-Response erstellen
     * 
     * Parameter:
     * - mixed data: Daten für JSON-Konvertierung
     * - int status: HTTP-Status-Code
     * 
     * Return: Response
     */
    public function json($data, int $status = 200): Response;
    
    /**
     * Redirect-Response erstellen
     * 
     * Parameter:
     * - string url: Ziel-URL
     * - int status: HTTP-Status-Code (default: 302)
     * 
     * Return: Response
     */
    public function redirect(string $url, int $status = 302): Response;
    
    /**
     * Header setzen
     * 
     * Parameter:
     * - string name: Header-Name
     * - string value: Header-Wert
     */
    public function setHeader(string $name, string $value): void;
    
    /**
     * Status-Code setzen
     * 
     * Parameter:
     * - int status: HTTP-Status-Code
     */
    public function setStatus(int $status): void;
}
</code></pre>
</div>

<h3>Request-Handling</h3>

<p>HTTP-Requests verarbeiten:</p>

<div class="code-block">
<pre><code class="language-php">
// Request-Daten abrufen
$_GET['parameter'];    // GET-Parameter
$_POST['field'];       // POST-Daten
$_FILES['upload'];     // Datei-Uploads
$_SERVER['HTTP_HOST']; // Server-Informationen

// Beispiel: Formular-Verarbeitung
class ContactController
{
    public function show()
    {
        return $this->view->render('contact/form');
    }
    
    public function submit()
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? '';
        
        // Validierung
        if (empty($name) || empty($email)) {
            return $this->view->render('contact/form', [
                'error' => 'Name und E-Mail sind erforderlich'
            ]);
        }
        
        // Verarbeitung
        $this->sendEmail($name, $email, $message);
        
        return $this->view->render('contact/success');
    }
}
</code></pre>
</div>

<h2 id="utilities">🔧 Utilities</h2>

<h3>Hilfsfunktionen</h3>

<p>Nützliche Hilfsfunktionen für die tägliche Entwicklung:</p>

<div class="code-block">
<pre><code class="language-php">
// Array-Helpers
function array_get(array $array, string $key, $default = null)
{
    return $array[$key] ?? $default;
}

function array_has(array $array, string $key): bool
{
    return isset($array[$key]);
}

// String-Helpers
function str_slug(string $title): string
{
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
}

function str_limit(string $value, int $limit = 100): string
{
    return strlen($value) > $limit ? substr($value, 0, $limit) . '...' : $value;
}

// Path-Helpers
function base_path(string $path = ''): string
{
    return __DIR__ . '/../' . ltrim($path, '/');
}

function public_path(string $path = ''): string
{
    return base_path('public/' . ltrim($path, '/'));
}

// URL-Helpers
function url(string $path = ''): string
{
    $baseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
    return $baseUrl . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url($path);
}
</code></pre>
</div>

<h2 id="examples">🚀 Code-Beispiele</h2>

<h3>Vollständiges Beispiel: Blog-System</h3>

<p>Ein komplettes Beispiel für ein einfaches Blog-System:</p>

<div class="code-block">
<pre><code class="language-php">
// routes.php
$router->get('/', [BlogController::class, 'index']);
$router->get('/post/{slug}', [BlogController::class, 'show']);
$router->get('/category/{category}', [BlogController::class, 'category']);

// Controller
class BlogController
{
    private $view;
    private $posts;
    
    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../Views');
        $this->posts = $this->loadPosts();
    }
    
    public function index()
    {
        $recentPosts = array_slice($this->posts, 0, 10);
        
        return $this->view->render('blog/index', [
            'posts' => $recentPosts,
            'title' => 'Blog'
        ], true);
    }
    
    public function show($slug)
    {
        $post = $this->findPostBySlug($slug);
        
        if (!$post) {
            return new Response('Post nicht gefunden', 404);
        }
        
        return $this->view->render('blog/show', [
            'post' => $post,
            'title' => $post['title']
        ], true);
    }
    
    public function category($category)
    {
        $posts = array_filter($this->posts, function($post) use ($category) {
            return $post['category'] === $category;
        });
        
        return $this->view->render('blog/category', [
            'posts' => $posts,
            'category' => $category,
            'title' => 'Kategorie: ' . $category
        ], true);
    }
    
    private function loadPosts(): array
    {
        // Hier würden Sie normalerweise aus einer Datenbank laden
        return [
            [
                'id' => 1,
                'title' => 'Erster Blog-Post',
                'slug' => 'erster-blog-post',
                'content' => 'Das ist der Inhalt...',
                'category' => 'Allgemein',
                'created_at' => '2023-01-01'
            ],
            // weitere Posts...
        ];
    }
    
    private function findPostBySlug(string $slug): ?array
    {
        foreach ($this->posts as $post) {
            if ($post['slug'] === $slug) {
                return $post;
            }
        }
        return null;
    }
}
</code></pre>
</div>

<h3>Template-Beispiele</h3>

<p>Entsprechende Templates für das Blog-System:</p>

<div class="code-block">
<pre><code class="language-blade">
<!-- blog/index.php -->
<!-- extends('layouts/app') -->

<!-- section('title', $title) -->

<!-- section('content') -->
<div class="container">
    <h1>{{ $title }}</h1>
    
    <div class="row">
        <!-- foreach($posts as $post) -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $post['title'] }}</h5>
                    <p class="card-text">{{ substr($post['content'], 0, 150) }}...</p>
                    <a href="/post/{{ $post['slug'] }}" class="btn btn-primary">Weiterlesen</a>
                </div>
            </div>
        </div>
        <!-- endforeach -->
    </div>
</div>
<!-- endsection -->

<!-- blog/show.php -->
<!-- extends('layouts/app') -->

<!-- section('title', $post['title']) -->

<!-- section('content') -->
<div class="container">
    <article>
        <h1>{{ $post['title'] }}</h1>
        <p class="text-muted">{{ $post['created_at'] }} | {{ $post['category'] }}</p>
        
        <div class="content">
            {!! nl2br($post['content']) !!}
        </div>
        
        <a href="/" class="btn btn-secondary">Zurück zur Übersicht</a>
    </article>
</div>
<!-- endsection -->
</code></pre>
</div>

<h2>📝 Best Practices</h2>

<div class="example-block">
    <h5>✅ API-Entwicklung</h5>
    <ul>
        <li><strong>Konsistente Struktur:</strong> Einheitliche Controller-Organisation</li>
        <li><strong>Error Handling:</strong> Umfassende Fehlerbehandlung</li>
        <li><strong>Validation:</strong> Input-Validierung in allen Controllern</li>
        <li><strong>Response Format:</strong> Konsistente JSON-Antworten</li>
    </ul>
</div>

<div class="warning-block">
    <h5>⚠️ Wichtige Hinweise</h5>
    <ul>
        <li>Controller sollten schlank bleiben - Logik in Services auslagern</li>
        <li>Templates nur für Präsentation verwenden</li>
        <li>Immer Input-Validierung durchführen</li>
        <li>HTTP-Status-Codes korrekt verwenden</li>
        <li>Security-Headers für alle Responses setzen</li>
    </ul>
</div>

<h2>🚀 Nächste Schritte</h2>

<div class="row">
    <div class="col-md-4">
        <a href="/docs/database" class="btn btn-primary w-100">🗄️ Database</a>
    </div>
    <div class="col-md-4">
        <a href="/docs/middleware" class="btn btn-outline-primary w-100">🛡️ Middleware</a>
    </div>
    <div class="col-md-4">
        <a href="/docs" class="btn btn-outline-secondary w-100">📖 Übersicht</a>
    </div>
</div>
@endsection

@section('prev-page')
<a href="/docs/database" class="btn btn-outline-secondary">
    ← Database
</a>
@endsection

@section('next-page')
<a href="/docs" class="btn btn-primary">
    Dokumentation →
</a>
@endsection
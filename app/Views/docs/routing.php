@extends('docs')

@section('title', 'Routing System')

@section('content')
<h1>🛣️ Routing System</h1>

<p class="lead">Das Brick Framework bietet ein mächtiges, flexibles Routing-System für moderne Webanwendungen.</p>

<div class="table-of-contents">
    <h5>🗺️ Inhaltsübersicht</h5>
    <ul>
        <li><a href="#grundlagen">Routing-Grundlagen</a></li>
        <li><a href="#http-verben">HTTP-Verben</a></li>
        <li><a href="#parameter">Route-Parameter</a></li>
        <li><a href="#gruppen">Route-Gruppen</a></li>
        <li><a href="#middleware">Middleware Integration</a></li>
        <li><a href="#controller">Controller-Routing</a></li>
        <li><a href="#beispiele">Praktische Beispiele</a></li>
    </ul>
</div>

<h2 id="grundlagen">🏗️ Routing-Grundlagen</h2>

<div class="example-block">
    <h5>✨ Hauptfeatures</h5>
    <ul>
        <li><strong>RESTful Routes:</strong> Vollständige Unterstützung für alle HTTP-Verben</li>
        <li><strong>Parameter-Binding:</strong> Dynamische URL-Parameter mit Typisierung</li>
        <li><strong>Middleware-Support:</strong> Route-spezifische und globale Middleware</li>
        <li><strong>Controller-Integration:</strong> Nahtlose Verbindung zu Controller-Methoden</li>
    </ul>
</div>

<h2 id="http-verben">📡 HTTP-Verben</h2>

<h3>Grundlegende Route-Definitionen</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

use Brick\Core\Router;

\$router = new Router();

// GET Route
\$router->get('/users', function() {
    return 'Liste aller Benutzer';
});

// POST Route
\$router->post('/users', function() {
    return 'Neuen Benutzer erstellen';
});

// PUT Route
\$router->put('/users/{id}', function(\$id) {
    return "Benutzer \$id aktualisieren";
});

// DELETE Route
\$router->delete('/users/{id}', function(\$id) {
    return "Benutzer \$id löschen";
});

// PATCH Route
\$router->patch('/users/{id}', function(\$id) {
    return "Benutzer \$id teilweise aktualisieren";
});</code></pre>
</div>

<h3>Closure-basierte Routen</h3>

<div class="code-block">
<pre><code class="language-php">\$router->get('/', function() {
    return 'Willkommen auf der Homepage!';
});

// JSON Response
\$router->get('/json', function() use (\$response) {
    return \$response->json(['message' => 'Hallo Welt']);
});

// View rendern
\$router->get('/about', function() {
    \$view = new \Brick\Core\View(__DIR__ . '/Views');
    return \$view->render('about');
});</code></pre>
</div>

<h2 id="parameter">🔗 Route-Parameter</h2>

<h3>Einfache Parameter</h3>

<div class="code-block">
<pre><code class="language-php">// Einzelner Parameter
\$router->get('/user/{id}', function(\$id) {
    return "Benutzer ID: \$id";
});

// Mehrere Parameter
\$router->get('/post/{category}/{slug}', function(\$category, \$slug) {
    return "Kategorie: \$category, Slug: \$slug";
});

// Optionale Parameter
\$router->get('/search/{term?}', function(\$term = null) {
    return \$term ? "Suche nach: \$term" : "Alle Ergebnisse";
});</code></pre>
</div>

<h3>Parameter-Constraints</h3>

<div class="code-block">
<pre><code class="language-php">// Numerische Parameter
\$router->get('/user/{id}', function(\$id) {
    return "Benutzer ID: \$id";
})->where('id', '[0-9]+');

// Alphabetische Parameter
\$router->get('/category/{name}', function(\$name) {
    return "Kategorie: \$name";
})->where('name', '[a-zA-Z]+');

// Mehrere Constraints
\$router->get('/post/{id}/{slug}', function(\$id, \$slug) {
    return "Post \$id: \$slug";
})->where(['id' => '[0-9]+', 'slug' => '[a-z-]+']);</code></pre>
</div>

<h2 id="gruppen">📂 Route-Gruppen</h2>

<h3>Namespace-Gruppen</h3>

<div class="code-block">
<pre><code class="language-php">// API-Routen gruppieren
\$router->group(['prefix' => 'api/v1'], function(\$router) {
    \$router->get('/users', [UserController::class, 'index']);
    \$router->post('/users', [UserController::class, 'store']);
    \$router->get('/users/{id}', [UserController::class, 'show']);
});

// Admin-Bereich
\$router->group(['prefix' => 'admin', 'middleware' => 'auth'], function(\$router) {
    \$router->get('/dashboard', [AdminController::class, 'dashboard']);
    \$router->get('/users', [AdminController::class, 'users']);
    \$router->get('/settings', [AdminController::class, 'settings']);
});</code></pre>
</div>

<h3>Middleware-Gruppen</h3>

<div class="code-block">
<pre><code class="language-php">// Authentifizierte Routen
\$router->group(['middleware' => 'auth'], function(\$router) {
    \$router->get('/profile', [ProfileController::class, 'show']);
    \$router->put('/profile', [ProfileController::class, 'update']);
    \$router->delete('/account', [AccountController::class, 'delete']);
});

// Rate-limitierte API-Routen
\$router->group(['middleware' => ['cors', 'rate-limit']], function(\$router) {
    \$router->get('/api/data', [ApiController::class, 'getData']);
    \$router->post('/api/upload', [ApiController::class, 'upload']);
});</code></pre>
</div>

<h2 id="middleware">🛡️ Middleware Integration</h2>

<h3>Route-spezifische Middleware</h3>

<div class="code-block">
<pre><code class="language-php">// Einzelne Middleware
\$router->get('/admin', function() {
    return 'Admin Bereich';
})->middleware('auth');

// Mehrere Middleware
\$router->get('/api/users', function() {
    return \$users;
})->middleware(['auth', 'api-key', 'rate-limit']);

// Middleware mit Parametern
\$router->get('/premium', function() {
    return 'Premium Inhalt';
})->middleware('role:premium');</code></pre>
</div>

<h2 id="controller">🎮 Controller-Routing</h2>

<h3>Controller-Methoden</h3>

<div class="code-block">
<pre><code class="language-php">use App\Controllers\UserController;
use App\Controllers\PostController;

// Einzelne Controller-Methoden
\$router->get('/users', [UserController::class, 'index']);
\$router->post('/users', [UserController::class, 'store']);
\$router->get('/users/{id}', [UserController::class, 'show']);
\$router->put('/users/{id}', [UserController::class, 'update']);
\$router->delete('/users/{id}', [UserController::class, 'destroy']);

// Resource-Controller (alle CRUD-Operationen)
\$router->resource('/posts', PostController::class);</code></pre>
</div>

<h3>Controller-Beispiel</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

namespace App\Controllers;

use Brick\Core\View;
use Brick\Core\Response;

class UserController
{
    private \$view;
    private \$response;
    
    public function __construct()
    {
        \$this->view = new View(__DIR__ . '/../Views');
        \$this->response = new Response();
    }
    
    public function index()
    {
        \$users = User::all();
        return \$this->view->render('users/index', [
            'users' => \$users
        ]);
    }
    
    public function show(\$id)
    {
        \$user = User::find(\$id);
        
        if (!\$user) {
            return \$this->response->json([
                'error' => 'Benutzer nicht gefunden'
            ], 404);
        }
        
        return \$this->view->render('users/show', [
            'user' => \$user
        ]);
    }
    
    public function store()
    {
        // Benutzer erstellen...
        return \$this->response->json([
            'message' => 'Benutzer erfolgreich erstellt'
        ], 201);
    }
}</code></pre>
</div>

<h2 id="beispiele">🚀 Praktische Beispiele</h2>

<h3>Blog-Routing</h3>

<div class="code-block">
<pre><code class="language-php">// Blog-Routen
\$router->get('/', [HomeController::class, 'index']); // Startseite
\$router->get('/blog', [BlogController::class, 'index']); // Blog-Übersicht
\$router->get('/blog/{slug}', [BlogController::class, 'show']); // Einzelner Post
\$router->get('/category/{category}', [BlogController::class, 'category']); // Kategorie
\$router->get('/tag/{tag}', [BlogController::class, 'tag']); // Tag

// Admin-Bereich für Blog
\$router->group(['prefix' => 'admin', 'middleware' => 'auth'], function(\$router) {
    \$router->get('/posts', [AdminController::class, 'posts']);
    \$router->get('/posts/create', [AdminController::class, 'createPost']);
    \$router->post('/posts', [AdminController::class, 'storePost']);
    \$router->get('/posts/{id}/edit', [AdminController::class, 'editPost']);
    \$router->put('/posts/{id}', [AdminController::class, 'updatePost']);
    \$router->delete('/posts/{id}', [AdminController::class, 'deletePost']);
});</code></pre>
</div>

<h3>API-Routing</h3>

<div class="code-block">
<pre><code class="language-php">// RESTful API
\$router->group(['prefix' => 'api/v1', 'middleware' => 'api'], function(\$router) {
    
    // Authentifizierung
    \$router->post('/login', [AuthController::class, 'login']);
    \$router->post('/register', [AuthController::class, 'register']);
    
    // Geschützte Routen
    \$router->group(['middleware' => 'auth:api'], function(\$router) {
        \$router->get('/user', [AuthController::class, 'user']);
        \$router->post('/logout', [AuthController::class, 'logout']);
        
        // Benutzer-Verwaltung
        \$router->get('/users', [UserApiController::class, 'index']);
        \$router->post('/users', [UserApiController::class, 'store']);
        \$router->get('/users/{id}', [UserApiController::class, 'show']);
        \$router->put('/users/{id}', [UserApiController::class, 'update']);
        \$router->delete('/users/{id}', [UserApiController::class, 'destroy']);
    });
});</code></pre>
</div>

<h2>📝 Best Practices</h2>

<div class="example-block">
    <h5>✅ Empfehlungen</h5>
    <ul>
        <li><strong>RESTful Design:</strong> Verwenden Sie konsistente URL-Strukturen</li>
        <li><strong>Gruppierung:</strong> Organisieren Sie verwandte Routen in Gruppen</li>
        <li><strong>Middleware:</strong> Nutzen Sie Middleware für Querschnittsfunktionen</li>
        <li><strong>Parameter-Validation:</strong> Validieren Sie Route-Parameter mit Constraints</li>
    </ul>
</div>

<div class="warning-block">
    <h5>⚠️ Wichtige Hinweise</h5>
    <ul>
        <li>Routen werden in der Reihenfolge ihrer Definition verarbeitet</li>
        <li>Spezifischere Routen sollten vor allgemeineren definiert werden</li>
        <li>Parameter-Namen müssen eindeutig sein</li>
        <li>Middleware wird in der angegebenen Reihenfolge ausgeführt</li>
    </ul>
</div>

<h2>🚀 Nächste Schritte</h2>

<div class="row">
    <div class="col-md-4">
        <a href="/docs/views" class="btn btn-primary w-100">🎨 View System</a>
    </div>
    <div class="col-md-4">
        <a href="/docs/middleware" class="btn btn-outline-primary w-100">🛡️ Middleware</a>
    </div>
    <div class="col-md-4">
        <a href="/demo/routes" class="btn btn-outline-secondary w-100">🧪 Live Demo</a>
    </div>
</div>
@endsection

@section('prev-page')
<a href="/docs/installation" class="btn btn-outline-secondary">
    ← Installation
</a>
@endsection

@section('next-page')
<a href="/docs/views" class="btn btn-primary">
    View System →
</a>
@endsection
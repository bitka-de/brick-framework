@extends('docs')

@section('title', 'Middleware')

@section('content')
<h1>🔄 Middleware</h1>

<div class="info-block">
    <h5>🚧 In Entwicklung</h5>
    <p>Das Middleware-System ist derzeit in Entwicklung und wird in einer zukünftigen Version des Brick Frameworks verfügbar sein.</p>
</div>

<div class="table-of-contents">
    <h5>🗺️ Geplante Features</h5>
    <ul>
        <li><a href="#konzept">Middleware-Konzept</a></li>
        <li><a href="#auth">Authentifizierung</a></li>
        <li><a href="#cors">CORS-Handling</a></li>
        <li><a href="#logging">Request-Logging</a></li>
        <li><a href="#roadmap">Entwicklungs-Roadmap</a></li>
    </ul>
</div>

<h2 id="konzept">🎨 Middleware-Konzept</h2>

<p class="lead">Middleware ermöglicht es, HTTP-Requests vor und nach der Verarbeitung zu modifizieren oder zu validieren.</p>

<div class="example-block">
    <h6>🔄 Geplante Architektur</h6>
    <p>Das Middleware-System wird eine Pipeline-Architektur verwenden, die es ermöglicht, mehrere Middleware-Komponenten zu verketten.</p>
</div>

<h3>Zukünftige Syntax (Vorschau)</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

// Route mit Middleware
$router-&gt;get('/admin', function() {
    return 'Admin-Bereich';
})-&gt;middleware('auth');

// Mehrere Middleware
$router-&gt;get('/api/users', function() {
    return ['users' =&gt; []];
})-&gt;middleware(['auth', 'api', 'cors']);

// Middleware-Gruppen
$router-&gt;group(['middleware' =&gt; 'auth'], function($router) {
    $router-&gt;get('/dashboard', [DashboardController::class, 'index']);
    $router-&gt;get('/profile', [ProfileController::class, 'show']);
});</code></pre>
</div>

<h2 id="auth">🔐 Authentifizierung</h2>

<h3>Auth-Middleware (geplant)</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

namespace App\Middleware;

use Brick\Http\{Request, Response};
use Brick\Contracts\Middleware;

class AuthMiddleware implements Middleware
{
    public function handle(Request $request, \Closure $next): Response
    {
        // Session prüfen
        if (!$request-&gt;session()-&gt;has('user_id')) {
            return redirect('/login');
        }
        
        // User-Objekt laden
        $userId = $request-&gt;session()-&gt;get('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            return redirect('/login');
        }
        
        // Request mit User anreichern
        $request-&gt;setUser($user);
        
        return $next($request);
    }
}</code></pre>
</div>

<h2 id="cors">🌐 CORS-Handling</h2>

<h3>CORS-Middleware (geplant)</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

namespace App\Middleware;

use Brick\Http\{Request, Response};
use Brick\Contracts\Middleware;

class CorsMiddleware implements Middleware
{
    private array $allowedOrigins = [
        'https://example.com',
        'https://app.example.com'
    ];
    
    public function handle(Request $request, \Closure $next): Response
    {
        $origin = $request-&gt;header('Origin');
        
        // Preflight OPTIONS Request
        if ($request-&gt;method() === 'OPTIONS') {
            return $this-&gt;handlePreflight($origin);
        }
        
        $response = $next($request);
        
        // CORS Headers hinzufügen
        if (in_array($origin, $this-&gt;allowedOrigins)) {
            $response-&gt;header('Access-Control-Allow-Origin', $origin);
            $response-&gt;header('Access-Control-Allow-Credentials', 'true');
        }
        
        return $response;
    }
    
    private function handlePreflight(string $origin): Response
    {
        $response = new Response();
        
        if (in_array($origin, $this-&gt;allowedOrigins)) {
            $response-&gt;header('Access-Control-Allow-Origin', $origin);
            $response-&gt;header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response-&gt;header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
            $response-&gt;header('Access-Control-Max-Age', '86400');
        }
        
        return $response;
    }
}</code></pre>
</div>

<h2 id="logging">📝 Request-Logging</h2>

<h3>Logging-Middleware (geplant)</h3>

<div class="code-block">
<pre><code class="language-php">&lt;?php

namespace App\Middleware;

use Brick\Http\{Request, Response};
use Brick\Contracts\Middleware;
use Psr\Log\LoggerInterface;

class LoggingMiddleware implements Middleware
{
    private LoggerInterface $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this-&gt;logger = $logger;
    }
    
    public function handle(Request $request, \Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Request loggen
        $this-&gt;logger-&gt;info('Request started', [
            'method' =&gt; $request-&gt;method(),
            'uri' =&gt; $request-&gt;uri(),
            'ip' =&gt; $request-&gt;ip(),
            'user_agent' =&gt; $request-&gt;header('User-Agent')
        ]);
        
        $response = $next($request);
        
        $duration = microtime(true) - $startTime;
        
        // Response loggen
        $this-&gt;logger-&gt;info('Request completed', [
            'status_code' =&gt; $response-&gt;getStatusCode(),
            'duration_ms' =&gt; round($duration * 1000, 2),
            'memory_peak_mb' =&gt; round(memory_get_peak_usage() / 1024 / 1024, 2)
        ]);
        
        return $response;
    }
}</code></pre>
</div>

<h2 id="roadmap">🗺️ Entwicklungs-Roadmap</h2>

<div class="row">
    <div class="col-md-6">
        <h4>🏁 Version 1.1 (geplant)</h4>
        <ul>
            <li>✅ Basic Middleware Interface</li>
            <li>✅ Pipeline-Implementation</li>
            <li>✅ Auth-Middleware</li>
            <li>✅ CORS-Middleware</li>
        </ul>
    </div>
    
    <div class="col-md-6">
        <h4>🚀 Version 1.2 (geplant)</h4>
        <ul>
            <li>⏳ Rate-Limiting</li>
            <li>⏳ Request-Validation</li>
            <li>⏳ Response-Caching</li>
            <li>⏳ API-Throttling</li>
        </ul>
    </div>
</div>

<div class="warning-block">
    <h5>📅 Zeitplan</h5>
    <p>Die Middleware-Implementierung ist für Q1 2026 geplant. Updates werden über die GitHub-Repository kommuniziert.</p>
</div>

<h2>👥 Community-Beitrag</h2>

<p>Möchten Sie bei der Middleware-Entwicklung mithelfen?</p>

<div class="example-block">
    <h6>🤝 Wie Sie beitragen können</h6>
    <ul>
        <li><strong>GitHub Issues:</strong> Diskutieren Sie Middleware-Konzepte</li>
        <li><strong>Pull Requests:</strong> Implementieren Sie Middleware-Prototypen</li>
        <li><strong>Testing:</strong> Testen Sie experimentelle Middleware-Branches</li>
        <li><strong>Dokumentation:</strong> Helfen Sie bei der Middleware-Dokumentation</li>
    </ul>
</div>

<h2>🚀 Aktuelle Alternativen</h2>

<p>Bis Middleware verfügbar ist, können Sie folgende Ansätze verwenden:</p>

<div class="code-block">
<pre><code class="language-php">&lt;?php

// Manual Auth Check in Routes
$router-&gt;get('/admin', function() {
    // Authentifizierung prüfen
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    
    return 'Admin-Bereich';
});

// Helper-Functions
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
}

$router-&gt;get('/dashboard', function() {
    requireAuth();
    return 'Dashboard';
});</code></pre>
</div>

<h2>🚀 Nächste Schritte</h2>

<div class="row">
    <div class="col-md-4">
        <a href="/docs/database" class="btn btn-primary w-100">🗄️ Database</a>
    </div>
    <div class="col-md-4">
        <a href="/docs/api" class="btn btn-outline-primary w-100">📚 API Referenz</a>
    </div>
    <div class="col-md-4">
        <a href="https://github.com/bitka-de/brick-framework" class="btn btn-outline-secondary w-100">💙 GitHub</a>
    </div>
</div>
@endsection

@section('prev-page')
<a href="/docs/css-js" class="btn btn-outline-secondary">
    ← CSS/JS Direktiven
</a>
@endsection

@section('next-page')
<a href="/docs/database" class="btn btn-primary">
    Database →
</a>
@endsection
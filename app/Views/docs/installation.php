@extends('docs')

@section('title', 'Installation')

@section('content')
<h1>⚙️ Installation</h1>

<p class="lead">Schritt-für-Schritt Anleitung zur Installation des Brick Frameworks</p>

<div class="table-of-contents">
    <h5>🗺️ Inhaltsübersicht</h5>
    <ul>
        <li><a href="#anforderungen">Systemanforderungen</a></li>
        <li><a href="#download">Download & Setup</a></li>
        <li><a href="#konfiguration">Konfiguration</a></li>
        <li><a href="#webserver">Webserver-Setup</a></li>
        <li><a href="#entwicklung">Entwicklungsserver</a></li>
        <li><a href="#verifikation">Installation verifizieren</a></li>
    </ul>
</div>

<h2 id="anforderungen">💻 Systemanforderungen</h2>

<div class="row">
    <div class="col-md-6">
        <h4>✅ Minimum</h4>
        <ul>
            <li><strong>PHP:</strong> 8.0+</li>
            <li><strong>Webserver:</strong> Apache/Nginx</li>
            <li><strong>RAM:</strong> 256 MB</li>
            <li><strong>Speicher:</strong> 50 MB</li>
        </ul>
    </div>
    
    <div class="col-md-6">
        <h4>🚀 Empfohlen</h4>
        <ul>
            <li><strong>PHP:</strong> 8.2+</li>
            <li><strong>Extensions:</strong> mbstring, curl, json</li>
            <li><strong>RAM:</strong> 512 MB+</li>
            <li><strong>Composer:</strong> Latest</li>
        </ul>
    </div>
</div>

<h2 id="download">📥 Download & Setup</h2>

<h3>Via Git (Empfohlen)</h3>

<div class="code-block">
<pre><code class="language-bash"># Repository klonen
git clone https://github.com/bitka-de/brick-framework.git
cd brick-framework

# Dependencies installieren (falls vorhanden)
composer install

# Berechtigung setzen
chmod -R 755 public/
chmod -R 777 storage/ (falls vorhanden)</code></pre>
</div>

<h3>Via Download</h3>

<div class="example-block">
    <h6>📦 Direkt-Download</h6>
    <ol>
        <li>Framework von GitHub herunterladen</li>
        <li>ZIP-Datei extrahieren</li>
        <li>Dateien auf Webserver hochladen</li>
        <li>Berechtigungen setzen</li>
    </ol>
</div>

<h2 id="konfiguration">⚙️ Konfiguration</h2>

<h3>Ordnerstruktur</h3>

<div class="code-block">
<pre><code class="language-text">brick-framework/
├── app/                    # Ihre Anwendung
│   ├── Controllers/        # Controller-Klassen
│   ├── Views/             # Template-Dateien
│   └── routes.php         # Route-Definitionen
├── brick/                 # Framework-Core
│   ├── Core/              # Kern-Komponenten
│   └── Http/              # HTTP-Handling
└── public/                # Öffentlich zugänglich
    └── index.php          # Entry Point</code></pre>
</div>

<h3>Webserver-Konfiguration</h3>

<h4>Apache (.htaccess)</h4>

<div class="code-block">
<pre><code class="language-apache">RewriteEngine On

# Handle Angular and other front-end routes
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ public/index.php [QSA,L]</code></pre>
</div>

<h4>Nginx</h4>

<div class="code-block">
<pre><code class="language-nginx">server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/brick-framework/public;
    index index.php;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }
}</code></pre>
</div>

<h2 id="entwicklung">🚀 Entwicklungsserver</h2>

<h3>PHP Built-in Server</h3>

<div class="code-block">
<pre><code class="language-bash"># Im Framework-Verzeichnis
cd public
php -S localhost:8000

# Oder mit anderer IP/Port
php -S 0.0.0.0:8080</code></pre>
</div>

<div class="info-block">
    <h5>💡 Tipp</h5>
    <p>Der PHP Built-in Server ist perfekt für die Entwicklung, aber nicht für Produktion geeignet.</p>
</div>

<h2 id="verifikation">✅ Installation verifizieren</h2>

<h3>1. Basis-Test</h3>

<p>Öffnen Sie <code>http://localhost:8000</code> in Ihrem Browser. Sie sollten die Framework-Startseite sehen.</p>

<h3>2. Route-Test</h3>

<p>Erstellen Sie eine einfache Test-Route in <code>app/routes.php</code>:</p>

<div class="code-block">
<pre><code class="language-php">&lt;?php

use Brick\Core\Router;

\$router = new Router();

// Test-Route
\$router-&gt;get('/test', function() {
    return 'Framework funktioniert!';
});

return \$router;</code></pre>
</div>

<h3>3. Framework-Features testen</h3>

<div class="row">
    <div class="col-md-4">
        <h5>🛣️ Routing</h5>
        <a href="/test" class="btn btn-outline-primary btn-sm">Test Route</a>
    </div>
    
    <div class="col-md-4">
        <h5>📊 API</h5>
        <a href="/api/stats" class="btn btn-outline-primary btn-sm">API Test</a>
    </div>
    
    <div class="col-md-4">
        <h5>📚 Dokumentation</h5>
        <a href="/docs" class="btn btn-outline-primary btn-sm">Docs Test</a>
    </div>
</div>

<h2>🔧 Troubleshooting</h2>

<div class="warning-block">
    <h5>⚠️ Häufige Probleme</h5>
    <ul>
        <li><strong>404 Fehler:</strong> Prüfen Sie die Webserver-Konfiguration</li>
        <li><strong>PHP Fehler:</strong> Überprüfen Sie die PHP-Version (8.0+)</li>
        <li><strong>Berechtigungen:</strong> Stellen Sie sicher, dass der Webserver Zugriff hat</li>
        <li><strong>Pfade:</strong> Überprüfen Sie die Document Root Einstellung</li>
    </ul>
</div>

<h2>🎉 Fertig!</h2>

<p>Herzlichen Glückwunsch! Das Brick Framework ist jetzt installiert und bereit für die Entwicklung.</p>

<div class="row">
    <div class="col-md-4">
        <a href="/docs/routing" class="btn btn-primary w-100">🛣️ Routing lernen</a>
    </div>
    <div class="col-md-4">
        <a href="/docs/views" class="btn btn-outline-primary w-100">🎨 Views erstellen</a>
    </div>
    <div class="col-md-4">
        <a href="/demo/css-js" class="btn btn-outline-secondary w-100">💻 Live Demo</a>
    </div>
</div>
@endsection

@section('prev-page')
<a href="/docs/overview" class="btn btn-outline-secondary">
    ← Framework Übersicht
</a>
@endsection

@section('next-page')
<a href="/docs/routing" class="btn btn-primary">
    Routing System →
</a>
@endsection
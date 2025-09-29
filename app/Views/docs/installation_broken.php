@extends('docs')

@section('title', 'Installation')

@section('content')
<h1>⚙️ Installation</h1>

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

<p>Bevor Sie das Brick Framework installieren, stellen Sie sicher, dass Ihr System die folgenden Anforderungen erfüllt:</p>

<div class="example-block">
    <h5>✅ Erforderlich</h5>
    <ul>
        <li><strong>PHP 8.0+</strong> mit folgenden Extensions:
            <ul>
                <li>mbstring</li>
                <li>json</li>
                <li>fileinfo</li>
            </ul>
        </li>
        <li><strong>Webserver</strong> (Apache, Nginx) oder PHP Built-in Server</li>
        <li><strong>Composer</strong> (optional, für Dependency Management)</li>
    </ul>
</div>

<h2 id="download">📦 Download & Setup</h2>

<h3>Option 1: Git Clone (Empfohlen)</h3>

<div class="code-block">
<pre><code class="language-bash"># Repository klonen
git clone https://github.com/bitka-de/brick-framework.git mein-projekt

# In das Projektverzeichnis wechseln
cd mein-projekt

# Abhängigkeiten installieren (falls vorhanden)
composer install --no-dev --optimize-autoloader</code></pre>
</div>

<h3>Option 2: Download als ZIP</h3>

<ol>
    <li>Laden Sie das Framework von <a href="https://github.com/bitka-de/brick-framework">GitHub</a> herunter</li>
    <li>Entpacken Sie das Archiv in Ihr Webserver-Verzeichnis</li>
    <li>Benennen Sie den Ordner nach Ihren Wünschen um</li>
</ol>

<h2 id="konfiguration">🔧 Konfiguration</h2>

<h3>Verzeichnisstruktur prüfen</h3>

<p>Nach der Installation sollte Ihre Verzeichnisstruktur so aussehen:</p>

<div class="code-block">
<pre><code class="language-">mein-projekt/
├── brick/               # Framework Core (nicht ändern)
├── app/                 # Ihr Anwendungscode
│   ├── Controllers/     # Controller-Klassen
│   ├── Views/           # Template-Dateien
│   └── routes.php       # Route-Definitionen
├── public/             # Öffentlich zugängliche Dateien
│   ├── index.php        # Haupt-Einstiegspunkt
│   ├── css/             # CSS-Dateien
│   ├── js/              # JavaScript-Dateien
│   └── images/          # Bilder
└── .htaccess            # Apache-Konfiguration</code></pre>
</div>

<h3>Berechtigungen setzen</h3>

<div class="code-block">
<pre><code class="language-bash"># Schreibrechte für Cache-Verzeichnis (wird automatisch erstellt)
chmod -R 755 public/

# Falls Sie ein manuelles Cache-Verzeichnis erstellen möchten:
mkdir -p storage/cache/views
chmod -R 755 storage/</code></pre>
</div>

<h2 id="webserver">🌐 Webserver-Setup</h2>

<h3>Apache</h3>

<p>Für Apache ist bereits eine <code>.htaccess</code>-Datei im Hauptverzeichnis enthalten:</p>

<div class="code-block">
<pre><code class="language-apache">RewriteEngine On

# Handle Angular and other frontend routes
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)\$ public/index.php [QSA,L]</code></pre>
</div>

<div class="info-block">
    <strong>Virtual Host Konfiguration:</strong>
    <div class="code-block">
<pre><code class="language-apache">&lt;VirtualHost *:80&gt;
    ServerName mein-projekt.local
    DocumentRoot /pfad/zu/mein-projekt/public
    
    &lt;Directory /pfad/zu/mein-projekt/public&gt;
        AllowOverride All
        Require all granted
    &lt;/Directory&gt;
&lt;/VirtualHost&gt;</code></pre>
    </div>
</div>

<h3>Nginx</h3>

<div class="code-block">
<pre><code class="language-nginx">server {
    listen 80;
    server_name mein-projekt.local;
    root /pfad/zu/mein-projekt/public;
    index index.php;
    
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }
    
    location ~ \.php\$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }
}</code></pre>
</div>

<h2 id="entwicklung">📝 Entwicklungsserver</h2>

<p>Für die schnelle Entwicklung können Sie den PHP Built-in Server verwenden:</p>

<div class="code-block">
<pre><code class="language-bash"># Im Hauptverzeichnis des Projekts
php -S localhost:8000 -t public/

# Alternative mit spezifischem Port
php -S 0.0.0.0:3000 -t public/</code></pre>
</div>

<div class="warning-block">
    <strong>Achtung:</strong> Der PHP Built-in Server ist nur für die Entwicklung gedacht und sollte nicht in der Produktion verwendet werden.
</div>

<h2 id="verifikation">✅ Installation verifizieren</h2>

<h3>1. Grundfunktionalität testen</h3>

<p>Öffnen Sie Ihren Browser und navigieren Sie zu:</p>

<ul>
    <li><code>http://localhost:8000</code> (bei Built-in Server)</li>
    <li><code>http://mein-projekt.local</code> (bei Virtual Host)</li>
</ul>

<p>Sie sollten die Willkommensseite des Brick Frameworks sehen.</p>

<h3>2. Template-System testen</h3>

<p>Besuchen Sie <code>/demo/templates</code> um das Template-System zu testen.</p>

<h3>3. CSS/JS-Direktiven testen</h3>

<p>Besuchen Sie <code>/demo/css-js</code> um die Asset-Management-Features zu testen.</p>

<h3>4. Dokumentation aufrufen</h3>

<p>Diese Dokumentation ist unter <code>/docs</code> verfügbar.</p>

<div class="example-block">
    <h5>✨ Erfolgreich installiert!</h5>
    <p>Wenn alle Tests erfolgreich waren, ist das Brick Framework korrekt installiert und einsatzbereit.</p>
</div>

<h2>🔧 Anpassungen</h2>

<h3>Basis-Konfiguration</h3>

<p>Bearbeiten Sie <code>app/routes.php</code> um Ihre eigenen Routen zu definieren:</p>

<div class="code-block">
<pre><code class="language-php">&lt;?php

use Brick\Core\Router;

\$router = new Router();

// Ihre Routen hier
\$router-&gt;get('/', function() {
    echo "Hallo Welt!";
});

// Controller-basierte Route
\$router-&gt;get('/users', [App\Controllers\UserController::class, 'index']);

return \$router;</code></pre>
</div>

<h3>Templates anpassen</h3>

<p>Erstellen Sie eigene Templates in <code>app/Views/</code>:</p>

<div class="code-block">
<pre><code class="language-php">{{-- app/Views/welcome.php --}}
@extends('layouts/app')

@section('title', 'Willkommen')

@section('content')
&lt;h1&gt;Willkommen in meiner App!&lt;/h1&gt;
@endsection</code></pre>
</div>

<h2>🚀 Nächste Schritte</h2>

<div class="row">
    <div class="col-md-4">
        <a href="/docs/routing" class="btn btn-primary w-100">🛣️ Routing lernen</a>
    </div>
    <div class="col-md-4">
        <a href="/docs/views" class="btn btn-outline-primary w-100">🎨 Templates verstehen</a>
    </div>
    <div class="col-md-4">
        <a href="/demo/css-js" class="btn btn-outline-success w-100">💎 Assets verwalten</a>
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
    Routing →
</a>
@endsection
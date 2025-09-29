@extends('docs')

@section('title', 'Framework Übersicht')

@section('content')
<h1>🧱 Brick Framework Übersicht</h1>

<div class="table-of-contents">
    <h5>🗺️ Inhaltsübersicht</h5>
    <ul>
        <li><a href="#was-ist-brick">Was ist das Brick Framework?</a></li>
        <li><a href="#philosophie">Design-Philosophie</a></li>
        <li><a href="#architektur">Architektur</a></li>
        <li><a href="#features">Hauptfeatures</a></li>
        <li><a href="#systemanforderungen">Systemanforderungen</a></li>
    </ul>
</div>

<h2 id="was-ist-brick">🏠 Was ist das Brick Framework?</h2>

<p class="lead">Das Brick Framework ist ein modernes, leichtgewichtiges PHP Web Framework, das entwickelt wurde, um die Entwicklung von Webanwendungen zu vereinfachen und zu beschleunigen.</p>

<div class="example-block">
    <h5>✨ Vision</h5>
    <p>Wir glauben an einfache, aber mächtige Tools. Das Brick Framework kombiniert die Vertrautheit von Laravel Blade mit der Einfachheit eines Mikroframeworks.</p>
</div>

<h2 id="philosophie">🎯 Design-Philosophie</h2>

<div class="row">
    <div class="col-md-6">
        <h4>🔧 Einfachheit</h4>
        <p>Minimale Konfiguration, maximale Produktivität. Das Framework folgt dem Prinzip "Convention over Configuration".</p>
        
        <h4>📝 Vertrautheit</h4>
        <p>Blade-ähnliche Template-Syntax und Laravel-inspirierte Konzepte für eine kurze Einarbeitungszeit.</p>
    </div>
    <div class="col-md-6">
        <h4>⚡ Performance</h4>
        <p>Schnelle Template-Kompilierung und optimierte Asset-Verwaltung für beste Performance.</p>
        
        <h4>🔄 Flexibilität</h4>
        <p>Modularer Aufbau ermöglicht es, nur die benötigten Komponenten zu verwenden.</p>
    </div>
</div>

<h2 id="architektur">🏢 Architektur</h2>

<p>Das Brick Framework folgt einer MVC-ähnlichen Architektur mit modernen PHP-Konzepten:</p>

<div class="code-block">
<pre><code class="language-">brick-framework/
├── brick/           # Framework Core
│   ├── Core/        # Kern-Klassen
│   │   ├── View.php    # Template System
│   │   ├── Router.php  # Routing System
│   │   └── Response.php # HTTP Response
│   └── Http/        # HTTP Components
├── app/             # Anwendungs-Code
│   ├── Controllers/ # Controller-Klassen
│   ├── Views/       # Template-Dateien
│   └── routes.php   # Route-Definitionen
└── public/          # Öffentliche Dateien
    └── index.php    # Einstiegspunkt</code></pre>
</div>

<h2 id="features">✨ Hauptfeatures</h2>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">🎨 Template System</h5>
                <p class="card-text">Blade-ähnliche Template-Engine mit vertrauter Syntax für @extends, @section, @include und mehr.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">💎 Asset Management</h5>
                <p class="card-text">Revolutionäre @css und @js Direktiven für automatische Asset-Einbindung direkt in Templates.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">🛣️ Flexibles Routing</h5>
                <p class="card-text">Einfache Route-Definitionen mit Closures, Controller-Support und Parameter-Binding.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">🔄 Middleware</h5>
                <p class="card-text">Request/Response-Pipeline für Cross-Cutting Concerns wie Authentifizierung und Logging.</p>
            </div>
        </div>
    </div>
</div>

<h2 id="systemanforderungen">⚙️ Systemanforderungen</h2>

<div class="info-block">
    <h5>💻 Mindestanforderungen</h5>
    <ul>
        <li><strong>PHP:</strong> 8.0 oder höher</li>
        <li><strong>Extensions:</strong> mbstring, json</li>
        <li><strong>Webserver:</strong> Apache, Nginx oder PHP Built-in Server</li>
        <li><strong>Betriebssystem:</strong> Linux, macOS, Windows</li>
    </ul>
</div>

<div class="warning-block">
    <h5>⚠️ Wichtige Hinweise</h5>
    <ul>
        <li>Das Framework ist für PHP 8.0+ optimiert und nutzt moderne PHP-Features</li>
        <li>Für Produktionsumgebungen wird OPcache empfohlen</li>
        <li>Template-Caching erfordert Schreibrechte im Cache-Verzeichnis</li>
    </ul>
</div>

<h2>🚀 Nächste Schritte</h2>

<p>Bereit loszulegen? Hier sind die nächsten Schritte:</p>

<div class="row">
    <div class="col-md-4">
        <a href="/docs/installation" class="btn btn-primary w-100">⚙️ Installation</a>
    </div>
    <div class="col-md-4">
        <a href="/docs/routing" class="btn btn-outline-primary w-100">🛣️ Routing Tutorial</a>
    </div>
    <div class="col-md-4">
        <a href="/demo/css-js" class="btn btn-outline-success w-100">🎯 Live Demo</a>
    </div>
</div>
@endsection

@section('next-page')
<a href="/docs/installation" class="btn btn-primary">
    Installation →
</a>
@endsection
@extends('app')

@section('title', 'Über das Brick Framework')

@section('breadcrumb')
<div class="bg-light py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Über uns</li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('header')
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-4">Über das Brick Framework</h1>
                <p class="lead">
                    Ein modernes PHP Framework, das Geschwindigkeit, Einfachheit 
                    und Entwicklerfreundlichkeit vereint.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h2 class="mb-4">Unsere Mission</h2>
        <p class="lead">
            Das Brick Framework wurde entwickelt, um Entwicklern ein leistungsstarkes, 
            aber dennoch einfach zu verwendendes Tool für moderne Webanwendungen zu bieten.
        </p>
        
        <p>
            Wir glauben, dass Web-Entwicklung nicht kompliziert sein muss. Deshalb haben 
            wir ein Framework geschaffen, das auf bewährte Konzepte setzt, aber dennoch 
            moderne PHP-Features nutzt und eine ausgezeichnete Performance bietet.
        </p>
        
        <h3 class="mt-5 mb-3">Technische Highlights</h3>
        <div class="row">
            <div class="col-md-6">
                <ul class="list-unstyled">
                    <li class="mb-2">✅ <strong>PHP 8+</strong> - Moderne Sprachfeatures</li>
                    <li class="mb-2">✅ <strong>O(1) Routing</strong> - Blitzschnelle Navigation</li>
                    <li class="mb-2">✅ <strong>Template Engine</strong> - Blade-ähnliche Syntax</li>
                    <li class="mb-2">✅ <strong>Test Suite</strong> - 91 Tests für Zuverlässigkeit</li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="list-unstyled">
                    <li class="mb-2">🛡️ <strong>Sicherheit</strong> - XSS & CSRF Protection</li>
                    <li class="mb-2">📚 <strong>Dokumentation</strong> - Umfassende Anleitungen</li>
                    <li class="mb-2">🔧 <strong>Debug Tools</strong> - Entwicklerfreundliche Fehlerbehandlung</li>
                    <li class="mb-2">⚡ <strong>Performance</strong> - Template Caching & Optimierungen</li>
                </ul>
            </div>
        </div>
        
        <h3 class="mt-5 mb-3">Framework-Architektur</h3>
        <p>
            Das Brick Framework folgt bewährten Design-Patterns und ist in drei 
            Hauptkomponenten unterteilt:
        </p>
        
        <!-- HTTP-System Card -->
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">🌐 HTTP-System</h5>
                <p class="card-text">
                    Verarbeitet Requests und Responses mit umfassender Unterstützung für moderne 
                    HTTP-Features, Sessions, Cookies und Sicherheitsheader.
                </p>
            </div>
        </div>
        
        <!-- Router-System Card -->
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">🔀 Router-System</h5>
                <p class="card-text">
                    Hochperformantes Routing mit O(1)-Lookups für statische Routen, Routengruppen, 
                    Parameter-Validierung und Debug-Tools.
                </p>
            </div>
        </div>
        
        <!-- View-System Card -->
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">🎨 View-System</h5>
                <p class="card-text">
                    Blade-ähnliche Template-Engine mit Layouts, Komponenten, sicherer Ausgabe 
                    und intelligentem Caching.
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="sticky-top" style="top: 20px;">
            <!-- Live-Statistiken Card -->
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">📊 Live-Statistiken</h5>
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $stats['compiled_templates'] ?? 0 }}</h4>
                            <small class="text-muted">Templates</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $stats['cache_hits'] ?? 0 }}</h4>
                            <small class="text-muted">Cache Hits</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <small class="text-muted">
                            Render-Zeit: <strong>{{ number_format(($stats['render_time'] ?? 0) * 1000, 2) }}ms</strong>
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Open Source Alert -->
            <div class="alert alert-info mt-3">
                <h6 class="alert-heading">Open Source</h6>
                <p class="mb-0">Das Brick Framework ist Open Source und steht unter der MIT-Lizenz.</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col text-center">
        <h3 class="mb-4">Bereit zum Loslegen?</h3>
        <p class="mb-4">Entdecke die Möglichkeiten des Brick Frameworks</p>
        <a href="/docs" class="btn btn-primary btn-lg me-3">Dokumentation</a>
        <a href="https://github.com/bitka-de/brick-framework" class="btn btn-outline-primary btn-lg">GitHub</a>
    </div>
</div>
@endsection
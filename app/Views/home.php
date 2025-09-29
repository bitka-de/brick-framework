@extends('app')

@section('title', 'Willkommen bei Brick Framework')
@section('description', 'Das ss PHP Framework für schnelle Webanwendungen')

@section('styles')
<style>
    .hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 80px 0;
        text-align: center;
    }
    .feature-card {
        transition: transform 0.3s ease;
        height: 100%;
    }
    .feature-card:hover {
        transform: translateY(-5px);
    }
    .code-example {
        background: #f8f9fa;
        border-left: 4px solid #007bff;
        padding: 20px;
        margin: 20px 0;
        border-radius: 4px;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero">
    <div class="container">
        <h1 class="display-4 mb-4">🧱 Brick Framework</h1>
        <p class="lead mb-4">Das moderne PHP Framework für blitzschnelle Webanwendungen</p>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <p class="mb-4">
                    Entwickle leistungsstarke Webanwendungen mit PHP 8+, 
                    Blade-ähnlichen Templates und einem intuitiven Routing-System.
                </p>
                <a href="#features" class="btn btn-light btn-lg me-3">Features entdecken</a>
                <a href="/demo/css-js" class="btn btn-success btn-lg me-3">✨ CSS & JS Demo</a>
                <a href="/docs" class="btn btn-outline-light btn-lg">Dokumentation</a>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section id="features" class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col">
                <h2 class="display-5 mb-4">Warum Brick Framework?</h2>
                <p class="lead text-muted">Modern, schnell und entwicklerfreundlich</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card feature-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span class="display-4">🚀</span>
                        </div>
                        <h5 class="card-title">Blitzschnell</h5>
                        <p class="card-text">
                            O(1) Routing für statische Routen, optimierte Template-Engine 
                            mit Caching und minimaler Overhead.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card feature-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span class="display-4">🎨</span>
                        </div>
                        <h5 class="card-title">Blade-Templates</h5>
                        <p class="card-text">
                            Vertraute Syntax mit @extends, @section, @yield und mehr. 
                            Layouts, Komponenten und sichere Ausgabe inklusive.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card feature-card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span class="display-4">🛡️</span>
                        </div>
                        <h5 class="card-title">Sicher</h5>
                        <p class="card-text">
                            Eingebaute XSS-Protection, CSRF-Schutz, sichere Headers 
                            und automatisches Escaping für alle Ausgaben.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Code Example Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h3 class="mb-4">Einfach zu verwenden</h3>
                <p>Mit nur wenigen Zeilen Code erstellst du leistungsstarke Anwendungen:</p>
                
                <div class="code-example">
                    <h6>Controller (PHP)</h6>
                    <pre><code><?= htmlspecialchars('<?php
use Brick\Core\View;

$view = new View();
$view->share("user", $currentUser);

return $view->render("dashboard", [
    "stats" => $userStats,
    "recent" => $recentActivity
]);') ?></code></pre>
                </div>
                
                <div class="code-example">
                    <h6>Template (dashboard.php)</h6>
                    <pre><code><?= htmlspecialchars('@extends("app")

@section("content")
    <h1>Hallo, {{ $user->name }}!</h1>
    
    @foreach($recent as $activity)
        <div class="activity">
            {{ $activity->description }}
        </div>
    @endforeach
@endsection') ?></code></pre>
                </div>
            </div>
            
            <div class="col-lg-6">
                <h3 class="mb-4">Framework-Statistiken</h3>
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h2 class="card-title">{{ $stats['compiled_templates'] ?? 0 }}</h2>
                                <p class="card-text">Templates kompiliert</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h2 class="card-title">{{ $stats['cache_hits'] ?? 0 }}</h2>
                                <p class="card-text">Cache Hits</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6 mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h2 class="card-title">{{ number_format(($stats['render_time'] ?? 0) * 1000, 2) }}ms</h2>
                                <p class="card-text">Render-Zeit</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-6 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h2 class="card-title">91</h2>
                                <p class="card-text">Tests bestanden</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Getting Started Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h3 class="mb-4">Bereit loszulegen?</h3>
                <p class="lead mb-4">
                    Installiere das Brick Framework und starte dein erstes Projekt in wenigen Minuten.
                </p>
                
                <div class="code-example text-start">
                    <pre><code># Framework installieren
composer require bitka-de/brick-framework

# Oder Repository klonen
git clone https://github.com/bitka-de/brick-framework.git
cd brick-framework
composer install

# Tests ausführen
php tests/brick-test-runner.php

# Development Server starten  
php -S localhost:8080 -t public/</code></pre>
                </div>
                
                <div class="mt-4">
                    <a href="/docs/getting-started" class="btn btn-primary btn-lg me-3">Erste Schritte</a>
                    <a href="https://github.com/bitka-de/brick-framework" class="btn btn-outline-primary btn-lg">
                        GitHub Repository
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
// Smooth Scrolling für Anchor-Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Feature Cards Animation on Scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
        }
    });
}, observerOptions);

document.querySelectorAll('.feature-card').forEach(card => {
    observer.observe(card);
});

// CSS Animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .feature-card {
        opacity: 0;
    }
`;
document.head.appendChild(style);
</script>
@endsection

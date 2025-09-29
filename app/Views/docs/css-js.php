@extends('docs')

@section('title', 'CSS/JS Direktiven')

@section('content')
<h1>💎 CSS/JS Direktiven</h1>

<div class="table-of-contents">
    <h5>🗺️ Inhaltsübersicht</h5>
    <ul>
        <li><a href="#einfuehrung">Einführung</a></li>
        <li><a href="#css-direktiven">CSS-Direktiven</a></li>
        <li><a href="#js-direktiven">JavaScript-Direktiven</a></li>
        <li><a href="#asset-management">Asset-Management</a></li>
        <li><a href="#performance">Performance-Optimierung</a></li>
        <li><a href="#beispiele">Praktische Beispiele</a></li>
        <li><a href="#best-practices">Best Practices</a></li>
    </ul>
</div>

<h2 id="einfuehrung">✨ Einführung</h2>

<p class="lead">Die CSS/JS-Direktiven des Brick Frameworks ermöglichen es, Assets direkt in Templates zu definieren und automatisch in den HTML-Header zu injizieren.</p>

<div class="example-block">
    <h5>🚀 Hauptvorteile</h5>
    <ul>
        <li><strong>Einfachheit:</strong> Assets direkt im Template definieren</li>
        <li><strong>Automatische Injection:</strong> CSS/JS wird automatisch in den HTML-Header eingefügt</li>
        <li><strong>Keine Duplikate:</strong> Gleiche Assets werden nur einmal geladen</li>
        <li><strong>Performance:</strong> Optimierte Ladereihenfolge und Caching</li>
    </ul>
</div>

<h2 id="css-direktiven">🎨 CSS-Direktiven</h2>

<h3>Externe CSS-Dateien</h3>

<p>Verwenden Sie die css-Direktive um externe CSS-Dateien einzubinden:</p>

<div class="code-block">
<pre><code class="language-blade">
<!-- Externe CSS-Bibliothek -->
<!-- css('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css') -->

<!-- Lokale CSS-Datei -->
<!-- css('/css/custom.css') -->

<!-- Bootstrap CSS -->
<!-- css('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css') -->
</code></pre>
</div>

<h3>Inline CSS-Styles</h3>

<p>Definieren Sie CSS-Styles direkt im Template:</p>

<div class="code-block">
<pre><code class="language-blade">
<!-- CSS-Block für seitspezifische Styles -->
<!-- css -->
.custom-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
}

.feature-box {
    border: 2px solid #007bff;
    border-radius: 8px;
    padding: 1rem;
    margin: 1rem 0;
}
<!-- endcss -->
</code></pre>
</div>

<h3>Conditional CSS</h3>

<p>CSS nur unter bestimmten Bedingungen laden:</p>

<div class="code-block">
<pre><code class="language-blade">
<!-- CSS nur für bestimmte Seiten -->
<!-- if($page === 'dashboard') -->
    <!-- css('/css/dashboard.css') -->
<!-- endif -->

<!-- CSS für verschiedene Themes -->
<!-- if($theme === 'dark') -->
    <!-- css('/css/dark-theme.css') -->
<!-- else -->
    <!-- css('/css/light-theme.css') -->
<!-- endif -->
</code></pre>
</div>

<h2 id="js-direktiven">⚡ JavaScript-Direktiven</h2>

<h3>Externe JavaScript-Dateien</h3>

<p>JavaScript-Bibliotheken und externe Skripte einbinden:</p>

<div class="code-block">
<pre><code class="language-blade">
<!-- jQuery -->
<!-- js('https://code.jquery.com/jquery-3.6.0.min.js') -->

<!-- Bootstrap JavaScript -->
<!-- js('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js') -->

<!-- Lokale JavaScript-Datei -->
<!-- js('/js/app.js') -->

<!-- Chart.js für Diagramme -->
<!-- js('https://cdn.jsdelivr.net/npm/chart.js') -->
</code></pre>
</div>

<h3>Inline JavaScript</h3>

<p>JavaScript-Code direkt im Template definieren:</p>

<div class="code-block">
<pre><code class="language-blade">
<!-- JavaScript-Block -->
<!-- js -->
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling für Navigation
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
    
    // Toast-Benachrichtigungen
    if (typeof bootstrap !== 'undefined') {
        const toastElements = document.querySelectorAll('.toast');
        toastElements.forEach(el => new bootstrap.Toast(el));
    }
});
<!-- endjs -->
</code></pre>
</div>

<h3>Script-Attribute</h3>

<p>JavaScript mit spezifischen Attributen laden:</p>

<div class="code-block">
<pre><code class="language-blade">
<!-- Defer-Attribut -->
<!-- js('/js/analytics.js', ['defer' => true]) -->

<!-- Async-Attribut -->
<!-- js('/js/widgets.js', ['async' => true]) -->

<!-- Integrity-Check -->
<!-- js('https://cdn.example.com/lib.js', ['integrity' => 'sha384-...']) -->

<!-- CrossOrigin -->
<!-- js('https://external.com/script.js', ['crossorigin' => 'anonymous']) -->
</code></pre>
</div>

<h2 id="asset-management">📦 Asset-Management</h2>

<h3>Asset-Reihenfolge</h3>

<p>Die Reihenfolge der Asset-Einbindung ist wichtig:</p>

<ol>
    <li><strong>CSS wird zuerst geladen:</strong> Verhindert FOUC (Flash of Unstyled Content)</li>
    <li><strong>JavaScript wird nach CSS geladen:</strong> Stellt sicher, dass Styles verfügbar sind</li>
    <li><strong>Abhängigkeiten werden respektiert:</strong> jQuery vor Bootstrap, etc.</li>
    <li><strong>Inline-Code wird nach externen Dateien ausgeführt</strong></li>
</ol>

<h3>Duplikat-Vermeidung</h3>

<p>Das System verhindert automatisch doppelte Asset-Einbindungen:</p>

<ul>
    <li><strong>Gleiche URLs:</strong> Werden nur einmal geladen</li>
    <li><strong>Smart Merging:</strong> Inline-Styles werden zusammengefasst</li>
    <li><strong>Cache-Optimierung:</strong> Assets werden intelligent gecacht</li>
</ul>

<h2 id="performance">⚡ Performance-Optimierung</h2>

<h3>Best Practices für Performance</h3>

<div class="example-block">
    <h5>✅ Empfehlungen</h5>
    <ul>
        <li><strong>Minimieren Sie HTTP-Requests:</strong> Kombinieren Sie CSS/JS wo möglich</li>
        <li><strong>Nutzen Sie CDNs:</strong> Für populäre Bibliotheken</li>
        <li><strong>Lazy Loading:</strong> Laden Sie JavaScript erst bei Bedarf</li>
        <li><strong>Kritisches CSS:</strong> Inline für Above-the-fold Content</li>
    </ul>
</div>

<h3>Asset-Optimierung</h3>

<p>Strategien für optimale Ladezeiten:</p>

<ul>
    <li><strong>Minification:</strong> Entfernen Sie unnötige Zeichen</li>
    <li><strong>Compression:</strong> Nutzen Sie Gzip/Brotli</li>
    <li><strong>Caching:</strong> Setzen Sie lange Cache-Header</li>
    <li><strong>Preloading:</strong> Laden Sie kritische Assets vor</li>
</ul>

<h2 id="beispiele">🚀 Praktische Beispiele</h2>

<h3>Dashboard-Seite</h3>

<p>Komplettes Beispiel für eine Dashboard-Seite:</p>

<div class="code-block">
<pre><code class="language-blade">
<!-- Dashboard Template -->
<!-- extends('layouts/app') -->

<!-- CSS für Dashboard -->
<!-- css('/css/dashboard.css') -->
<!-- css('https://cdn.jsdelivr.net/npm/chart.js/dist/Chart.min.css') -->

<!-- Dashboard-spezifische Styles -->
<!-- css -->
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}
<!-- endcss -->

<!-- JavaScript für Dashboard -->
<!-- js('https://cdn.jsdelivr.net/npm/chart.js') -->
<!-- js('/js/dashboard-charts.js') -->

<!-- Dashboard-Funktionalität -->
<!-- js -->
document.addEventListener('DOMContentLoaded', function() {
    // Echtzeit-Updates
    setInterval(updateDashboardStats, 30000);
    
    // Chart-Initialisierung
    initializeDashboardCharts();
    
    // Live-Benachrichtigungen
    connectWebSocket();
});

function updateDashboardStats() {
    fetch('/api/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            updateStatCards(data);
        });
}
<!-- endjs -->
</code></pre>
</div>

<h3>Blog-Post Seite</h3>

<p>Beispiel für eine Blog-Post Seite mit Syntax-Highlighting:</p>

<div class="code-block">
<pre><code class="language-blade">
<!-- Blog Post Template -->
<!-- extends('layouts/blog') -->

<!-- Syntax-Highlighting CSS -->
<!-- css('https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism.min.css') -->

<!-- Blog-spezifische Styles -->
<!-- css -->
.blog-post {
    max-width: 800px;
    margin: 0 auto;
    line-height: 1.8;
}

.blog-post img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}

.share-buttons {
    position: sticky;
    top: 20px;
    float: left;
    margin-left: -60px;
}
<!-- endcss -->

<!-- Syntax-Highlighting JavaScript -->
<!-- js('https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-core.min.js') -->
<!-- js('https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/plugins/autoloader/prism-autoloader.min.js') -->

<!-- Blog-Funktionalität -->
<!-- js -->
document.addEventListener('DOMContentLoaded', function() {
    // Reading Progress Bar
    const progressBar = document.querySelector('.reading-progress');
    window.addEventListener('scroll', updateProgress);
    
    // Social Sharing
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', sharePost);
    });
    
    // Copy Code Buttons
    addCopyButtons();
});
<!-- endjs -->
</code></pre>
</div>

<h2 id="best-practices">📝 Best Practices</h2>

<div class="example-block">
    <h5>✅ CSS Best Practices</h5>
    <ul>
        <li><strong>Mobile First:</strong> Schreiben Sie CSS für mobile Geräte zuerst</li>
        <li><strong>BEM Methodology:</strong> Verwenden Sie konsistente CSS-Klassen</li>
        <li><strong>CSS Variables:</strong> Nutzen Sie Custom Properties für Themes</li>
        <li><strong>Flexbox/Grid:</strong> Moderne Layout-Techniken verwenden</li>
    </ul>
</div>

<div class="example-block">
    <h5>✅ JavaScript Best Practices</h5>
    <ul>
        <li><strong>Event Delegation:</strong> Effiziente Event-Behandlung</li>
        <li><strong>Debouncing:</strong> Vermeiden Sie übermäßige Event-Ausführung</li>
        <li><strong>Error Handling:</strong> Robuste Fehlerbehandlung implementieren</li>
        <li><strong>Modern ES6+:</strong> Verwenden Sie moderne JavaScript-Features</li>
    </ul>
</div>

<div class="warning-block">
    <h5>⚠️ Wichtige Hinweise</h5>
    <ul>
        <li>CSS wird im HTML-Head eingefügt</li>
        <li>JavaScript wird vor dem schließenden body-Tag eingefügt</li>
        <li>Externe Assets werden vor Inline-Code geladen</li>
        <li>Duplikate werden automatisch vermieden</li>
        <li>Assets werden in der Reihenfolge der Definition geladen</li>
    </ul>
</div>

<h2>🚀 Nächste Schritte</h2>

<div class="row">
    <div class="col-md-4">
        <a href="/docs/middleware" class="btn btn-primary w-100">🛡️ Middleware</a>
    </div>
    <div class="col-md-4">
        <a href="/docs/views" class="btn btn-outline-primary w-100">🎨 View System</a>
    </div>
    <div class="col-md-4">
        <a href="/docs/database" class="btn btn-outline-secondary w-100">🗄️ Database</a>
    </div>
</div>
@endsection

@section('prev-page')
<a href="/docs/views" class="btn btn-outline-secondary">
    ← View System
</a>
@endsection

@section('next-page')
<a href="/docs/middleware" class="btn btn-primary">
    Middleware →
</a>
@endsection
# 🎨 Brick View System - Vollständige Dokumentation

Das Brick View System ist eine leistungsstarke, Blade-ähnliche Template-Engine, die moderne PHP-Features mit einer vertrauten Syntax kombiniert.

## 🚀 Schnellstart

### Installation und Setup

```php
<?php
use Brick\Core\View;

// View-System initialisieren
$view = new View(
    viewPath: '/path/to/app/Views',    // Template-Verzeichnis
    cachePath: '/path/to/cache',       // Cache-Verzeichnis (optional)
    debug: true                        // Debug-Modus (optional)
);

// Globale Variablen setzen
$view->share('appName', 'Meine App');
$view->share('version', '1.0.0');

// Template rendern
$html = $view->render('homepage', [
    'title' => 'Willkommen',
    'user' => $currentUser
]);
```

## 🏗️ Verzeichnisstruktur

```
app/Views/
├── layouts/              # Master-Layouts
│   ├── app.php          # Haupt-Layout
│   └── admin.php        # Admin-Layout
├── components/           # Wiederverwendbare Komponenten
│   ├── alert.php        # Alert-Komponente
│   ├── card.php         # Card-Komponente
│   └── form.php         # Form-Komponente
├── pages/               # Seiten-Templates
│   ├── home.php
│   ├── about.php
│   └── contact.php
└── partials/            # Teilstücke
    ├── header.php
    ├── footer.php
    └── sidebar.php
```

## 📝 Unterstützte Direktiven

### Layout-System

#### @extends - Layout erweitern
```php
@extends('app')  // Erweitert app/Views/layouts/app.php
```

#### @section - Sektion definieren
```php
@section('title')
    Meine Seitentitel
@endsection

@section('content')
    <h1>Hauptinhalt</h1>
    <p>Dies ist der Hauptinhalt der Seite.</p>
@endsection
```

#### @yield - Sektion ausgeben
```php
<!-- Im Layout (layouts/app.php) -->
<title>@yield('title', 'Standardtitel')</title>

<main>
    @yield('content')
</main>

<aside>
    @yield('sidebar', '<p>Standard Sidebar</p>')
</aside>
```

### Template-Einbindung

#### @include - Template einbinden
```php
@include('components.alert')
@include('partials.header')
```

### Variablen-Ausgabe

#### Escaped Ausgabe (Standard)
```php
<h1>{{ $title }}</h1>
<p>{{ $user->name }}</p>
<div>{{ $content ?? 'Kein Inhalt' }}</div>
```

#### Unescaped Ausgabe (Raw HTML)
```php
<div>{!! $htmlContent !!}</div>
<article>{!! $blogPost->content !!}</article>
```

### Kontrollstrukturen

#### Bedingte Anweisungen
```php
@if($user->isLoggedIn())
    <p>Willkommen zurück, {{ $user->name }}!</p>
@elseif($user->isGuest())
    <p>Hallo Gast!</p>
@else
    <p>Bitte melde dich an.</p>
@endif

@if($showAlert)
    @include('components.alert', ['message' => $alertMessage])
@endif
```

#### Schleifen
```php
<!-- Foreach-Schleife -->
@foreach($products as $product)
    <div class="product">
        <h3>{{ $product->name }}</h3>
        <p>{{ $product->price }}€</p>
    </div>
@endforeach

<!-- For-Schleife -->
@for($i = 1; $i <= 10; $i++)
    <li>Element {{ $i }}</li>
@endfor

<!-- While-Schleife -->
@while($condition)
    <p>Aktive Bedingung</p>
@endwhile
```

#### PHP-Code Blöcke
```php
@php
    $total = 0;
    foreach ($items as $item) {
        $total += $item->price;
    }
@endphp

<div>Gesamtsumme: {{ $total }}€</div>
```

## 🧩 Komponenten-System

### Komponenten erstellen

**app/Views/components/button.php:**
```php
<button 
    type="{{ $type ?? 'button' }}" 
    class="btn btn-{{ $variant ?? 'primary' }} {{ $class ?? '' }}"
    {{ $attributes ?? '' }}>
    
    @if($icon ?? false)
        <i class="{{ $icon }}"></i>
    @endif
    
    {{ $text ?? $slot ?? 'Button' }}
</button>
```

### Komponenten verwenden

```php
@include('components.button', [
    'type' => 'submit',
    'variant' => 'success',
    'text' => 'Speichern',
    'icon' => 'fas fa-save'
])
```

## 🎨 Layout-Beispiel

### Master-Layout (layouts/app.php)

```php
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Brick Framework')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">{{ $appName ?? 'Brick App' }}</a>
            @yield('navigation')
        </div>
    </nav>

    <main class="container mt-4">
        @if($flash_message ?? false)
            @include('components.alert', [
                'type' => $flash_type ?? 'info',
                'message' => $flash_message,
                'dismissible' => true
            ])
        @endif

        @yield('content')
    </main>

    <footer class="bg-light mt-5 py-4">
        <div class="container">
            @yield('footer', '<p>&copy; ' . date('Y') . ' Brick Framework</p>')
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
```

### Seiten-Template (home.php)

```php
@extends('app')

@section('title', 'Startseite - Meine App')

@section('styles')
<style>
    .hero { background: #007bff; color: white; padding: 60px 0; }
    .feature-card:hover { transform: translateY(-5px); }
</style>
@endsection

@section('content')
<div class="hero text-center mb-5">
    <h1>Willkommen bei {{ $appName }}</h1>
    <p class="lead">Die moderne PHP-Anwendung</p>
</div>

<div class="row">
    @foreach($features as $feature)
        <div class="col-md-4 mb-4">
            @include('components.card', [
                'title' => $feature['title'],
                'text' => $feature['description'],
                'class' => 'feature-card h-100'
            ])
        </div>
    @endforeach
</div>

@if($showStats)
    <div class="mt-5">
        <h3>Statistiken</h3>
        <div class="row">
            <div class="col-sm-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h2>{{ $stats['users'] }}</h2>
                        <p>Benutzer</p>
                    </div>
                </div>
            </div>
            <!-- Weitere Statistiken... -->
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
document.querySelectorAll('.feature-card').forEach(card => {
    card.style.transition = 'transform 0.3s ease';
});
</script>
@endsection
```

## ⚡ Performance & Caching

### Caching aktivieren

```php
$view = new View(
    viewPath: '/app/Views',
    cachePath: '/app/cache/views',  // Templates werden automatisch gecacht
    debug: false                    // Produktiv: false für bessere Performance
);
```

### Cache verwalten

```php
// Cache-Statistiken abrufen
$stats = $view->getStats();
echo "Cache Hits: " . $stats['cache_hits'];
echo "Cache Misses: " . $stats['cache_misses'];

// Cache leeren
$view->clearCache();
```

### Performance optimieren

```php
// Globale Variablen einmalig setzen
$view->shareAll([
    'appName' => 'Meine App',
    'version' => '1.0.0',
    'currentYear' => date('Y'),
    'user' => $currentUser
]);

// Template mehrfach rendern (Cache wird genutzt)
$html1 = $view->render('template', $data1);
$html2 = $view->render('template', $data2);  // Aus Cache
```

## 🔧 Erweiterte Features

### Factory-Pattern

```php
// Einfache Erstellung
$view = View::create('/app/Views', '/cache', true);
```

### Debug-Modus

```php
$view = new View('/app/Views', '/cache', debug: true);

// Debug-Informationen abrufen
$debugInfo = $view->getDebugInfo();
print_r($debugInfo);
```

### Fehlerbehandlung

```php
try {
    $html = $view->render('template', $data);
} catch (InvalidArgumentException $e) {
    // Template nicht gefunden
    $html = $view->render('errors.404');
} catch (RuntimeException $e) {
    // Render-Fehler
    $html = $view->render('errors.500', ['error' => $e->getMessage()]);
}
```

## 🛠️ Praktische Beispiele

### Blog-System

```php
// Controller
$view = new View('/app/Views');
$view->share('siteName', 'Mein Blog');

$html = $view->render('blog.post', [
    'post' => $blogPost,
    'comments' => $comments,
    'relatedPosts' => $relatedPosts
]);
```

**blog/post.php:**
```php
@extends('blog.layout')

@section('title', $post->title . ' - ' . $siteName)
@section('description', $post->excerpt)

@section('content')
<article>
    <header>
        <h1>{{ $post->title }}</h1>
        <div class="meta">
            Von {{ $post->author->name }} am {{ $post->created_at->format('d.m.Y') }}
        </div>
    </header>

    <div class="content">
        {!! $post->content !!}
    </div>

    <footer>
        @foreach($post->tags as $tag)
            <span class="badge bg-secondary">{{ $tag->name }}</span>
        @endforeach
    </footer>
</article>

@if($comments->count() > 0)
    <section class="comments mt-5">
        <h3>Kommentare ({{ $comments->count() }})</h3>
        
        @foreach($comments as $comment)
            <div class="comment mb-3 p-3 border rounded">
                <strong>{{ $comment->author }}</strong>
                <small class="text-muted">{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                <p class="mt-2 mb-0">{{ $comment->text }}</p>
            </div>
        @endforeach
    </section>
@endif

@if($relatedPosts->count() > 0)
    <aside class="related-posts mt-5">
        <h4>Ähnliche Artikel</h4>
        <div class="row">
            @foreach($relatedPosts as $related)
                <div class="col-md-6 mb-3">
                    @include('blog.components.post-card', ['post' => $related])
                </div>
            @endforeach
        </div>
    </aside>
@endif
@endsection
```

### Dashboard mit dynamischen Komponenten

```php
// Controller
$widgets = [
    ['type' => 'stats', 'title' => 'Benutzer', 'value' => $userCount, 'icon' => 'users'],
    ['type' => 'chart', 'title' => 'Verkäufe', 'data' => $salesData],
    ['type' => 'list', 'title' => 'Neueste Bestellungen', 'items' => $recentOrders]
];

$html = $view->render('dashboard', [
    'widgets' => $widgets,
    'user' => $currentUser
]);
```

**dashboard.php:**
```php
@extends('admin.layout')

@section('title', 'Dashboard - ' . $user->name)

@section('content')
<div class="dashboard">
    <h1>Dashboard</h1>
    <p>Willkommen zurück, {{ $user->name }}!</p>

    <div class="row">
        @foreach($widgets as $widget)
            <div class="col-lg-4 col-md-6 mb-4">
                @if($widget['type'] === 'stats')
                    @include('admin.widgets.stats', $widget)
                @elseif($widget['type'] === 'chart')
                    @include('admin.widgets.chart', $widget)
                @elseif($widget['type'] === 'list')
                    @include('admin.widgets.list', $widget)
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
```

## 🧪 Testing

Das View-System wird automatisch mit dem Brick Test Runner getestet:

```bash
# Alle Tests ausführen
php tests/brick-test-runner.php

# Nur View-Tests
php vendor/bin/phpunit tests/ViewTest.php

# View-System schnell testen
php tests/view-test.php
```

## 🔍 Debugging

### Template-Debugging

```php
// Debug-Modus aktivieren
$view = new View('/app/Views', '/cache', debug: true);

// Verfügbare Templates anzeigen
$debugInfo = $view->getDebugInfo();
var_dump($debugInfo['available_templates']);

// Performance-Statistiken
$stats = $view->getStats();
echo "Render-Zeit: " . ($stats['render_time'] * 1000) . "ms";
```

### Fehlerbehandlung im Template

```php
@php
    if (!isset($requiredVar)) {
        throw new InvalidArgumentException('Variable $requiredVar ist erforderlich');
    }
@endphp

@if($debugMode)
    <div class="debug-info">
        <h4>Debug-Informationen</h4>
        <pre>{{ json_encode(get_defined_vars(), JSON_PRETTY_PRINT) }}</pre>
    </div>
@endif
```

## 🚀 Best Practices

### 1. Template-Organisation
- Nutze Layouts für gemeinsame Struktur
- Erstelle Komponenten für wiederverwendbare Elemente
- Organisiere Templates in logischen Ordnern

### 2. Performance
- Aktiviere Caching in der Produktion
- Nutze globale Variablen für häufig verwendete Daten
- Vermeide komplexe Logik in Templates

### 3. Sicherheit
- Verwende `{{ }}` für Benutzer-Eingaben (automatisches Escaping)
- Nutze `{!! !!}` nur für vertrauenswürdige HTML-Inhalte
- Validiere Daten vor der Übergabe an Templates

### 4. Wartbarkeit
- Halte Templates einfach und lesbar
- Kommentiere komplexe Logik
- Verwende sprechende Variablennamen

---

**🎉 Das Brick View System bietet alles für moderne, sichere und performante Templates!**
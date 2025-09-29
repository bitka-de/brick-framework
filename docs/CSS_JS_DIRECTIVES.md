# 🎨 CSS & JavaScript Direktiven - Erweiterung des Brick View Systems

## ✅ Implementierte Features

Das Brick View System wurde erfolgreich um umfassende CSS- und JavaScript-Direktiven erweitert, die automatisch in den HTML-Header eingefügt werden.

## 📝 Neue Direktiven

### 🎨 CSS-Direktiven

#### Externe CSS-Dateien
```php
@css('path/to/styles.css')
@css('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css')
```
**Ergebnis:** `<link rel="stylesheet" href="path/to/styles.css">`

#### Inline CSS-Styles
```php
@css
    body {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        font-family: 'Arial', sans-serif;
    }
    
    .my-class {
        color: #333;
        padding: 1rem;
    }
@endcss
```
**Ergebnis:** 
```html
<style>
body {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    font-family: 'Arial', sans-serif;
}

.my-class {
    color: #333;
    padding: 1rem;
}
</style>
```

### ⚡ JavaScript-Direktiven

#### Externe JavaScript-Dateien
```php
@js('path/to/script.js')
@js('https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js')
```
**Ergebnis:** `<script src="path/to/script.js"></script>`

#### JavaScript mit Attributen
```php
@js('script.js', ['defer'])
@js('analytics.js', ['async', 'defer'])
```
**Ergebnis:** 
```html
<script src="script.js" defer></script>
<script src="analytics.js" async defer></script>
```

#### Inline JavaScript
```php
@js
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded!');
        
        // Ihre JavaScript-Logik hier
        const button = document.getElementById('myButton');
        button.addEventListener('click', function() {
            alert('Button clicked!');
        });
    });
@endjs
```
**Ergebnis:**
```html
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded!');
    
    // Ihre JavaScript-Logik hier
    const button = document.getElementById('myButton');
    button.addEventListener('click', function() {
        alert('Button clicked!');
    });
});
</script>
```

## 🏗️ Technische Implementierung

### Automatische Header-Platzierung
- Alle CSS-Assets werden automatisch vor `</head>` eingefügt
- JavaScript-Assets werden ebenfalls im Header platziert (bessere Performance)
- Duplikate werden automatisch vermieden
- Assets werden in der Reihenfolge ihrer Definition eingefügt

### Performance-Optimierungen
- **Duplikats-Vermeidung:** Gleiche Dateien werden nur einmal eingefügt
- **Attribut-Unterstützung:** `defer`, `async` für bessere Performance
- **Caching:** Kompilierte Templates mit Assets werden gecacht
- **Memory-Efficient:** Assets werden nur während der Kompilierung gesammelt

## 📖 Verwendungsbeispiele

### Beispiel 1: Bootstrap-Integration
```php
@extends('app')

@section('title', 'Meine Seite')

{{-- Bootstrap CSS --}}
@css('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css')

{{-- Custom Styles --}}
@css
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 0;
    }
@endcss

{{-- Bootstrap JS --}}
@js('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js')

{{-- Custom JavaScript --}}
@js
    document.addEventListener('DOMContentLoaded', function() {
        // Bootstrap Toast initialisieren
        const toastElList = [].slice.call(document.querySelectorAll('.toast'));
        const toastList = toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl);
        });
    });
@endjs

@section('content')
<div class="hero-section text-center">
    <h1>Willkommen!</h1>
    <p class="lead">Bootstrap mit automatischem Asset-Management</p>
</div>
@endsection
```

### Beispiel 2: Animation & Interaktivität
```php
@extends('app')

{{-- GSAP für Animationen --}}
@js('https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js')

{{-- SweetAlert für schöne Dialoge --}}
@js('https://unpkg.com/sweetalert/dist/sweetalert.min.js', ['defer'])

{{-- Custom Animation Styles --}}
@css
    .animate-box {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.6s ease;
    }
    
    .animate-box.visible {
        opacity: 1;
        transform: translateY(0);
    }
@endcss

{{-- Animation JavaScript --}}
@js
    document.addEventListener('DOMContentLoaded', function() {
        // GSAP Timeline Animation
        const tl = gsap.timeline();
        tl.from('.hero-title', { duration: 1, y: 50, opacity: 0 })
          .from('.hero-subtitle', { duration: 1, y: 30, opacity: 0 }, '-=0.5')
          .from('.hero-button', { duration: 1, scale: 0, ease: 'back.out(1.7)' }, '-=0.3');
        
        // SweetAlert für Button-Klicks
        document.querySelectorAll('.sweet-alert-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                swal("Erfolg!", "Animation und Dialoge funktionieren perfekt!", "success");
            });
        });
    });
@endjs

@section('content')
<div class="hero-section">
    <h1 class="hero-title">Animated Content</h1>
    <p class="hero-subtitle">Mit GSAP und SweetAlert</p>
    <button class="btn btn-primary hero-button sweet-alert-btn">Test Animation</button>
</div>
@endsection
```

## 🔧 Programmatische Asset-Verwaltung

Zusätzlich zu den Template-Direktiven können Assets auch programmatisch hinzugefügt werden:

```php
// Im Controller oder Service
$view = new View();

// CSS hinzufügen
$view->addCss('/assets/css/app.css');
$view->addInlineCss('.dynamic { color: blue; }');

// JavaScript hinzufügen
$view->addJs('/assets/js/app.js', ['defer']);
$view->addInlineJs('console.log("Dynamic JS");');

// Template rendern
$html = $view->render('template', $data);
```

## 🧪 Testing

Die CSS/JS-Direktiven wurden umfassend getestet:

```bash
# CSS/JS Test ausführen
php simple-css-js-test.php

# Ergebnis:
# ✅ Contains test.css: YES
# ✅ Contains inline CSS: YES  
# ✅ Contains test.js: YES
# ✅ Contains inline JS: YES
```

### Live-Demo verfügbar
```bash
# Server starten
php -S localhost:8000

# Demo öffnen
http://localhost:8000/demo/css-js
```

## 📊 Performance-Messung

```php
$stats = $view->getStats();
echo "Kompilierte Templates: " . $stats['compiled_templates'];
echo "Render-Zeit: " . number_format($stats['render_time'] * 1000, 2) . "ms";
```

## 🎯 VS Code-Integration

Neue Code-Snippets für bessere Entwicklererfahrung:

- `@css` → Externe CSS-Datei
- `@cssinline` → Inline CSS-Block
- `@js` → Externe JS-Datei mit Attributen
- `@jsinline` → Inline JavaScript-Block

## 🚀 Vorteile

1. **🎯 Automatische Platzierung:** Kein manuelles Verwalten von Header-Includes
2. **🔄 Duplikats-Vermeidung:** Gleiche Assets werden nur einmal geladen
3. **⚡ Performance:** Defer/Async-Attribute für optimierte Ladezeiten
4. **🧩 Modularität:** Assets können in jedem Template definiert werden
5. **💾 Caching:** Kompilierte Templates mit Assets werden gecacht
6. **🎨 Entwicklerfreundlich:** Intuitive Syntax ähnlich zu Blade
7. **🔧 Flexibilität:** Sowohl Template- als auch programmatische API

---

**🎉 Das Brick View System unterstützt jetzt vollständiges Asset-Management mit automatischer Header-Injektion!**
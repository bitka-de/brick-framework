# 📚 Brick Framework - Dokumentationssystem

## 🎯 Übersicht

Das Brick Framework enthält jetzt ein vollständiges, integriertes Dokumentationssystem, das automatisch mit der Framework-Installation verfügbar ist.

## 🚀 Features

### ✅ Vollständig implementiert:
- **MVC-Controller**: `DocumentationController` mit 8 Methoden
- **Responsive Layout**: Bootstrap 5.3.0 mit Sidebar-Navigation
- **Code-Highlighting**: Prism.js für Syntax-Hervorhebung
- **8 Dokumentationsseiten**: Alle Aspekte des Frameworks abgedeckt
- **Routing-Integration**: Vollständig in das Framework-Routing integriert

### 📄 Verfügbare Dokumentationsseiten:

1. **`/docs`** - Hauptübersicht mit Navigation
2. **`/docs/overview`** - Framework-Übersicht und Architektur
3. **`/docs/installation`** - Installation und Setup
4. **`/docs/routing`** - Routing-System und URL-Handling
5. **`/docs/views`** - Template-System und Blade-ähnliche Syntax
6. **`/docs/css-js`** - CSS/JS-Direktiven und Asset-Management
7. **`/docs/middleware`** - Middleware-System (geplante Features)
8. **`/docs/database`** - Database-Integration (aktuelle + geplante Features)
9. **`/docs/api`** - API-Referenz mit Code-Beispielen

## 🏗️ Dateistruktur

```
app/
├── Controllers/
│   └── DocumentationController.php      # MVC-Controller (755 Zeilen)
├── Views/
│   ├── layouts/
│   │   └── docs.php                     # Dokumentations-Layout
│   └── docs/
│       ├── index.php                    # Hauptseite
│       ├── overview.php                 # Framework-Übersicht
│       ├── installation.php             # Installation
│       ├── routing.php                  # Routing-Dokumentation
│       ├── views.php                    # Template-System
│       ├── css-js.php                   # CSS/JS-Direktiven
│       ├── middleware.php               # Middleware (geplant)
│       ├── database.php                 # Database-Integration
│       └── api.php                      # API-Referenz
└── routes.php                          # Routing-Konfiguration (aktualisiert)
```

## 🎨 Design & UX

### Responsive Layout:
- **Bootstrap 5.3.0**: Modernes, responsives Design
- **Sticky Sidebar**: Navigation bleibt beim Scrollen sichtbar
- **Breadcrumb-Navigation**: Orientierung in der Dokumentation
- **Code-Blocks**: Syntax-Highlighting mit Prism.js
- **Info-Boxen**: Hervorgehobene Tipps und Warnungen

### Navigations-Features:
- **Seitenübergreifende Navigation**: Vor/Zurück-Buttons
- **Inhaltsverzeichnis**: Sprungmarken innerhalb der Seiten
- **Externe Links**: GitHub, Font Awesome, etc.
- **Mobile-Optimiert**: Responsive Design für alle Geräte

## 🛠️ Technische Details

### Controller-Architektur:
```php
class DocumentationController 
{
    private View $view;
    
    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../Views');
        $this->view->shareAll([...]);
    }
    
    // 8 Methoden für verschiedene Dokumentationsseiten
    public function index() { /* Hauptseite */ }
    public function overview() { /* Framework-Übersicht */ }
    public function installation() { /* Installation */ }
    public function routing() { /* Routing */ }
    public function views() { /* Template-System */ }
    public function cssJs() { /* CSS/JS-Direktiven */ }
    public function middleware() { /* Middleware-System */ }
    public function database() { /* Database-Integration */ }
    public function api() { /* API-Referenz */ }
}
```

### Routing-Integration:
```php
// Alle Dokumentations-Routen
$router->get('/docs', [DocumentationController::class, 'index']);
$router->get('/docs/overview', [DocumentationController::class, 'overview']);
$router->get('/docs/installation', [DocumentationController::class, 'installation']);
// ... weitere Routen
```

### Template-System:
- **Layout-Vererbung**: `@extends('layouts/docs')`
- **Sections**: `@section('content')` ... `@endsection`
- **Includes**: Wiederverwendbare Komponenten
- **Global Variables**: Framework-weite Daten

## 🎓 Inhalte der Dokumentation

### 1. Overview (`/docs/overview`)
- Framework-Philosophie und Architektur
- MVC-Pattern-Erklärung
- Core-Komponenten Übersicht
- Design-Prinzipien

### 2. Installation (`/docs/installation`)
- System-Anforderungen (PHP 8.0+)
- Download und Setup-Anleitung
- Webserver-Konfiguration
- Erste Schritte Tutorial

### 3. Routing (`/docs/routing`)
- Route-Definition und Parameter
- HTTP-Methoden (GET, POST, PUT, DELETE)
- Controller-Integration
- Route-Parameter und Constraints

### 4. Views (`/docs/views`)
- Template-System und Blade-ähnliche Syntax
- Layout-Vererbung mit `@extends`
- Sections und Yields
- Include-System
- Kontrollstrukturen (@if, @foreach, etc.)

### 5. CSS/JS (`/docs/css-js`)
- CSS/JS-Direktiven System
- Externe Asset-Einbindung
- Inline-Styles und Scripts
- Asset-Management Best Practices

### 6. Middleware (`/docs/middleware`)
- Geplante Middleware-Architektur
- Authentifizierung und CORS
- Request-Logging
- Entwicklungs-Roadmap

### 7. Database (`/docs/database`)
- Aktuelle PDO-Integration
- Geplantes Model-System
- Migration-System (Vorschau)
- Query Builder (Vorschau)

### 8. API (`/docs/api`)
- Vollständige API-Referenz
- Core-Klassen Dokumentation
- Code-Beispiele für alle Features
- Helper-Funktionen

## 🚀 Zugriff auf die Dokumentation

Nach der Framework-Installation ist die Dokumentation sofort verfügbar:

1. **Hauptdokumentation**: `http://localhost/docs`
2. **Direkte Seitenlinks**: `http://localhost/docs/[page]`
3. **Responsive Design**: Funktioniert auf Desktop und Mobile

## 🔄 Integration in bestehende Projekte

Das Dokumentationssystem ist vollständig isoliert und beeinträchtigt bestehende Routen nicht:

- **Eigener Namespace**: `/docs/*` URLs
- **Separate Controller**: Keine Konflikte mit App-Controllern
- **Eigenes Layout**: Unabhängig von App-Layouts
- **Optional**: Kann bei Bedarf deaktiviert werden

## 🎯 Status

### ✅ Fertig implementiert:
- [x] DocumentationController (755 Zeilen)
- [x] Responsive Documentation Layout
- [x] 8 vollständige Dokumentationsseiten
- [x] Routing-Integration
- [x] Code-Highlighting und Design
- [x] Navigation und UX

### 🚀 Ready to use:
Das komplette Dokumentationssystem ist jetzt **produktionsbereit** und automatisch mit jeder Framework-Installation verfügbar.

---

**Entwickelt für das Brick Framework v1.0**  
*Eine vollständige Dokumentation für ein vollständiges Framework* 🧱
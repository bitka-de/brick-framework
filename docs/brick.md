# 🧱 Das Brick Framework - Einführung für Entwickler

**Eine umfassende Anleitung für deinen ersten Blick auf das Brick Framework**

## 🎯 Was ist das Brick Framework?

Das Brick Framework ist ein modernes, hochperformantes PHP-Framework, das sich auf **Einfachheit**, **Geschwindigkeit** und **Entwicklerfreundlichkeit** konzentriert. Es wurde mit PHP 8+ entwickelt und nutzt die neuesten Sprachfeatures für optimale Performance und Typsicherheit.

### ✨ Warum Brick Framework?

- **🚀 Schnell & Schlank**: Minimaler Overhead, maximale Performance
- **📚 Einfach zu lernen**: Klare Struktur ohne versteckte Magie
- **🔧 Entwicklerfreundlich**: Ausgezeichnete Debugging-Tools und hilfreiche Fehlermeldungen
- **🧪 Robust getestet**: 91 Tests mit 213 Assertions für Zuverlässigkeit
- **🛡️ Sicher**: Eingebaute Sicherheitsfeatures und Best Practices

---

## 🏗️ Architektur-Überblick

Das Framework besteht aus drei Kernkomponenten:

```
🧱 Brick Framework
├── 🌐 HTTP-System      (Request/Response Handling)
├── 🔀 Router-System    (URL-Routing & Parameter)
└── 🧪 Test-Framework   (Qualitätssicherung)
```

### 1. 🌐 HTTP-System
**Was es macht**: Verarbeitet eingehende HTTP-Anfragen und erstellt Antworten

**Hauptklassen**:
- `Brick\Http\Request` - Eingangsdaten (GET, POST, Headers, Sessions)
- `Brick\Http\Response` - Ausgabedaten (JSON, HTML, Redirects, CORS)

### 2. 🔀 Router-System  
**Was es macht**: Verbindet URLs mit deinem Code (Controller-Funktionen)

**Hauptklasse**:
- `Brick\Core\Router` - Hochperformantes Routing mit O(1) für statische Routen

### 3. 🧪 Test-Framework
**Was es macht**: Stellt sicher, dass alles korrekt funktioniert

**Tools**:
- Eigener Test-Runner mit schöner Ausgabe
- 91 automatische Tests für alle Komponenten

---

## 🚀 Schnellstart - In 5 Minuten produktiv

### Schritt 1: Installation

```bash
# Framework herunterladen
git clone https://github.com/bitka-de/brick-framework.git
cd brick-framework

# Abhängigkeiten installieren
composer install

# Testen, ob alles funktioniert
php tests/brick-test-runner.php
```

### Schritt 2: Deine erste Anwendung

Erstelle `index.php` im Projektroot:

```php
<?php
require_once 'vendor/autoload.php';

use Brick\Http\Request;
use Brick\Http\Response;
use Brick\Core\Router;

// Framework-Komponenten initialisieren
$request = new Request();
$response = new Response();
$router = new Router();

// Einfache Route: Startseite
$router->get('/', function() use ($response) {
    return $response->json([
        'message' => 'Willkommen beim Brick Framework!',
        'version' => '1.0.0',
        'status' => 'ready'
    ]);
});

// Dynamische Route: Benutzer-Info
$router->get('/user/{id}', function($params) use ($response) {
    return $response->json([
        'user_id' => $params['id'],
        'message' => "Hallo, Benutzer {$params['id']}!"
    ]);
});

// Route verarbeiten
$result = $router->dispatch($request->method(), $request->uri());

if ($result['found']) {
    // Route gefunden - Handler ausführen
    $result['handler']($result['params'] ?? []);
} else {
    // 404 - Seite nicht gefunden
    $response->setStatusCode(404)->json(['error' => 'Seite nicht gefunden']);
}
```

### Schritt 3: Server starten

```bash
# PHP Development Server starten
php -S localhost:8080 -t public/

# Im Browser öffnen:
# http://localhost:8080/        → Startseite
# http://localhost:8080/user/42 → Benutzer-Info
```

---

## 📖 Wichtige Konzepte verstehen

### 🌐 Request-Handling

```php
$request = new Request();

// GET-Parameter abrufen
$name = $request->query('name', 'Standardwert');

// POST-Daten abrufen
$email = $request->post('email');

// HTTP-Header lesen
$userAgent = $request->header('User-Agent');

// Session-Daten
$userId = $request->session('user_id');
```

### 📤 Response-Generierung

```php
$response = new Response();

// JSON-Response
$response->json(['message' => 'Erfolgreich']);

// HTML-Response
$response->html('<h1>Willkommen!</h1>');

// Redirect
$response->redirect('/dashboard');

// Fehler-Response
$response->setStatusCode(400)->json(['error' => 'Ungültige Eingabe']);
```

### 🔀 Router-Features

```php
$router = new Router();

// Basis-Routen
$router->get('/products', $handler);
$router->post('/products', $createHandler);
$router->put('/products/{id}', $updateHandler);
$router->delete('/products/{id}', $deleteHandler);

// Routengruppen (für API-Versioning)
$router->group('/api/v1', function($router) {
    $router->get('/users', $usersHandler);
    $router->get('/orders', $ordersHandler);
});

// Parameter-Validierung
$router->get('/user/{id:\d+}', $handler); // Nur Zahlen
$router->get('/slug/{name:[a-z-]+}', $handler); // Nur Buchstaben und Bindestriche
```

---

## 🛠️ Entwickler-Tools

### 🧪 Tests ausführen

```bash
# Schnelle Übersicht
php tests/brick-test-runner.php

# Detaillierte Ausgabe mit allen Testfällen
php tests/brick-test-runner.php --details

# Nur Router-Tests
vendor/bin/phpunit tests/RouterTest.php
```

### 🔍 Router-Debugging

```php
$router = new Router();
// ... Routen hinzufügen ...

// Alle registrierten Routen anzeigen
$router->dumpRoutes();

// Performance-Statistiken
$stats = $router->getStats();
echo "Routen insgesamt: {$stats['total_routes']}\n";
echo "Statische Routen: {$stats['static_routes']}\n";
echo "Dynamische Routen: {$stats['dynamic_routes']}\n";
```

---

## 🎯 Praktische Beispiele

### Beispiel 1: Einfache REST-API

```php
<?php
require_once 'vendor/autoload.php';

use Brick\Http\Request;
use Brick\Http\Response;
use Brick\Core\Router;

$request = new Request();
$response = new Response();
$router = new Router();

// Dummy-Datenbank
$users = [
    1 => ['name' => 'Max Mustermann', 'email' => 'max@example.com'],
    2 => ['name' => 'Anna Schmidt', 'email' => 'anna@example.com']
];

// API-Routen
$router->group('/api', function($router) use ($response, $users) {
    
    // Alle Benutzer auflisten
    $router->get('/users', function() use ($response, $users) {
        return $response->json($users);
    });
    
    // Einzelnen Benutzer abrufen
    $router->get('/users/{id:\d+}', function($params) use ($response, $users) {
        $id = (int)$params['id'];
        if (isset($users[$id])) {
            return $response->json($users[$id]);
        }
        return $response->setStatusCode(404)->json(['error' => 'Benutzer nicht gefunden']);
    });
    
    // Neuen Benutzer erstellen
    $router->post('/users', function() use ($response, $request) {
        $name = $request->post('name');
        $email = $request->post('email');
        
        if (!$name || !$email) {
            return $response->setStatusCode(400)->json(['error' => 'Name und E-Mail erforderlich']);
        }
        
        return $response->setStatusCode(201)->json([
            'message' => 'Benutzer erstellt',
            'user' => ['name' => $name, 'email' => $email]
        ]);
    });
});

// Request verarbeiten
$result = $router->dispatch($request->method(), $request->uri());
if ($result['found']) {
    $result['handler']($result['params'] ?? []);
} else {
    $response->setStatusCode(404)->json(['error' => 'Endpoint nicht gefunden']);
}
```

### Beispiel 2: Webanwendung mit Sessions

```php
<?php
require_once 'vendor/autoload.php';

use Brick\Http\Request;
use Brick\Http\Response;
use Brick\Core\Router;

$request = new Request();
$response = new Response();
$router = new Router();

// Login-Seite anzeigen
$router->get('/login', function() use ($response) {
    $html = '
    <form method="POST" action="/login">
        <input type="text" name="username" placeholder="Benutzername" required>
        <input type="password" name="password" placeholder="Passwort" required>
        <button type="submit">Anmelden</button>
    </form>';
    return $response->html($html);
});

// Login verarbeiten
$router->post('/login', function() use ($request, $response) {
    $username = $request->post('username');
    $password = $request->post('password');
    
    // Einfache Authentifizierung (in der Realität: Datenbank/Hash-Vergleich)
    if ($username === 'admin' && $password === 'secret') {
        $request->setSession('user_id', 1);
        $request->setSession('username', $username);
        return $response->redirect('/dashboard');
    }
    
    return $response->setStatusCode(401)->html('<p>Ungültige Anmeldedaten</p>');
});

// Dashboard (nur für angemeldete Benutzer)
$router->get('/dashboard', function() use ($request, $response) {
    $userId = $request->session('user_id');
    
    if (!$userId) {
        return $response->redirect('/login');
    }
    
    $username = $request->session('username');
    return $response->html("<h1>Willkommen im Dashboard, {$username}!</h1>");
});

// Logout
$router->post('/logout', function() use ($request, $response) {
    $request->setSession('user_id', null);
    $request->setSession('username', null);
    return $response->redirect('/login');
});

// Request verarbeiten
$result = $router->dispatch($request->method(), $request->uri());
if ($result['found']) {
    $result['handler']($result['params'] ?? []);
} else {
    $response->setStatusCode(404)->html('<h1>404 - Seite nicht gefunden</h1>');
}
```

---

## 🔧 Erweiterte Features

### 🛡️ Sicherheit

Das Framework bietet eingebaute Sicherheitsfeatures:

```php
$response = new Response();

// CORS-Header setzen
$response->setCorsHeaders([
    'Access-Control-Allow-Origin' => 'https://myapp.com',
    'Access-Control-Allow-Methods' => 'GET,POST,PUT,DELETE',
    'Access-Control-Allow-Headers' => 'Content-Type,Authorization'
]);

// Sicherheitsheader
$response->setSecurityHeaders([
    'X-Frame-Options' => 'DENY',
    'X-XSS-Protection' => '1; mode=block',
    'X-Content-Type-Options' => 'nosniff'
]);
```

### ⚡ Performance-Optimierung

```php
$router = new Router();

// Router nutzt automatisch:
// - O(1) Lookup für statische Routen
// - Optimierte Regex-Kompilierung für dynamische Routen
// - Intelligente Route-Gruppierung

// Performance-Statistiken abrufen
$stats = $router->getStats();
echo "Durchschnittliche Lookup-Zeit: " . $stats['avg_lookup_time'] . "ms\n";
```

---

## 📁 Projekt-Struktur verstehen

```
brick-framework/
├── 📁 app/                    # Deine Anwendungslogik
├── 📁 brick/                  # Framework-Kernkomponenten
│   ├── 📁 Core/              # Router, Container, etc.
│   └── 📁 Http/              # Request/Response-Klassen
├── 📁 config/                # Konfigurationsdateien
├── 📁 docs/                  # Dokumentation
│   ├── README.md             # Dokumentations-Index
│   ├── brick.md              # Diese Datei
│   ├── ROUTER_ENHANCEMENT_SUMMARY.md
│   └── TEST_SUITE_OVERVIEW.md
├── 📁 public/                # Web-Root (index.php, Assets)
├── 📁 tests/                 # Automatische Tests
│   ├── brick-test-runner.php # Eigener Test-Runner
│   ├── RouterTest.php        # Router-Tests
│   ├── RequestTest.php       # Request-Tests
│   └── ResponseTest.php      # Response-Tests
├── composer.json             # Abhängigkeiten
└── README.md                 # Projekt-Übersicht
```

---

## 🎓 Nächste Schritte

### 1. **Experimentieren**
- Probiere die Beispiele aus
- Modifiziere die Routen
- Teste verschiedene HTTP-Methoden

### 2. **Dokumentation erkunden**
- [Router-Verbesserungen](ROUTER_ENHANCEMENT_SUMMARY.md) - Erweiterte Router-Features
- [Test-Übersicht](TEST_SUITE_OVERVIEW.md) - Vollständige Testdokumentation
- [Hauptdokumentation](README.md) - Dokumentations-Index

### 3. **Eigene Anwendung entwickeln**
- Erstelle deine eigenen Controller
- Implementiere Datenbankanbindung
- Füge Middleware hinzu

### 4. **Tests schreiben**
```bash
# Bestehende Tests als Beispiel ansehen
php tests/brick-test-runner.php --details

# Eigene Tests hinzufügen
cp tests/RouterTest.php tests/MyFeatureTest.php
```

---

## 🆘 Hilfe & Support

### 🐛 Probleme lösen

1. **Tests nicht erfolgreich?**
   ```bash
   # Detaillierte Testausgabe anzeigen
   php tests/brick-test-runner.php --details
   ```

2. **Router funktioniert nicht?**
   ```php
   // Router-Debug aktivieren
   $router->dumpRoutes(); // Zeigt alle registrierten Routen
   ```

3. **HTTP-Probleme?**
   ```php
   // Request-Debugging
   var_dump($request->all()); // Alle Request-Daten anzeigen
   ```

### 📚 Weitere Ressourcen

- **Framework-Code**: Schaue dir die Klassen in `brick/` an
- **Test-Beispiele**: `tests/` enthält Verwendungsbeispiele für alle Features
- **Performance-Benchmarks**: In den Router-Tests findest du Performance-Messungen

---

## 💡 Pro-Tipps für Entwickler

### 🎯 Best Practices

1. **Verwende Typisierung**:
   ```php
   function handleUser(int $userId, Request $request): Response {
       // PHP 8+ Features nutzen
   }
   ```

2. **Teste deine Routen**:
   ```php
   // Eigene Tests schreiben
   $this->assertTrue($router->dispatch('GET', '/api/users')['found']);
   ```

3. **Nutze Routengruppen**:
   ```php
   $router->group('/admin', function($router) {
       // Alle Admin-Routen hier
   });
   ```

4. **Sicherheit beachten**:
   ```php
   // Immer Eingaben validieren
   $email = filter_var($request->post('email'), FILTER_VALIDATE_EMAIL);
   ```

### 🚀 Performance-Tipps

- Statische Routen werden automatisch O(1) abgefragt
- Routengruppen reduzieren Regex-Komplexität
- Der Test-Runner zeigt Performance-Benchmarks

---

**🎉 Herzlichen Glückwunsch! Du bist jetzt bereit, mit dem Brick Framework zu entwickeln.**

**Viel Erfolg bei deinen Projekten! 🧱✨**
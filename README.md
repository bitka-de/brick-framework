# 🧱 Brick Framework

## Über Brick-Framework

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue.svg)](https://php.net)
[![Framework Version](https://img.shields.io/badge/Version-1.0-green.svg)](https://github.com/bitka-de/brick-framework)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

Brick ist ein kleines, schnelles und einfach zu bedienendes PHP-Framework für kleine bis mittlere Webprojekte. Es setzt auf eine schlanke, modulare Struktur, moderne PHP 8+ Features und schnelle Einrichtung.

**Vorteile von Brick:**
- Minimalistisches Design für schnellen Einstieg
- Klare und modulare Projektstruktur
- Einfache Erweiterbarkeit und Wartbarkeit
- Ideal für kleine bis mittlere Webprojekte

Brick eignet sich besonders für Entwickler, die Übersichtlichkeit, Effizienz und moderne PHP-Standards schätzen.

## 🚀 Schnellstart

### Installation

1. **Repository klonen:**
    ```bash
    git clone https://github.com/bitka-de/brick-framework.git
    cd brick-framework
    ```

2. **Dependencies installieren:**
    ```bash
    composer install
    ```

3. **Development Server starten:**
    ```bash
    ./cli/brick-cli.sh
    # Wähle Option 5: "Start Development Server"
    ```

4. **Framework testen:**
    ```bash
    php cli/brick:test
    ```

### Projektstruktur – Brick-Framework v1.0

```
📦 Brick-Framework/
├── 🌐 public/
│   └── index.php
├── 🧱 brick/
│   ├── Core/
│   │   ├── Autoloader.php
│   │   ├── Router.php
│   │   └── bootstrap.php
│   └── Http/
│       ├── Request.php        # geplant
│       └── Response.php       # geplant
├── ⚙️ config/
│   └── config.php             # geplant
├── 🗂️ app/
│   ├── Controllers/
│   │   └── HomeController.php # geplant
│   ├── Views/
│   │   └── home.php           # geplant
│   └── routes.php             # geplant
├── 🧪 tests/
│   ├── brick/
│   └── app/
├── ⚙️ phpunit.xml
├── 📦 composer.json
└── 📁 vendor/                  # falls Composer genutzt wird
```

## 📖 Framework verwenden

### HTTP Request Handling

Das Brick Framework bietet ein mächtiges Request-System mit Session-, Cookie- und Header-Management:

```php
<?php
use Brick\Http\Request;

require_once 'brick/Core/Autoloader.php';
$autoloader = new \Brick\Core\Autoloader();
$autoloader->addNamespace('Brick', __DIR__ . '/brick');
$autoloader->register();

$request = new Request();

// HTTP-Methoden prüfen
if ($request->isPost()) {
     $data = $request->post('username');
}
if ($request->isGet()) {
     $id = $request->get('id', 1); // Default: 1
}

// Eingabedaten verarbeiten
$userData = $request->only(['name', 'email', 'password']);
$allData = $request->all();

// Headers auslesen
$authToken = $request->getBearerToken();
$userAgent = $request->getUserAgent();
$isAjax = $request->isAjax();
$wantsJson = $request->wantsJson();
```

### Session Management

```php
// Session starten und verwalten
$request->session()->start();

// Session-Daten setzen
$request->session()->set('user_id', 123);
$request->session()->set('username', 'john_doe');

// Session-Daten abrufen
$userId = $request->session()->get('user_id');
$username = $request->session()->get('username', 'Guest');

// Session prüfen
if ($request->session()->has('user_id')) {
     // User ist eingeloggt
}

// Session-ID regenerieren
$request->session()->regenerate();

// Session beenden
$request->session()->destroy();
```

### Cookie Management

```php
// Cookies setzen
$request->cookie()->set('theme', 'dark', 3600);
$request->cookie()->forever('user_preference', 'compact');
$request->cookie()->set('secure_data', 'value', 3600, [
     'secure' => true,
     'samesite' => 'Strict'
]);

// Cookies auslesen
$theme = $request->cookie()->get('theme', 'light');
$hasPreference = $request->cookie()->has('user_preference');

// Cookies löschen
$request->cookie()->delete('old_cookie');
```

### Header Management

```php
// HTTP Headers auslesen
$contentType = $request->headers()->get('Content-Type');
$authorization = $request->headers()->get('Authorization');

// Alle Headers
$allHeaders = $request->headers()->all();

// Bearer Token extrahieren
$token = $request->getBearerToken();

// Request-Typ prüfen
if ($request->wantsJson()) {
     // Client erwartet JSON-Response
}
if ($request->isSecure()) {
     // HTTPS-Verbindung
}
```

### Praktisches Beispiel: Login-System

```php
<?php
use Brick\Http\Request;

require_once 'brick/Core/Autoloader.php';
$autoloader = new \Brick\Core\Autoloader();
$autoloader->addNamespace('Brick', __DIR__ . '/brick');
$autoloader->register();

$request = new Request();
$request->session()->start();

// Login-Formular verarbeiten
if ($request->isPost() && $request->has('login')) {
     $credentials = $request->only(['email', 'password']);
     if (validateUser($credentials['email'], $credentials['password'])) {
          $request->session()->set('user_id', getUserId($credentials['email']));
          $request->session()->set('logged_in', true);
          $request->session()->regenerate();
          if ($request->has('remember')) {
                $request->cookie()->forever('remember_token', generateToken());
          }
          redirect('/dashboard');
     } else {
          $request->session()->set('error', 'Invalid credentials');
     }
}

// Logout verarbeiten
if ($request->isPost() && $request->has('logout')) {
     $request->session()->destroy();
     $request->cookie()->delete('remember_token');
     redirect('/login');
}

// User-Status prüfen
$isLoggedIn = $request->session()->get('logged_in', false);
$userId = $request->session()->get('user_id');
```

## 🏗️ Projektstruktur

```
📦 Brick-Framework/
├── 🌐 public/                    # Web Root
│   └── index.php                 # Entry Point
├── 🧱 brick/                     # Framework Core
│   ├── Core/
│   │   └── Autoloader.php        # PSR-4 Autoloader
│   └── Http/
│       ├── Request.php           # HTTP Request Handler
│       └── Request/
│           ├── Session.php       # Session Management
│           ├── Cookie.php        # Cookie Management
│           └── HeaderBag.php     # Header Management
├── ⚙️ config/                    # Konfiguration
├── 🗂️ app/                       # Application Code
│   ├── Controllers/
│   ├── Views/
│   └── routes.php
├── 🧪 tests/                     # Test Suite
│   ├── brick/
│   │   └── RequestTest.php       # Framework Tests
│   ├── brick-test-runner.php     # Custom Test Runner
│   └── phpunit.xml               # PHPUnit Config
├── 🎯 cli/                       # CLI Tools
│   ├── brick-cli.sh              # Interactive CLI
│   ├── brick:test                # Quick Test Runner
│   └── setup-cli                 # Global Installation
├── 📦 composer.json              # Dependencies
└── 🌟 README.md                  # Diese Datei
```

## 🧪 Testing

Das Framework kommt mit einer umfassenden Test-Suite:

```bash
# Schnelle Tests
php cli/brick:test

# Interaktive CLI mit Test-Optionen
./cli/brick-cli.sh

# PHPUnit direkt
./vendor/bin/phpunit
```

**Test-Statistiken:**
- ✅ 30 Tests
- ✅ 67 Assertions
- ✅ 100% Pass Rate

## 🎯 CLI Tools

Brick bietet mächtige CLI-Tools für die Entwicklung:

```bash
./cli/brick-cli.sh
# Optionen:
# 1) 🧪 Run Tests
# 2) 📋 Project Info
# 3) 🔧 Development Tools
# 4) 📚 Generate Documentation
# 5) 🚀 Start Development Server
# 6) 📦 Build Project
```

## 🚀 Features

### ✨ Aktuelle Features
- 🌐 HTTP Request Handling
- 🍪 Session & Cookie Support
- 📡 Header Management
- 🔧 PSR-4 Autoloader
- 🧪 Comprehensive Testing
- 🎯 CLI Tools
- 🛡️ Security Features

### 📋 Roadmap
- 🛣️ Router System
- 🎨 Template Engine
- 🗄️ Database Layer
- 🔐 Authentication
- 📧 Mail System
- 📊 Logging

## 🤝 Beitragen

Contributions sind willkommen! Siehe [Contributing Guidelines](CONTRIBUTING.md).

1. Fork das Repository
2. Feature Branch erstellen (`git checkout -b feature/amazing-feature`)
3. Changes committen (`git commit -m 'Add amazing feature'`)
4. Pushen (`git push origin feature/amazing-feature`)
5. Pull Request öffnen

## 📄 Lizenz

Dieses Projekt steht unter der MIT-Lizenz. Siehe [LICENSE](LICENSE).

## 👨‍💻 Autor

**JP Behrens**
- 📧 E-Mail: jp@bitka.de
- 💼 GitHub: [bitka-de](https://github.com/bitka-de)
- 🌐 Website: [bitka.de](https://bitka.de)
- 💼 LinkedIn: [JP Behrens](https://linkedin.com/in/webentwickler-karlsruhe)

---

<p align="center">
  <strong>🧱 Gebaut mit ❤️ und modernem PHP 8+</strong><br>
  <em>Keep building awesome things!</em> 🚀
</p>

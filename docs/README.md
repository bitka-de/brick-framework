# 📚 Brick Framework Dokumentation

Willkommen zur umfassenden Dokumentation des Brick Frameworks!

## 🎯 Schnellzugriff

### 🚀 **Erste Schritte**
- [Haupt-README](../README.md) – Projektübersicht und Einrichtung
- [Installationsanleitung](#installation) – Framework installieren
- [Schnellstart](#quick-start) – In wenigen Minuten loslegen

### 🧪 **Tests & Qualität**
- [Testübersicht](TEST_SUITE_OVERVIEW.md) – Vollständige Testdokumentation
- [Tests ausführen](#running-tests) – Tests ausführen und verstehen

### 🔀 **Router-System**
- [Router-Verbesserungen](ROUTER_ENHANCEMENT_SUMMARY.md) – Neueste Router-Features
- [Routing-Anleitung](#routing-guide) – Router-System verwenden

### 🌐 **HTTP-Komponenten**
- [Request-Verarbeitung](#request-handling) – Mit HTTP-Requests arbeiten
- [Response-System](#response-system) – HTTP-Responses erstellen

## 📖 Dokumentationsstruktur

```
docs/
├── README.md                      # Diese Datei – Dokumentationsindex
├── TEST_SUITE_OVERVIEW.md         # Vollständige Testdokumentation
├── ROUTER_ENHANCEMENT_SUMMARY.md  # Router-Verbesserungen & Features
└── [Weitere Dokumentationsdateien]
```

## 🔧 Installation

```bash
# Repository klonen
git clone https://github.com/bitka-de/brick-framework.git
cd brick-framework

# Abhängigkeiten installieren
composer install

# Tests ausführen, um die Installation zu prüfen
php tests/brick-test-runner.php
```

## ⚡ Schnellstart

```php
<?php
// Einfaches Anwendungsbeispiel
require_once 'vendor/autoload.php';

use Brick\Http\Request;
use Brick\Http\Response;
use Brick\Core\Router;

// Instanzen erstellen
$request = new Request();
$response = new Response();
$router = new Router();

// Routen hinzufügen
$router->get('/', fn() => $response->json(['message' => 'Hallo, Brick Framework!']));
$router->get('/users/{id}', fn($params) => $response->json(['user_id' => $params['id']]));

// Request verarbeiten
$result = $router->dispatch($request->method(), $request->uri());
if ($result['found']) {
  $result['handler']($result['params'] ?? []);
}
```

## 🧪 Tests ausführen

```bash
# Schneller Testlauf (nur Zusammenfassung)
php tests/brick-test-runner.php

# Detaillierte Testergebnisse
php tests/brick-test-runner.php --details

# Hilfe und Optionen
php tests/brick-test-runner.php --help
```

## 🏗️ Architektur

Das Brick Framework besteht aus drei Hauptkomponenten:

### 🌐 HTTP Request/Response-System
- **Request-Klasse**: Eingabe, Sessions, Cookies, Header
- **Response-Klasse**: JSON/XML/HTML-Ausgabe, Redirects, CORS, Sicherheitsheader
- **Umfassende Tests**: 71 Tests für alle HTTP-Szenarien

### 🔀 Router-System  
- **Hochperformantes Routing**: O(1) für statische Routen, optimiertes dynamisches Matching
- **Routengruppen**: Routen mit Präfixen organisieren
- **Parameter-Validierung**: Regex-Muster für Routenparameter
- **Debug-Tools**: Routen-Dumping und Performance-Statistiken

### 🧪 Test-Framework
- **91 Tests**: Vollständige Abdeckung aller Komponenten
- **213 Assertions**: Gründliche Validierung
- **Intelligente Ausgabe**: Detaillierte Ergebnisse nur bei Bedarf
- **Eigener Test-Runner**: Schöne, informative Ausgabe

## 🎯 Wichtige Features

- ✅ **Modernes PHP 8+**: Neueste Sprachfeatures
- ⚡ **Hohe Performance**: Für Geschwindigkeit optimiert
- 🛡️ **Sicherheit zuerst**: Eingebaute Sicherheitsheader und Validierung
- 🧪 **Gut getestet**: 100% Test-Erfolgsrate
- 📚 **Gut dokumentiert**: Umfassende Anleitungen und Beispiele
- 🎨 **Entwicklerfreundlich**: Schöne CLI-Tools und Debugging

## 🤝 Mitwirken

Bitte lies unsere [Mitwirkungsrichtlinien](../CONTRIBUTING.md), bevor du Pull Requests einreichst.

## 📄 Lizenz

Dieses Projekt steht unter der MIT-Lizenz – siehe [LICENSE](../LICENSE) für Details.

---

**Viel Spaß beim Coden mit dem Brick Framework! 🧱✨**
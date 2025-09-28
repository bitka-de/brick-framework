# 🧪 Brick Framework Test-Suite Übersicht

## 📊 Test-Suite Zusammenfassung

Die Brick Framework Test-Suite führt **91 Tests** mit **213 Assertions** aus und deckt die HTTP Request/Response Komponenten sowie den Router umfassend ab.

## 🎯 Test-Konfiguration

### PHPUnit Setup
- **Konfiguration**: `tests/phpunit.xml`
- **Bootstrap**: `vendor/autoload.php` (Composer Autoloader)
- **Test-Verzeichnis**: `tests/brick/`
- **Source-Abdeckung**: `brick/` Verzeichnis

### Test-Runner
- **Custom Runner**: `tests/brick-test-runner.php`
- **Features**: ASCII Art, Farbausgabe, Fortschrittsanzeige, detaillierte Statistiken

## 📁 Test-Dateien

### 1. **RequestTest.php** (30 Tests)
Tests für die `Brick\Http\Request` Klasse:

**Basis-Funktionen:**
- ✅ `testGetMethod()` - HTTP-Methode abrufen
- ✅ `testGetUri()` - URI extrahieren 
- ✅ `testIsMethod()` - Methoden-Validierung
- ✅ `testHttpMethodHelpers()` - GET, POST, PUT, etc. Helper

**Input-Verarbeitung:**
- ✅ `testGet()` - GET-Parameter
- ✅ `testPost()` - POST-Parameter  
- ✅ `testInput()` - Kombinierte Input-Daten
- ✅ `testAll()` - Alle Parameter
- ✅ `testOnly()` - Spezifische Parameter
- ✅ `testHas()` - Parameter-Existenz prüfen

**Session & Cookies:**
- ✅ `testSession()` - Session-Daten lesen
- ✅ `testSetSession()` - Session-Daten schreiben
- ✅ `testCookie()` - Cookie-Werte
- ✅ `testSetCookie()` - Cookies setzen

**HTTP-Details:**
- ✅ `testGetHeader()` - HTTP-Headers
- ✅ `testBearerToken()` - Authorization Token
- ✅ `testIsSecure()` - HTTPS-Erkennung
- ✅ `testIsAjax()` - AJAX-Requests
- ✅ `testWantsJson()` - JSON-Response gewünscht
- ✅ `testIp()` - Client-IP ermitteln
- ✅ `testUserAgent()` - User-Agent Header

**Magic Methods & Edge Cases:**
- ✅ `testMagicGet()` - `__get()` Method
- ✅ `testMagicIsset()` - `__isset()` Method  
- ✅ `testGetMethodWithEmptyServer()` - Fallback-Verhalten
- ✅ `testUriWithoutRequestUri()` - URI-Fallbacks
- ✅ `testIpWithProxyHeaders()` - Proxy-IP-Erkennung
- ✅ `testSecureWithProxy()` - HTTPS über Proxy

### 2. **ResponseTest.php** (41 Tests)
Tests für die `Brick\Http\Response` Klasse:

**Status & Headers:**
- ✅ `testDefaultStatus()` - Standard HTTP 200
- ✅ `testSetStatus()` - Status-Code setzen
- ✅ `testFluentInterface()` - Method Chaining
- ✅ `testSingleHeader()` - Einzelne Header
- ✅ `testMultipleHeaders()` - Mehrere Header
- ✅ `testHeaderOverwrite()` - Header überschreiben

**Response Body:**
- ✅ `testStringBody()` - String-Inhalte
- ✅ `testArrayBody()` - Array-Inhalte
- ✅ `testNullBody()` - Leere Response

**Content-Type Responses:**
- ✅ `testJsonResponse()` - JSON-Format
- ✅ `testJsonWithCustomStatus()` - JSON mit Status
- ✅ `testTextResponse()` - Plain Text
- ✅ `testTextWithCustomStatus()` - Text mit Status
- ✅ `testHtmlResponse()` - HTML-Content
- ✅ `testXmlResponse()` - XML-Content

**Redirects:**
- ✅ `testRedirectValidUrl()` - Gültige URLs
- ✅ `testRedirectRelativePath()` - Relative Pfade
- ✅ `testRedirectInvalidUrl()` - Ungültige URLs
- ✅ `testRedirectCustomStatus()` - Custom Redirect-Status

**API Responses:**
- ✅ `testErrorResponse()` - Fehler-Antworten
- ✅ `testErrorWithCustomStatus()` - Fehler mit Status
- ✅ `testErrorWithErrors()` - Fehler mit Details
- ✅ `testSuccessResponse()` - Erfolg-Antworten
- ✅ `testSuccessWithData()` - Erfolg mit Daten
- ✅ `testNotFoundResponse()` - 404 Responses
- ✅ `testUnauthorizedResponse()` - 401 Responses
- ✅ `testForbiddenResponse()` - 403 Responses
- ✅ `testValidationErrorResponse()` - Validierungsfehler

**Security & Headers:**
- ✅ `testSecureHeaders()` - Sicherheits-Header
- ✅ `testCorsHeaders()` - CORS-Konfiguration
- ✅ `testCorsDefaultValues()` - Standard CORS-Werte
- ✅ `testCacheHeaders()` - Cache-Control
- ✅ `testPrivateCache()` - Private Cache
- ✅ `testNoCache()` - No-Cache Headers

**Special Features:**
- ✅ `testIsSent()` - Response-Status prüfen
- ✅ `testReset()` - Response zurücksetzen
- ✅ `testHttpConstants()` - HTTP-Konstanten
- ✅ `testJsonEncodingWithSpecialCharacters()` - Unicode-Support
- ✅ `testEmptyJsonResponse()` - Leere JSON-Response
- ✅ `testExitAfterRedirectConfiguration()` - Exit-Verhalten
- ✅ `testSecurityHeadersConfiguration()` - Security-Config

### 3. **RouterTest.php** (20 Tests) 🆕
Tests für die `Brick\Core\Router` Klasse:

**Basis-Funktionen:**
- ✅ `testRouterInstantiation()` - Router-Erstellung
- ✅ `testAddRoute()` - Route hinzufügen
- ✅ `testHttpMethodHelpers()` - HTTP-Method Helper (GET, POST, etc.)
- ✅ `testInvalidHttpMethod()` - Ungültige HTTP-Methoden
- ✅ `testMultipleHttpMethods()` - Mehrere HTTP-Methoden pro Route

**Route Matching:**
- ✅ `testStaticRouteMatching()` - Statische Routen
- ✅ `testDynamicRouteMatching()` - Dynamische Routen mit Parametern
- ✅ `testDynamicRouteWithRegex()` - Parameter mit Regex-Validierung
- ✅ `testMultipleParameters()` - Mehrere Route-Parameter
- ✅ `testNoMatchReturnsNull()` - Keine Treffer

**Route Groups:**
- ✅ `testRouteGroups()` - Route-Gruppierung
- ✅ `testNestedRouteGroups()` - Verschachtelte Pfade
- ✅ `testRouteGroupsWithParameters()` - Gruppen mit Parametern

**Advanced Features:**
- ✅ `testGetStats()` - Router-Statistiken
- ✅ `testDumpRoutes()` - Route-Debug-Output
- ✅ `testRouteCompilationWithInvalidParameterName()` - Parameter-Validierung
- ✅ `testRouteCompilationWithValidRegex()` - Regex-Pattern

**Edge Cases:**
- ✅ `testRootPath()` - Root-Route handling
- ✅ `testPathNormalization()` - Pfad-Normalisierung
- ✅ `testCaseSensitiveRoutes()` - Case-Sensitivity

## 📈 Test-Ergebnisse

```bash
🧪 Tests Run: 91 tests (+20 Router tests)
✅ Assertions: 213 assertions (+62 Router assertions)
🎉 Passed: 100% aller Tests
⏱️ Execution Time: ~140ms
📊 Coverage: HTTP Request/Response + Router Komponenten vollständig

ℹ️  Note: 41 "Risky Tests" sind normal und erwartet
   (HTTP Response-Tests manipulieren Output Buffer)
```

## ⚠️ **Hinweise zu "Risky Tests"**

**41 "Risky Tests"** werden angezeigt - das ist **vollkommen normal**!

**Warum passiert das?**
- Die HTTP Response-Tests rufen `header()` und `http_response_code()` auf
- Diese PHP-Funktionen manipulieren Output Buffer
- PHPUnit erkennt das als "riskant" da es den Test-Output beeinflussen könnte

**Ist das ein Problem?** 
- ❌ **NEIN** - Die Tests funktionieren korrekt
- ✅ Alle 91 Tests bestehen erfolgreich
- ✅ Das Verhalten ist für HTTP Response-Tests erwartet und notwendig

**Warum beheben wir es nicht?**
- Es wäre ein Mock erforderlich, der die echte Funktionalität nicht testet
- Die aktuellen Tests prüfen das **reale Verhalten** der Response-Klasse
- Das ist wichtiger als "saubere" Test-Ausgaben

## 🎯 Test-Qualität

- **Vollständige Abdeckung**: Alle Kernkomponenten getestet
- **Router Integration**: Komplette Router-Funktionalität abgedeckt
- **Edge Cases**: Fehlerbehandlung und Grenzfälle berücksichtigt
- **Mock-Daten**: Realistische Test-Szenarien
- **Assertions**: Durchschnittlich 2.3 Assertions pro Test
- **Erfolgsquote**: 100% aller Tests bestehen

## 🚀 Test-Ausführung

```bash
# Standard Test-Ausführung (kompakte Ausgabe)
php tests/brick-test-runner.php

# Detaillierte Test-Ausgabe anzeigen
php tests/brick-test-runner.php --details
php tests/brick-test-runner.php -d

# Hilfe anzeigen
php tests/brick-test-runner.php --help

# Standard PHPUnit (ohne schöne Formatierung)
./vendor/bin/phpunit --configuration=tests/phpunit-clean.xml

# Nur Router-Tests
./vendor/bin/phpunit tests/brick/RouterTest.php

# Router-spezifische Tests (manuell)
php tests/router/enhanced_router_test.php
```

**Smart Output:**
- 📊 **Standard**: Zeigt Komponenten-Übersicht und Statistiken
  ```
  📊 Tested Components:
     🌐 HTTP Request Handler (30 tests)
        → Input Processing, Sessions, Headers, Security, IP Detection
     📤 HTTP Response System (41 tests)
        → JSON/XML/HTML Output, Redirects, CORS, Cache Control, API Responses
     🔀 Router & Route Groups (20 tests)  
        → Static/Dynamic Routes, Parameters, Regex Validation, Groups
  ```
- 🔍 **Details**: Zeigt komplette Testliste (mit `--details` oder `-d`)  
- ⚠️ **Auto-Details**: Detaillierte Ausgabe bei Fehlern automatisch aktiviert

Die Test-Suite stellt sicher, dass alle Kernkomponenten des Brick Frameworks (HTTP Request/Response + Router) robust und zuverlässig funktionieren! 🎉
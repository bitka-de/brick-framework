# 🧪 Router Tests

Dieser Ordner enthält spezielle Tests für die Router-Funktionalität des Brick Frameworks.

## 📁 Test-Dateien

### `simple_router_test.php`
- **Zweck**: Grundlegende Router-Funktionalität testen
- **Features**: HTTP-Method-Validierung, Statistiken
- **Ausführung**: `php tests/router/simple_router_test.php`

### `enhanced_router_test.php`
- **Zweck**: Erweiterte Router-Features testen
- **Features**: Route Groups, Route Matching, Parameter-Extraktion, Route Dumping
- **Ausführung**: `php tests/router/enhanced_router_test.php`

### `test_enhanced_router.php`
- **Zweck**: Umfassender Test aller Router-Verbesserungen
- **Features**: Route Groups mit Regex, HTTP-Validierung, Debug-Features
- **Ausführung**: `php tests/router/test_enhanced_router.php`

## 🚀 Alle Tests ausführen

```bash
# Einzelne Router-Tests
php tests/router/simple_router_test.php
php tests/router/enhanced_router_test.php

# Vollständige Framework-Tests (inkl. Router)
php tests/brick-test-runner.php
```

## ✅ Erwartete Ausgabe

Alle Tests sollten erfolgreich durchlaufen und grüne Checkmarks (✅) anzeigen. 
Bei Fehlern werden rote X-Marks (❌) und entsprechende Fehlermeldungen angezeigt.

## 🎯 Test-Abdeckung

Diese Tests überprüfen:
- ✅ Router-Instanziierung
- ✅ HTTP-Method-Validierung  
- ✅ Route Groups
- ✅ Parameter-Extraktion
- ✅ Route Matching
- ✅ Debug-Funktionalität
- ✅ Performance-Statistiken
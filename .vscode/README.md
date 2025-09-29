# 🔧 VS Code Konfiguration für Brick Framework

Diese VS Code-Konfiguration sorgt dafür, dass Brick Template-Direktiven korrekt erkannt werden und keine falschen Syntaxfehler angezeigt werden.

## 📁 Erstellte Dateien

### Core-Konfiguration
- **`.vscode/settings.json`** - Haupt-VS Code Einstellungen
- **`.vscode/brick-framework.code-workspace`** - Workspace-Konfiguration mit Tasks

### Template-Support
- **`.vscode/brick-template.tmLanguage.json`** - Syntax-Highlighting für Brick-Direktiven
- **`.vscode/brick-snippets.json`** - Code-Snippets für schnellere Entwicklung
- **`.vscode/php-template-config.json`** - PHP-spezifische Konfiguration für Templates

## 🚀 Verwendung

### 1. Workspace öffnen
```bash
# VS Code mit der Brick Framework Workspace-Konfiguration öffnen
code brick-framework.code-workspace
```

### 2. Automatische Features

#### ✅ **Syntax-Highlighting**
- `@extends`, `@section`, `@yield` werden korrekt hervorgehoben
- `{{ }}` und `{!! !!}` Ausgaben sind farblich markiert
- Keine falschen Syntaxfehler mehr in Template-Dateien

#### ✅ **Code-Snippets**
- `@extends` → Komplettes Layout-Template
- `@section` → Section-Block erstellen
- `@if` → If-Statement
- `@foreach` → Foreach-Loop
- `{{` → Escaped Output
- `{!!` → Unescaped Output
- `brick-layout` → Komplettes Layout-Template
- `brick-page` → Page-Template Struktur

#### ✅ **Integrierte Tasks**
- **Ctrl+Shift+P** → "Tasks: Run Task"
- `🧪 Run Brick Tests` → Alle Tests ausführen
- `🧪 Run Detailed Tests` → Tests mit Details
- `🎨 Test View System` → View-System testen
- `🚀 Start Development Server` → PHP Server starten

### 3. Manuelle Konfiguration

Falls die automatische Erkennung nicht funktioniert:

#### Schritt 1: Intelephense konfigurieren
```json
{
    "intelephense.diagnostics.enable": false,
    "php.validate.enable": false
}
```

#### Schritt 2: File-Associations setzen
```json
{
    "files.associations": {
        "app/Views/**/*.php": "php"
    }
}
```

## 🎨 Template-Entwicklung

### Verfügbare Snippets

| Kürzel | Beschreibung | Ergebnis |
|--------|-------------|----------|
| `@extends` | Layout erweitern | `@extends('layout')` + Section |
| `@section` | Section erstellen | `@section('name')...@endsection` |
| `@yield` | Section ausgeben | `@yield('name', 'default')` |
| `@include` | Template einbinden | `@include('template', [])` |
| `@if` | If-Statement | `@if()...@endif` |
| `@foreach` | Foreach-Loop | `@foreach()...@endforeach` |
| `{{` | Escaped Output | `{{ $variable }}` |
| `{!!` | Unescaped Output | `{!! $html !!}` |
| `brick-layout` | Layout-Struktur | Komplettes HTML-Layout |
| `brick-page` | Page-Template | Template mit Layout |
| `brick-alert` | Alert-Komponente | Alert mit Parametern |
| `brick-card` | Card-Komponente | Card mit Inhalt |

### Syntax-Highlighting

Die Konfiguration erkennt automatisch:
- **Direktiven**: `@extends`, `@section`, `@if`, etc. (lila, fett)
- **Escaped Output**: `{{ $var }}` (blau)
- **Unescaped Output**: `{!! $html !!}` (gold)
- **HTML**: Normale HTML-Syntax bleibt erhalten
- **PHP**: PHP-Code wird normal hervorgehoben

## 🐛 Problembehandlung

### Problem: Syntaxfehler werden noch angezeigt
**Lösung**: 
1. VS Code neustarten
2. Command Palette → "Developer: Reload Window"
3. Intelephense Extension neu laden

### Problem: Snippets funktionieren nicht
**Lösung**:
1. Überprüfen, ob die Datei als PHP erkannt wird (unten rechts in VS Code)
2. Settings → "editor.quickSuggestions" aktivieren
3. Extensions → PHP Intelephense → Reload

### Problem: Tasks sind nicht verfügbar
**Lösung**:
1. Workspace öffnen: `File → Open Workspace from File → brick-framework.code-workspace`
2. Command Palette → "Tasks: Configure Task" → Aus Template auswählen

## 🔧 Erweiterte Konfiguration

### Eigene Snippets hinzufügen

Bearbeite `.vscode/brick-snippets.json`:
```json
{
    "Mein Custom Snippet": {
        "prefix": "custom",
        "body": [
            "// Mein Custom Code",
            "$0"
        ],
        "description": "Beschreibung"
    }
}
```

### Eigene Syntax-Rules

Bearbeite `.vscode/brick-template.tmLanguage.json` für eigene Direktiven.

### Project-spezifische Settings

Füge in `.vscode/settings.json` hinzu:
```json
{
    "brick.customDirectives": ["@custom", "@mydirective"],
    "brick.debug": true
}
```

## 📚 Weitere Features

### IntelliSense für Templates
- Variable-Completion in `{{ }}` Blöcken
- HTML-Completion in Template-Dateien
- Emmet-Support für HTML-Strukturen

### Debugging
- Syntax-Highlighting für `@debug` Blöcke
- Variable-Inspektion in Templates
- Fehler-Markierungen nur für echte Probleme

---

**🎉 Mit dieser Konfiguration entwickelst du Brick Templates ohne nervige Syntax-Fehler!** ✨
@extends('docs')

@section('title', 'View System')

@section('content')
<h1>🎨 View System</h1>

<p class="lead">Das Brick Framework bietet ein Blade-ähnliches Template-System mit vertrauter Syntax und mächtigen Features.</p>

<div class="table-of-contents">
    <h5>🗺️ Inhaltsübersicht</h5>
    <ul>
        <li><a href="#grundlagen">Template-Grundlagen</a></li>
        <li><a href="#blade-syntax">Blade-ähnliche Syntax</a></li>
        <li><a href="#vererbung">Template-Vererbung</a></li>
        <li><a href="#includes">Includes & Partials</a></li>
        <li><a href="#variablen">Variablen & Daten</a></li>
        <li><a href="#direktiven">Verfügbare Direktiven</a></li>
        <li><a href="#best-practices">Best Practices</a></li>
    </ul>
</div>

<h2 id="grundlagen">🏠 Template-Grundlagen</h2>

<div class="example-block">
    <h5>✨ Hauptfeatures</h5>
    <ul>
        <li><strong>Vertraute Syntax:</strong> extends, section, include wie in Laravel Blade</li>
        <li><strong>Template-Vererbung:</strong> Hierarchische Layout-Strukturen</li>
        <li><strong>Automatische Kompilierung:</strong> Templates werden zu optimiertem PHP kompiliert</li>
        <li><strong>Variablen-Injection:</strong> Sichere Ausgabe und Daten-Binding</li>
    </ul>
</div>

<h2 id="blade-syntax">🔠 Blade-ähnliche Syntax</h2>

<h3>Variablen ausgeben</h3>

<p>Das Template-System unterstützt verschiedene Ausgabeformate:</p>

<ul>
    <li><strong>Sichere Ausgabe:</strong> Doppelte geschweifte Klammern für automatisches HTML-Escaping</li>
    <li><strong>Rohe Ausgabe:</strong> Ausrufezeichen-Syntax für unescaped HTML</li>
    <li><strong>Standardwerte:</strong> Null-Coalescing-Operator für Fallback-Werte</li>
    <li><strong>Objektzugriff:</strong> Pfeil-Notation für Objekteigenschaften</li>
</ul>

<h3>Template-Direktiven</h3>

<p>Wichtige Direktiven für die Template-Entwicklung:</p>

<ul>
    <li><strong>extends:</strong> Erweitert ein Layout-Template</li>
    <li><strong>section/endsection:</strong> Definiert Inhaltsbereiche</li>
    <li><strong>yield:</strong> Platzhalter für Inhalte in Layouts</li>
    <li><strong>include:</strong> Bindet andere Templates ein</li>
</ul>

<h2 id="vererbung">🏗️ Template-Vererbung</h2>

<h3>Layout-System</h3>

<p>Das Template-System nutzt eine hierarchische Struktur:</p>

<ol>
    <li><strong>Basis-Layout:</strong> Definiert die grundlegende HTML-Struktur</li>
    <li><strong>Seitenlayouts:</strong> Erweitern das Basis-Layout für spezifische Bereiche</li>
    <li><strong>Einzelseiten:</strong> Nutzen Layouts und definieren spezifische Inhalte</li>
    <li><strong>Partials:</strong> Wiederverwendbare Template-Fragmente</li>
</ol>

<h3>Workflow</h3>

<p>Typischer Arbeitsablauf für Templates:</p>

<ol>
    <li>Basis-Layout mit Header, Navigation, Footer erstellen</li>
    <li>Seitenlayouts für verschiedene Bereiche (Blog, Admin, etc.)</li>
    <li>Einzelne Templates die Layouts erweitern</li>
    <li>Partials für wiederkehrende Komponenten</li>
</ol>

<h2 id="includes">📦 Includes & Partials</h2>

<h3>Verwendung von Includes</h3>

<p>Includes ermöglichen die Wiederverwendung von Template-Code:</p>

<ul>
    <li><strong>Einfache Includes:</strong> Template ohne Parameter einbinden</li>
    <li><strong>Includes mit Daten:</strong> Variables an Templates übertragen</li>
    <li><strong>Bedingte Includes:</strong> Templates nur unter bestimmten Bedingungen einbinden</li>
    <li><strong>Partials:</strong> Kleine, wiederverwendbare Template-Komponenten</li>
</ul>

<h3>Organisationsstruktur</h3>

<p>Empfohlene Verzeichnisstruktur:</p>

<ul>
    <li><strong>layouts/:</strong> Basis-Templates und Layouts</li>
    <li><strong>partials/:</strong> Wiederverwendbare Komponenten</li>
    <li><strong>pages/:</strong> Seitenspezifische Templates</li>
    <li><strong>components/:</strong> Komplexere UI-Komponenten</li>
</ul>

<h2 id="variablen">📊 Variablen & Daten</h2>

<h3>Controller Integration</h3>

<p>Templates erhalten Daten von Controllern:</p>

<ol>
    <li><strong>Controller:</strong> Bereitet Daten auf und ruft View auf</li>
    <li><strong>View-Klasse:</strong> Lädt Template und injiziert Variablen</li>
    <li><strong>Template:</strong> Zeigt Daten mit Blade-Syntax an</li>
    <li><strong>Ausgabe:</strong> Gerenderte HTML wird an Browser gesendet</li>
</ol>

<h3>Datentransfer</h3>

<p>Verschiedene Wege der Datenübertragung:</p>

<ul>
    <li><strong>Array-Parameter:</strong> Einfache Schlüssel-Wert-Paare</li>
    <li><strong>Objekte:</strong> Komplexere Datenstrukturen</li>
    <li><strong>Collections:</strong> Listen von Objekten oder Arrays</li>
    <li><strong>Globale Variablen:</strong> In allen Templates verfügbare Daten</li>
</ul>

<h2 id="direktiven">⚡ Verfügbare Direktiven</h2>

<h3>Kontrollstrukturen</h3>

<p>Das Template-System unterstützt alle wichtigen Kontrollstrukturen:</p>

<ul>
    <li><strong>if/elseif/else/endif:</strong> Bedingte Anzeige von Inhalten</li>
    <li><strong>unless/endunless:</strong> Umgekehrte if-Bedingung</li>
    <li><strong>isset/endisset:</strong> Prüfung auf definierte Variablen</li>
    <li><strong>empty/endempty:</strong> Prüfung auf leere Variablen</li>
</ul>

<h3>Schleifen</h3>

<p>Verschiedene Schleifentypen für die Iteration:</p>

<ul>
    <li><strong>foreach/endforeach:</strong> Iteration über Arrays und Objekte</li>
    <li><strong>forelse/empty/endforelse:</strong> Foreach mit Fallback für leere Listen</li>
    <li><strong>for/endfor:</strong> Zählerbasierte Schleifen</li>
    <li><strong>while/endwhile:</strong> Bedingungsbasierte Schleifen</li>
</ul>

<h2 id="best-practices">📝 Best Practices</h2>

<div class="example-block">
    <h5>✅ Empfehlungen</h5>
    <ul>
        <li><strong>Klare Struktur:</strong> Verwenden Sie logische Layout-Hierarchien</li>
        <li><strong>Wiederverwendung:</strong> Erstellen Sie Partials für häufig genutzte Komponenten</li>
        <li><strong>Sicherheit:</strong> Nutzen Sie doppelte Klammern für automatisches HTML-Escaping</li>
        <li><strong>Performance:</strong> Vermeiden Sie komplexe Logik in Templates</li>
        <li><strong>Konsistenz:</strong> Folgen Sie einheitlichen Namenskonventionen</li>
        <li><strong>Dokumentation:</strong> Kommentieren Sie komplexe Template-Strukturen</li>
    </ul>
</div>

<div class="warning-block">
    <h5>⚠️ Wichtige Hinweise</h5>
    <ul>
        <li>Templates werden automatisch kompiliert und gecacht</li>
        <li>Verwenden Sie rohe Ausgabe nur für vertrauenswürdigen HTML-Content</li>
        <li>Komplexe Datenverarbeitung gehört in Controller, nicht in Templates</li>
        <li>Partials können beliebig verschachtelt werden</li>
        <li>Template-Pfade sind relativ zum Views-Verzeichnis</li>
        <li>Variablennamen sind case-sensitive</li>
    </ul>
</div>

<h2>🎯 Praktische Anwendung</h2>

<h3>Häufige Use Cases</h3>

<ol>
    <li><strong>Blog-System:</strong> Post-Listen, Einzelartikel, Kategorien</li>
    <li><strong>Admin-Interface:</strong> Dashboards, Datenmanagement, Formulare</li>
    <li><strong>E-Commerce:</strong> Produktlisten, Warenkorb, Checkout</li>
    <li><strong>Portfolio:</strong> Projektelisten, Einzelprojekte, Kontaktformulare</li>
</ol>

<h3>Template-Optimierung</h3>

<p>Tipps für bessere Performance:</p>

<ul>
    <li><strong>Caching:</strong> Templates werden automatisch kompiliert und gecacht</li>
    <li><strong>Partials:</strong> Kleine, fokussierte Komponenten reduzieren Komplexität</li>
    <li><strong>Datenaufbereitung:</strong> Controller sollten Daten vorverarbeiten</li>
    <li><strong>Lazy Loading:</strong> Große Datenmengen nur bei Bedarf laden</li>
</ul>

<h2>🚀 Nächste Schritte</h2>

<div class="row">
    <div class="col-md-4">
        <a href="/docs/css-js" class="btn btn-primary w-100">💎 CSS/JS Direktiven</a>
    </div>
    <div class="col-md-4">
        <a href="/docs/routing" class="btn btn-outline-primary w-100">🛣️ Routing System</a>
    </div>
    <div class="col-md-4">
        <a href="/docs/middleware" class="btn btn-outline-secondary w-100">🛡️ Middleware</a>
    </div>
</div>

<div class="mt-4">
    <h5>📚 Weitere Themen</h5>
    <p>Erkunden Sie die anderen Bereiche der Dokumentation für ein vollständiges Verständnis des Brick Frameworks:</p>
    
    <div class="row">
        <div class="col-md-6">
            <ul>
                <li><a href="/docs/database">🗄️ Datenbankintegration</a></li>
                <li><a href="/docs/api">🔌 API-Entwicklung</a></li>
            </ul>
        </div>
        <div class="col-md-6">
            <ul>
                <li><a href="/docs/installation">⚙️ Installation & Setup</a></li>
                <li><a href="/docs">📖 Dokumentationsübersicht</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('prev-page')
<a href="/docs/routing" class="btn btn-outline-secondary">
    ← Routing System
</a>
@endsection

@section('next-page')
<a href="/docs/css-js" class="btn btn-primary">
    CSS/JS Direktiven →
</a>
@endsection
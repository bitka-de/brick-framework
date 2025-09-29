<?php

declare(strict_types=1);
namespace Brick\Core;

use InvalidArgumentException;
use RuntimeException;

/**
 * 🎨 Brick View System - Blade-ähnliche Template Engine
 * 
 * Features:
 * - Blade-ähnliche Direktiven (@extends, @section, @yield, @include)
 * - Layout-System mit Master-Templates
 * - Variablen-Escaping und sichere Ausgabe
 * - Template-Vererbung und Komponenten
 * - Caching für Performance
 * - Debugging-Tools
 * 
 * Unterstützte Direktiven:
 * - @extends('layout')           - Layout erweitern
 * - @section('name')            - Sektion definieren
 * - @endsection                 - Sektion beenden
 * - @yield('name', 'default')   - Sektion ausgeben
 * - @include('partial')         - Template einbinden
 * - @if($condition)             - Bedingte Ausgabe
 * - @endif                      - If beenden
 * - @foreach($items as $item)   - Schleife
 * - @endforeach                 - Schleife beenden
 * - {{ $variable }}             - Escaped Ausgabe
 * - {!! $variable !!}           - Unescaped Ausgabe
 * 
 * @package Brick\Core
 * @version 1.0.0
 * @since 29.09.2025
 */
class View
{
    /** @var string Basis-Pfad für Views */
    private readonly string $viewPath;
    
    /** @var string Pfad für Layout-Templates */
    private readonly string $layoutPath;
    
    /** @var string Cache-Pfad für kompilierte Templates */
    private readonly string $cachePath;
    
    /** @var array<string, mixed> Globale Variablen für alle Views */
    private array $globals = [];
    
    /** @var array<string, string> Aktuelle Sektionen während der Kompilierung */
    public array $sections = [];
    
    /** @var array<string> Stack für verschachtelte Sektionen */
    private array $sectionStack = [];
    
    /** @var string|null Aktueller Layout-Name */
    private ?string $currentLayout = null;
    
    /** @var bool Debug-Modus für detaillierte Fehlermeldungen */
    private bool $debug = false;
    
    /** @var array<string, mixed> Template-Statistiken */
    private array $stats = [
        'compiled_templates' => 0,
        'cache_hits' => 0,
        'cache_misses' => 0,
        'render_time' => 0.0
    ];

    /**
     * View-System initialisieren
     * 
     * @param string $viewPath Basis-Pfad für View-Templates
     * @param string|null $cachePath Cache-Pfad (null = kein Caching)
     * @param bool $debug Debug-Modus aktivieren
     */
    public function __construct(
        string $viewPath = '',
        ?string $cachePath = null,
        bool $debug = false
    ) {
        $this->viewPath = $viewPath ?: $this->getDefaultViewPath();
        $this->layoutPath = $this->viewPath . '/layouts';
        $this->cachePath = $cachePath ?: sys_get_temp_dir() . '/brick_views';
        $this->debug = $debug;
        
        // Cache-Verzeichnis erstellen falls nötig
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
        
        // Layout-Verzeichnis erstellen falls nötig
        if (!is_dir($this->layoutPath)) {
            mkdir($this->layoutPath, 0755, true);
        }
    }

    /**
     * Template rendern
     * 
     * @param string $template Template-Name (ohne .php)
     * @param array<string, mixed> $data Variablen für das Template
     * @param bool $return true = String zurückgeben, false = direkt ausgeben
     * @return string|null
     */
    public function render(string $template, array $data = [], bool $return = true): ?string
    {
        $startTime = microtime(true);
        
        try {
            // Template-Pfad auflösen
            $templatePath = $this->resolveTemplatePath($template);
            
            // Template kompilieren (oder aus Cache laden)
            $compiledPath = $this->compile($templatePath);
            
            // Variablen zusammenführen
            $variables = array_merge($this->globals, $data, [
                '__view' => $this,
                '__template' => $template
            ]);
            
            // Template rendern
            $output = $this->renderCompiled($compiledPath, $variables);
            
            // Statistiken aktualisieren
            $this->stats['render_time'] += microtime(true) - $startTime;
            
            if ($return) {
                return $output;
            }
            
            echo $output;
            return null;
            
        } catch (\Throwable $e) {
            if ($this->debug) {
                throw new RuntimeException(
                    "View-Rendering fehlgeschlagen für '{$template}': {$e->getMessage()}",
                    $e->getCode(),
                    $e
                );
            }
            throw $e;
        }
    }

    /**
     * Template kompilieren
     * 
     * @param string $templatePath Vollständiger Pfad zum Template
     * @return string Pfad zur kompilierten Datei
     */
    private function compile(string $templatePath): string
    {
        $cacheKey = md5($templatePath);
        $cachePath = $this->cachePath . '/' . $cacheKey . '.php';
        
        // Cache prüfen (DEAKTIVIERT FÜR ENTWICKLUNG)
        if (false && file_exists($cachePath) && filemtime($cachePath) >= filemtime($templatePath)) {
            $this->stats['cache_hits']++;
            return $cachePath;
        }
        
        $this->stats['cache_misses']++;
        $this->stats['compiled_templates']++;
        
        // Template-Inhalt laden
        $content = file_get_contents($templatePath);
        if ($content === false) {
            throw new RuntimeException("Template nicht lesbar: {$templatePath}");
        }
        
        // Direktiven kompilieren
        $compiled = $this->compileDirectives($content);
        
        // Layout-System verarbeiten
        $compiled = $this->processLayout($compiled);
        
        // Kompilierte Version speichern
        if (file_put_contents($cachePath, $compiled) === false) {
            throw new RuntimeException("Cache nicht schreibbar: {$cachePath}");
        }
        
        return $cachePath;
    }

    /**
     * Alle Blade-ähnlichen Direktiven kompilieren
     * 
     * @param string $content Template-Inhalt
     * @return string Kompilierter PHP-Code
     */
    private function compileDirectives(string $content): string
    {
        // 1. Escaped Ausgabe: {{ $variable }}
        $content = preg_replace(
            '/\{\{\s*(.+?)\s*\}\}/',
            '<?= htmlspecialchars((string)($1), ENT_QUOTES, "UTF-8") ?>',
            $content
        );
        
        // 2. Unescaped Ausgabe: {!! $variable !!}
        $content = preg_replace(
            '/\{!!\s*(.+?)\s*!!\}/',
            '<?= $1 ?>',
            $content
        );
        
        // 3. Extends: @extends('layout')
        $content = preg_replace(
            '/@extends\s*\(\s*[\'"]([^\'"]+)[\'"]\s*\)/',
            '<?php $__layout = "$1"; ?>',
            $content
        );
        
        // 4. Section Start: @section('name')
        $content = preg_replace(
            '/@section\s*\(\s*[\'"]([^\'"]+)[\'"]\s*\)/',
            '<?php $__view->startSection("$1"); ?>',
            $content
        );
        
        // 5. Section End: @endsection
        $content = preg_replace(
            '/@endsection\b/',
            '<?php $__view->endSection(); ?>',
            $content
        );
        
        // 6. Yield: @yield('name', 'default')
        $content = preg_replace(
            '/@yield\s*\(\s*[\'"]([^\'"]+)[\'"](?:\s*,\s*[\'"]([^\'"]*)[\'"])?\s*\)/',
            '<?= $__view->yieldSection("$1", "$2") ?>',
            $content
        );
        
        // 7. Include: @include('partial') - nur einfache Includes
        $content = preg_replace(
            '/@include\s*\(\s*[\'"]([^\'"]+)[\'"]\s*\)/',
            '<?= $__view->includePartial("$1", get_defined_vars()) ?>',
            $content
        );
        
        // 8. If: @if($condition)
        $content = preg_replace(
            '/@if\s*\((.+)\)/',
            '<?php if ($1): ?>',
            $content
        );
        
        // 9. ElseIf: @elseif($condition)
        $content = preg_replace(
            '/@elseif\s*\((.+)\)/',
            '<?php elseif ($1): ?>',
            $content
        );
        
        // 10. Else: @else
        $content = preg_replace(
            '/@else\b/',
            '<?php else: ?>',
            $content
        );
        
        // 11. EndIf: @endif
        $content = preg_replace(
            '/@endif\b/',
            '<?php endif; ?>',
            $content
        );
        
        // 12. Foreach: @foreach($items as $item)
        $content = preg_replace(
            '/@foreach\s*\((.+)\)/',
            '<?php foreach ($1): ?>',
            $content
        );
        
        // 13. EndForeach: @endforeach
        $content = preg_replace(
            '/@endforeach\b/',
            '<?php endforeach; ?>',
            $content
        );
        
        // 14. For: @for($i = 0; $i < 10; $i++)
        $content = preg_replace(
            '/@for\s*\((.+)\)/',
            '<?php for ($1): ?>',
            $content
        );
        
        // 15. EndFor: @endfor
        $content = preg_replace(
            '/@endfor\b/',
            '<?php endfor; ?>',
            $content
        );
        
        // 16. While: @while($condition)
        $content = preg_replace(
            '/@while\s*\((.+)\)/',
            '<?php while ($1): ?>',
            $content
        );
        
        // 17. EndWhile: @endwhile
        $content = preg_replace(
            '/@endwhile\b/',
            '<?php endwhile; ?>',
            $content
        );
        
        // 18. PHP Code: @php ... @endphp
        $content = preg_replace(
            '/@php\b/',
            '<?php',
            $content
        );
        
        $content = preg_replace(
            '/@endphp\b/',
            '?>',
            $content
        );
        
        return $content;
    }

    /**
     * Layout-System verarbeiten
     * 
     * @param string $content Kompilierter Template-Inhalt
     * @return string Finaler PHP-Code
     */
    private function processLayout(string $content): string
    {
        // Prüfen ob Layout verwendet wird
        if (preg_match('/<\?php \$__layout = "([^"]+)"; \?>/', $content, $matches)) {
            $layoutName = $matches[1];
            
            // Layout-Inhalt entfernen (wird später eingefügt)
            $content = preg_replace('/<\?php \$__layout = "[^"]+"; \?>/', '', $content);
            
            // Layout-Template kompilieren
            $layoutPath = $this->resolveLayoutPath($layoutName);
            $layoutContent = file_get_contents($layoutPath);
            if ($layoutContent === false) {
                throw new RuntimeException("Layout nicht gefunden: {$layoutName}");
            }
            
            $compiledLayout = $this->compileDirectives($layoutContent);
            
            // FIXED: Content-Template zuerst ausführen, um Sections zu sammeln
            $content = "<?php " .
                      "ob_start(); ?>" . 
                      $content . 
                      "<?php \$__templateContent = ob_get_clean(); " .
                      "if (!isset(\$__view->sections['content'])) \$__view->sections['content'] = \$__templateContent; " .
                      "?>" . 
                      $compiledLayout;
        }
        
        return $content;
    }

    /**
     * Kompiliertes Template ausführen
     * 
     * @param string $compiledPath Pfad zur kompilierten Datei
     * @param array<string, mixed> $variables Template-Variablen
     * @return string Gerenderte Ausgabe
     */
    private function renderCompiled(string $compiledPath, array $variables): string
    {
        extract($variables);
        
        ob_start();
        try {
            include $compiledPath;
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
        
        return ob_get_clean();
    }

    /**
     * Sektion starten
     * 
     * @param string $name Sektionsname
     */
    public function startSection(string $name): void
    {
        $this->sectionStack[] = $name;
        ob_start();
    }

    /**
     * Sektion beenden
     */
    public function endSection(): void
    {
        if (empty($this->sectionStack)) {
            throw new RuntimeException('Keine aktive Sektion zum Beenden');
        }
        
        $name = array_pop($this->sectionStack);
        $this->sections[$name] = ob_get_clean();
    }

    /**
     * Sektion ausgeben
     * 
     * @param string $name Sektionsname
     * @param string $default Standardwert falls Sektion nicht existiert
     * @return string
     */
    public function yieldSection(string $name, string $default = ''): string
    {
        return $this->sections[$name] ?? $default;
    }

    /**
     * Partial-Template einbinden
     * 
     * @param string $partial Template-Name
     * @param array<string, mixed> $variables Verfügbare Variablen
     * @return string
     */
    public function includePartial(string $partial, array $variables = []): string
    {
        return $this->render($partial, $variables, true);
    }

    /**
     * Globale Variable setzen
     * 
     * @param string $key Variablenname
     * @param mixed $value Wert
     * @return self
     */
    public function share(string $key, mixed $value): self
    {
        $this->globals[$key] = $value;
        return $this;
    }

    /**
     * Mehrere globale Variablen setzen
     * 
     * @param array<string, mixed> $data Variablen-Array
     * @return self
     */
    public function shareAll(array $data): self
    {
        $this->globals = array_merge($this->globals, $data);
        return $this;
    }

    /**
     * Template-Cache leeren
     * 
     * @return self
     */
    public function clearCache(): self
    {
        $files = glob($this->cachePath . '/*.php');
        if ($files) {
            foreach ($files as $file) {
                unlink($file);
            }
        }
        
        $this->stats['compiled_templates'] = 0;
        $this->stats['cache_hits'] = 0;
        $this->stats['cache_misses'] = 0;
        
        return $this;
    }

    /**
     * Template-Pfad auflösen
     * 
     * @param string $template Template-Name
     * @return string Vollständiger Pfad
     */
    private function resolveTemplatePath(string $template): string
    {
        // Dots durch Slashes ersetzen für verschachtelte Views
        $path = str_replace('.', '/', $template);
        $fullPath = $this->viewPath . '/' . $path . '.php';
        
        if (!file_exists($fullPath)) {
            throw new InvalidArgumentException("Template nicht gefunden: {$template} ({$fullPath})");
        }
        
        return $fullPath;
    }

    /**
     * Layout-Pfad auflösen
     * 
     * @param string $layout Layout-Name
     * @return string Vollständiger Pfad
     */
    private function resolveLayoutPath(string $layout): string
    {
        $fullPath = $this->layoutPath . '/' . $layout . '.php';
        
        if (!file_exists($fullPath)) {
            throw new InvalidArgumentException("Layout nicht gefunden: {$layout} ({$fullPath})");
        }
        
        return $fullPath;
    }

    /**
     * Standard View-Pfad ermitteln
     * 
     * @return string
     */
    private function getDefaultViewPath(): string
    {
        // Vom aktuellen Verzeichnis ausgehend nach app/Views suchen
        $candidates = [
            getcwd() . '/app/Views',
            __DIR__ . '/../../app/Views',
            dirname(__DIR__, 2) . '/app/Views'
        ];
        
        foreach ($candidates as $path) {
            if (is_dir($path)) {
                return $path;
            }
        }
        
        // Fallback: app/Views im aktuellen Verzeichnis erstellen
        $defaultPath = getcwd() . '/app/Views';
        if (!is_dir($defaultPath)) {
            mkdir($defaultPath, 0755, true);
        }
        
        return $defaultPath;
    }

    /**
     * View-Statistiken abrufen
     * 
     * @return array<string, mixed>
     */
    public function getStats(): array
    {
        return $this->stats;
    }

    /**
     * Debug-Informationen ausgeben
     * 
     * @return array<string, mixed>
     */
    public function getDebugInfo(): array
    {
        return [
            'view_path' => $this->viewPath,
            'layout_path' => $this->layoutPath,
            'cache_path' => $this->cachePath,
            'debug_mode' => $this->debug,
            'globals' => array_keys($this->globals),
            'stats' => $this->stats,
            'available_templates' => $this->getAvailableTemplates()
        ];
    }

    /**
     * Verfügbare Templates auflisten
     * 
     * @return array<string>
     */
    private function getAvailableTemplates(): array
    {
        $templates = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->viewPath)
        );
        
        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $relativePath = substr($file->getPathname(), strlen($this->viewPath) + 1);
                $templates[] = str_replace(['/', '.php'], ['.', ''], $relativePath);
            }
        }
        
        return $templates;
    }

    /**
     * Statische Factory-Methode
     * 
     * @param string $viewPath View-Pfad
     * @param string|null $cachePath Cache-Pfad
     * @param bool $debug Debug-Modus
     * @return self
     */
    public static function create(
        string $viewPath = '',
        ?string $cachePath = null,
        bool $debug = false
    ): self {
        return new self($viewPath, $cachePath, $debug);
    }
}

<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Brick\Core\View;

/**
 * 🧪 View System Tests
 * 
 * Testet alle Features der Brick View Engine:
 * - Template-Rendering
 * - Blade-ähnliche Direktiven
 * - Layout-System
 * - Komponenten
 * - Caching
 * - Performance
 * 
 * @package Brick\Tests
 * @since 29.09.2025
 */
class ViewTest extends TestCase
{
    private View $view;
    private string $testViewPath;
    private string $testCachePath;

    protected function setUp(): void
    {
        // Test-Verzeichnisse erstellen
        $this->testViewPath = sys_get_temp_dir() . '/brick_view_tests';
        $this->testCachePath = sys_get_temp_dir() . '/brick_view_cache';
        
        // Verzeichnisse bereinigen und neu erstellen
        $this->cleanupTestDirectories();
        mkdir($this->testViewPath, 0755, true);
        mkdir($this->testViewPath . '/layouts', 0755, true);
        mkdir($this->testCachePath, 0755, true);
        
        // View-Instanz erstellen
        $this->view = new View($this->testViewPath, $this->testCachePath, true);
    }

    protected function tearDown(): void
    {
        $this->cleanupTestDirectories();
    }

    private function cleanupTestDirectories(): void
    {
        if (is_dir($this->testViewPath)) {
            $this->removeDirectory($this->testViewPath);
        }
        if (is_dir($this->testCachePath)) {
            $this->removeDirectory($this->testCachePath);
        }
    }

    private function removeDirectory(string $dir): void
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }

    public function testViewInstantiation(): void
    {
        $this->assertInstanceOf(View::class, $this->view);
        $this->assertIsArray($this->view->getStats());
        $this->assertIsArray($this->view->getDebugInfo());
    }

    public function testSimpleTemplateRendering(): void
    {
        // Test-Template erstellen
        file_put_contents($this->testViewPath . '/simple.php', '<h1>{{ $title }}</h1><p>{{ $content }}</p>');
        
        $result = $this->view->render('simple', [
            'title' => 'Test Title',
            'content' => 'Test Content'
        ]);
        
        $this->assertStringContainsString('Test Title', $result);
        $this->assertStringContainsString('Test Content', $result);
        $this->assertStringNotContainsString('{{', $result); // Direktiven sollten kompiliert sein
    }

    public function testEscapedOutput(): void
    {
        file_put_contents($this->testViewPath . '/escape.php', '<div>{{ $dangerous }}</div>');
        
        $result = $this->view->render('escape', [
            'dangerous' => '<script>alert("XSS")</script>'
        ]);
        
        $this->assertStringContainsString('&lt;script&gt;', $result);
        $this->assertStringNotContainsString('<script>', $result);
    }

    public function testUnescapedOutput(): void
    {
        file_put_contents($this->testViewPath . '/unescaped.php', '<div>{!! $html !!}</div>');
        
        $result = $this->view->render('unescaped', [
            'html' => '<strong>Bold Text</strong>'
        ]);
        
        $this->assertStringContainsString('<strong>Bold Text</strong>', $result);
    }

    public function testConditionalDirectives(): void
    {
        $template = '
        @if($showTitle)
            <h1>{{ $title }}</h1>
        @endif
        
        @if($user)
            <p>Hallo {{ $user }}</p>
        @else
            <p>Nicht angemeldet</p>
        @endif
        ';
        
        file_put_contents($this->testViewPath . '/conditional.php', $template);
        
        // Mit Titel und User
        $result1 = $this->view->render('conditional', [
            'showTitle' => true,
            'title' => 'Willkommen',
            'user' => 'Max'
        ]);
        
        $this->assertStringContainsString('<h1>Willkommen</h1>', $result1);
        $this->assertStringContainsString('Hallo Max', $result1);
        $this->assertStringNotContainsString('Nicht angemeldet', $result1);
        
        // Ohne Titel und User
        $result2 = $this->view->render('conditional', [
            'showTitle' => false,
            'user' => null
        ]);
        
        $this->assertStringNotContainsString('<h1>', $result2);
        $this->assertStringContainsString('Nicht angemeldet', $result2);
    }

    public function testLoopDirectives(): void
    {
        $template = '
        @foreach($items as $item)
            <li>{{ $item["name"] }} - {{ $item["price"] }}</li>
        @endforeach
        ';
        
        file_put_contents($this->testViewPath . '/loop.php', $template);
        
        $result = $this->view->render('loop', [
            'items' => [
                ['name' => 'Apfel', 'price' => '1.50'],
                ['name' => 'Banane', 'price' => '2.00']
            ]
        ]);
        
        $this->assertStringContainsString('<li>Apfel - 1.50</li>', $result);
        $this->assertStringContainsString('<li>Banane - 2.00</li>', $result);
    }

    public function testLayoutSystem(): void
    {
        // Layout erstellen
        $layout = '
        <html>
        <head><title>@yield("title", "Default")</title></head>
        <body>
            <header>@yield("header")</header>
            <main>@yield("content")</main>
        </body>
        </html>
        ';
        file_put_contents($this->testViewPath . '/layouts/master.php', $layout);
        
        // Template mit Layout
        $template = '
        @extends("master")
        
        @section("title")
        Testseite
        @endsection
        
        @section("content")
        <h1>{{ $heading }}</h1>
        <p>{{ $text }}</p>
        @endsection
        ';
        file_put_contents($this->testViewPath . '/with_layout.php', $template);
        
        $result = $this->view->render('with_layout', [
            'heading' => 'Test Heading',
            'text' => 'Test Content'
        ]);
        
        $this->assertStringContainsString('<html>', $result);
        $this->assertStringContainsString('<title>Testseite</title>', $result);
        $this->assertStringContainsString('<h1>Test Heading</h1>', $result);
        $this->assertStringContainsString('<p>Test Content</p>', $result);
    }

    public function testIncludeDirective(): void
    {
        // Partial erstellen
        file_put_contents($this->testViewPath . '/partial.php', '<div class="alert">{{ $message }}</div>');
        
        // Template mit Include
        $template = '
        <h1>Hauptseite</h1>
        @include("partial")
        ';
        file_put_contents($this->testViewPath . '/with_include.php', $template);
        
        $result = $this->view->render('with_include', [
            'message' => 'Include funktioniert!'
        ]);
        
        $this->assertStringContainsString('<h1>Hauptseite</h1>', $result);
        $this->assertStringContainsString('<div class="alert">Include funktioniert!</div>', $result);
    }

    public function testGlobalVariables(): void
    {
        file_put_contents($this->testViewPath . '/globals.php', '<p>{{ $globalVar }} - {{ $localVar }}</p>');
        
        $this->view->share('globalVar', 'Global Value');
        
        $result = $this->view->render('globals', [
            'localVar' => 'Local Value'
        ]);
        
        $this->assertStringContainsString('Global Value - Local Value', $result);
    }

    public function testCaching(): void
    {
        file_put_contents($this->testViewPath . '/cached.php', '<h1>{{ $title }}</h1>');
        
        // Erstes Rendering (Cache Miss)
        $result1 = $this->view->render('cached', ['title' => 'First']);
        $stats1 = $this->view->getStats();
        
        // Zweites Rendering (Cache Hit)
        $result2 = $this->view->render('cached', ['title' => 'Second']);
        $stats2 = $this->view->getStats();
        
        $this->assertStringContainsString('First', $result1);
        $this->assertStringContainsString('Second', $result2);
        $this->assertGreaterThan($stats1['cache_hits'], $stats2['cache_hits']);
    }

    public function testCacheClear(): void
    {
        file_put_contents($this->testViewPath . '/clear_test.php', '<p>Test</p>');
        
        // Template rendern (Cache erstellen)
        $this->view->render('clear_test');
        $this->assertGreaterThan(0, $this->view->getStats()['compiled_templates']);
        
        // Cache leeren
        $this->view->clearCache();
        $this->assertEquals(0, $this->view->getStats()['compiled_templates']);
    }

    public function testNestedSections(): void
    {
        $layout = '
        <html>
        <body>
            <main>@yield("content")</main>
            <aside>@yield("sidebar", "Standard Sidebar")</aside>
        </body>
        </html>
        ';
        file_put_contents($this->testViewPath . '/layouts/nested.php', $layout);
        
        $template = '
        @extends("nested")
        
        @section("content")
        <h1>Hauptinhalt</h1>
        @endsection
        
        @section("sidebar")
        <div>Custom Sidebar</div>
        @endsection
        ';
        file_put_contents($this->testViewPath . '/nested.php', $template);
        
        $result = $this->view->render('nested');
        
        $this->assertStringContainsString('<h1>Hauptinhalt</h1>', $result);
        $this->assertStringContainsString('<div>Custom Sidebar</div>', $result);
        $this->assertStringNotContainsString('Standard Sidebar', $result);
    }

    public function testPerformanceStatistics(): void
    {
        file_put_contents($this->testViewPath . '/perf.php', '<h1>{{ $title }}</h1>');
        
        $startTime = microtime(true);
        $this->view->render('perf', ['title' => 'Performance Test']);
        $endTime = microtime(true);
        
        $stats = $this->view->getStats();
        $this->assertGreaterThan(0, $stats['render_time']);
        $this->assertLessThan($endTime - $startTime, $stats['render_time']);
    }

    public function testErrorHandling(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Template nicht gefunden');
        
        $this->view->render('nonexistent');
    }

    public function testDebugInfo(): void
    {
        $debugInfo = $this->view->getDebugInfo();
        
        $this->assertArrayHasKey('view_path', $debugInfo);
        $this->assertArrayHasKey('cache_path', $debugInfo);
        $this->assertArrayHasKey('debug_mode', $debugInfo);
        $this->assertArrayHasKey('stats', $debugInfo);
        
        $this->assertEquals($this->testViewPath, $debugInfo['view_path']);
        $this->assertTrue($debugInfo['debug_mode']);
    }

    public function testStaticFactory(): void
    {
        $view = View::create($this->testViewPath, $this->testCachePath, true);
        $this->assertInstanceOf(View::class, $view);
    }

    public function testComplexTemplate(): void
    {
        // Komplexes Layout
        $layout = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>@yield("title", "Brick App")</title>
            <meta name="description" content="@yield("description", "Default Description")">
        </head>
        <body>
            <nav>@yield("navigation")</nav>
            <main>@yield("content")</main>
            <footer>@yield("footer", "© 2025 Brick Framework")</footer>
        </body>
        </html>
        ';
        file_put_contents($this->testViewPath . '/layouts/app.php', $layout);
        
        // Komplexes Template
        $template = '
        @extends("app")
        
        @section("title")
        {{ $pageTitle }} - Brick Framework
        @endsection
        
        @section("description")
        {{ $pageDescription }}
        @endsection
        
        @section("navigation")
        <ul>
            @foreach($menuItems as $item)
                <li><a href="{{ $item["url"] }}">{{ $item["title"] }}</a></li>
            @endforeach
        </ul>
        @endsection
        
        @section("content")
        <h1>{{ $pageTitle }}</h1>
        @if($showWelcome)
            <div class="welcome">Willkommen, {{ $userName }}!</div>
        @endif
        
        <div class="content">
            @foreach($articles as $article)
                <article>
                    <h2>{{ $article["title"] }}</h2>
                    <p>{{ $article["excerpt"] }}</p>
                    @if($article["featured"])
                        <span class="badge">Featured</span>
                    @endif
                </article>
            @endforeach
        </div>
        @endsection
        ';
        file_put_contents($this->testViewPath . '/complex.php', $template);
        
        $result = $this->view->render('complex', [
            'pageTitle' => 'Blog Übersicht',
            'pageDescription' => 'Die neuesten Artikel aus unserem Blog',
            'showWelcome' => true,
            'userName' => 'Max Mustermann',
            'menuItems' => [
                ['url' => '/', 'title' => 'Home'],
                ['url' => '/blog', 'title' => 'Blog'],
                ['url' => '/contact', 'title' => 'Kontakt']
            ],
            'articles' => [
                [
                    'title' => 'Erster Artikel',
                    'excerpt' => 'Das ist der erste Artikel.',
                    'featured' => true
                ],
                [
                    'title' => 'Zweiter Artikel', 
                    'excerpt' => 'Das ist der zweite Artikel.',
                    'featured' => false
                ]
            ]
        ]);
        
        // Validierungen
        $this->assertStringContainsString('<title>Blog Übersicht - Brick Framework</title>', $result);
        $this->assertStringContainsString('<meta name="description" content="Die neuesten Artikel aus unserem Blog">', $result);
        $this->assertStringContainsString('Willkommen, Max Mustermann!', $result);
        $this->assertStringContainsString('<a href="/">Home</a>', $result);
        $this->assertStringContainsString('<h2>Erster Artikel</h2>', $result);
        $this->assertStringContainsString('<span class="badge">Featured</span>', $result);
        $this->assertStringContainsString('© 2025 Brick Framework', $result);
    }
}
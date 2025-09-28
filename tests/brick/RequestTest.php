<?php

namespace Tests\Brick\Http;

use PHPUnit\Framework\TestCase;
use Brick\Http\Request;

/**
 * Tests für die Request-Klasse des Brick Frameworks
 *
 * @package Tests\Brick\Http
 * @author Jan P. Behrens <jp@bitka.de>
 */
class RequestTest extends TestCase
{
    private Request $request;

    protected function setUp(): void
    {
        // Mock globale PHP-Variablen für Tests
        $_GET = ['get_param' => 'get_value', 'shared' => 'from_get'];
        $_POST = ['post_param' => 'post_value', 'shared' => 'from_post'];
        $_SERVER = [
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/test/path?param=value',
            'HTTP_USER_AGENT' => 'TestBrowser/1.0',
            'HTTP_AUTHORIZATION' => 'Bearer test-token-123',
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
            'HTTP_ACCEPT' => 'application/json, text/html',
            'REMOTE_ADDR' => '192.168.1.100',
            'HTTPS' => 'on'
        ];
        $_FILES = [];
        $_COOKIE = ['test_cookie' => 'cookie_value'];
        $_SESSION = ['test_session' => 'session_value'];

        $this->request = new Request();
    }

    protected function tearDown(): void
    {
        // Reset globale Variablen
        $_GET = [];
        $_POST = [];
        $_SERVER = [];
        $_FILES = [];
        $_COOKIE = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
        }
    }

    // ===== HTTP METHOD TESTS =====

    public function testGetMethod(): void
    {
        $this->assertEquals('POST', $this->request->getMethod());
    }

    public function testGetUri(): void
    {
        $this->assertEquals('/test/path', $this->request->getUri());
    }

    public function testIsMethod(): void
    {
        $this->assertTrue($this->request->isMethod('POST'));
        $this->assertTrue($this->request->isMethod('post'));
        $this->assertFalse($this->request->isMethod('GET'));
    }

    public function testHttpMethodHelpers(): void
    {
        $this->assertTrue($this->request->isPost());
        $this->assertFalse($this->request->isGet());
        $this->assertFalse($this->request->isPut());
        $this->assertFalse($this->request->isDelete());
        $this->assertFalse($this->request->isPatch());
    }

    // ===== INPUT HANDLING TESTS =====

    public function testGet(): void
    {
        $this->assertEquals('get_value', $this->request->get('get_param'));
        $this->assertEquals('default', $this->request->get('nonexistent', 'default'));
        $this->assertNull($this->request->get('nonexistent'));
    }

    public function testPost(): void
    {
        $this->assertEquals('post_value', $this->request->post('post_param'));
        $this->assertEquals('default', $this->request->post('nonexistent', 'default'));
        $this->assertNull($this->request->post('nonexistent'));
    }

    public function testInput(): void
    {
        // POST hat Vorrang vor GET
        $this->assertEquals('from_post', $this->request->input('shared'));
        $this->assertEquals('get_value', $this->request->input('get_param'));
        $this->assertEquals('post_value', $this->request->input('post_param'));
        $this->assertEquals('default', $this->request->input('nonexistent', 'default'));
    }

    public function testAll(): void
    {
        $all = $this->request->all();
        $this->assertIsArray($all);
        $this->assertArrayHasKey('get_param', $all);
        $this->assertArrayHasKey('post_param', $all);
        $this->assertEquals('from_post', $all['shared']); // POST überschreibt GET
    }

    public function testOnly(): void
    {
        $filtered = $this->request->only('get_param', 'post_param');
        $this->assertCount(2, $filtered);
        $this->assertEquals('get_value', $filtered['get_param']);
        $this->assertEquals('post_value', $filtered['post_param']);
    }

    public function testHas(): void
    {
        $this->assertTrue($this->request->has('get_param'));
        $this->assertTrue($this->request->has('post_param'));
        $this->assertTrue($this->request->has('get_param', 'post_param'));
        $this->assertFalse($this->request->has('nonexistent'));
        $this->assertFalse($this->request->has('get_param', 'nonexistent'));
    }

    // ===== SESSION TESTS =====

    public function testSession(): void
    {
        // Einzelnen Wert abrufen
        $this->assertEquals('session_value', $this->request->session('test_session'));
        $this->assertEquals('default', $this->request->session('nonexistent', 'default'));
        
        // Alle Session-Daten
        $all = $this->request->session();
        $this->assertIsArray($all);
        $this->assertArrayHasKey('test_session', $all);
    }

    public function testSetSession(): void
    {
        $result = $this->request->setSession('new_key', 'new_value');
        
        // Fluent interface
        $this->assertInstanceOf(Request::class, $result);
        
        // Wert wurde gesetzt
        $this->assertEquals('new_value', $this->request->session('new_key'));
    }

    // ===== COOKIE TESTS =====

    public function testCookie(): void
    {
        $this->assertEquals('cookie_value', $this->request->cookie('test_cookie'));
        $this->assertEquals('default', $this->request->cookie('nonexistent', 'default'));
        $this->assertNull($this->request->cookie('nonexistent'));
    }

    public function testSetCookie(): void
    {
        // Note: setcookie kann in CLI-Tests nicht getestet werden
        // Aber wir können prüfen, ob die Methode existiert und Fluent Interface zurückgibt
        $result = $this->request->setCookie('test', 'value');
        $this->assertInstanceOf(Request::class, $result);
    }

    // ===== HEADER TESTS =====

    public function testGetHeader(): void
    {
        $this->assertEquals('TestBrowser/1.0', $this->request->getHeader('User-Agent'));
        $this->assertEquals('XMLHttpRequest', $this->request->getHeader('X-Requested-With'));
        $this->assertNull($this->request->getHeader('Nonexistent'));
    }

    public function testBearerToken(): void
    {
        $this->assertEquals('test-token-123', $this->request->bearerToken());
    }

    // ===== REQUEST INFO TESTS =====

    public function testIsSecure(): void
    {
        $this->assertTrue($this->request->isSecure());
    }

    public function testIsAjax(): void
    {
        $this->assertTrue($this->request->isAjax());
    }

    public function testWantsJson(): void
    {
        $this->assertTrue($this->request->wantsJson());
    }

    public function testIp(): void
    {
        $this->assertEquals('192.168.1.100', $this->request->ip());
    }

    public function testUserAgent(): void
    {
        $this->assertEquals('TestBrowser/1.0', $this->request->userAgent());
    }

    // ===== COMPONENT ACCESS TESTS =====

    public function testGetSession(): void
    {
        $session = $this->request->getSession();
        $this->assertInstanceOf(\Brick\Http\Request\Session::class, $session);
    }

    public function testGetCookie(): void
    {
        $cookie = $this->request->getCookie();
        $this->assertInstanceOf(\Brick\Http\Request\Cookie::class, $cookie);
    }

    public function testGetHeaders(): void
    {
        $headers = $this->request->getHeaders();
        $this->assertInstanceOf(\Brick\Http\Request\HeaderBag::class, $headers);
    }

    // ===== MAGIC METHODS TESTS =====

    public function testMagicGet(): void
    {
        $this->assertEquals('POST', $this->request->method);
        $this->assertEquals('/test/path', $this->request->uri);
        $this->assertEquals('192.168.1.100', $this->request->ip);
        $this->assertEquals('TestBrowser/1.0', $this->request->userAgent);
        $this->assertEquals('get_value', $this->request->get_param);
    }

    public function testMagicIsset(): void
    {
        $this->assertTrue(isset($this->request->method));
        $this->assertTrue(isset($this->request->uri));
        $this->assertTrue(isset($this->request->get_param));
        $this->assertFalse(isset($this->request->nonexistent));
    }

    // ===== EDGE CASE TESTS =====

    public function testGetMethodWithEmptyServer(): void
    {
        $_SERVER = [];
        $request = new Request();
        $this->assertEquals('GET', $request->getMethod()); // Default
    }

    public function testUriWithoutRequestUri(): void
    {
        $_SERVER = [];
        $request = new Request();
        $this->assertEquals('/', $request->getUri()); // Default
    }

    public function testIpWithProxyHeaders(): void
    {
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '203.0.113.1';
        $request = new Request();
        $this->assertEquals('203.0.113.1', $request->ip());
    }

    public function testSecureWithProxy(): void
    {
        $_SERVER['HTTPS'] = '';
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        $request = new Request();
        $this->assertTrue($request->isSecure());
    }
}
<?php

namespace Tests\Brick\Http;

use Brick\Http\Response;
use PHPUnit\Framework\TestCase;

/**
 * Response-Klasse Tests für das Brick Framework
 * 
 * @package Tests\Brick\Http
 * @author JP Behrens <jp@bitka.de>
 */
class ResponseTest extends TestCase
{
    protected Response $response;

    protected function setUp(): void
    {
        $this->response = new Response();
    }

    protected function tearDown(): void
    {
        // Response zurücksetzen nach jedem Test
        $this->response->reset();
        
        // Alle Output Buffer leeren
        while (ob_get_level()) {
            ob_end_clean();
        }
    }

    // ==========================================
    // BASIC FUNCTIONALITY TESTS
    // ==========================================

    public function testDefaultStatus(): void
    {
        $this->assertEquals(Response::HTTP_OK, $this->response->getStatus());
    }

    public function testSetStatus(): void
    {
        $this->response->status(Response::HTTP_NOT_FOUND);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->response->getStatus());
    }

    public function testFluentInterface(): void
    {
        $result = $this->response
            ->status(Response::HTTP_CREATED)
            ->header('Content-Type', 'application/json')
            ->body('test');

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(Response::HTTP_CREATED, $this->response->getStatus());
    }

    // ==========================================
    // HEADER TESTS
    // ==========================================

    public function testSingleHeader(): void
    {
        $this->response->header('X-Custom-Header', 'test-value');
        $headers = $this->response->getHeaders();
        
        $this->assertArrayHasKey('X-Custom-Header', $headers);
        $this->assertEquals('test-value', $headers['X-Custom-Header']);
    }

    public function testMultipleHeaders(): void
    {
        $testHeaders = [
            'X-Header-1' => 'value1',
            'X-Header-2' => 'value2',
            'Content-Type' => 'application/json'
        ];

        $this->response->headers($testHeaders);
        $headers = $this->response->getHeaders();

        foreach ($testHeaders as $name => $value) {
            $this->assertArrayHasKey($name, $headers);
            $this->assertEquals($value, $headers[$name]);
        }
    }

    public function testHeaderOverwrite(): void
    {
        $this->response->header('Content-Type', 'text/plain');
        $this->response->header('Content-Type', 'application/json');
        
        $headers = $this->response->getHeaders();
        $this->assertEquals('application/json', $headers['Content-Type']);
    }

    // ==========================================
    // BODY TESTS
    // ==========================================

    public function testStringBody(): void
    {
        $testContent = 'Hello World';
        $this->response->body($testContent);
        
        $this->assertEquals($testContent, $this->response->getBody());
    }

    public function testArrayBody(): void
    {
        $testData = ['key' => 'value', 'number' => 42];
        $this->response->body($testData);
        
        $this->assertEquals($testData, $this->response->getBody());
    }

    public function testNullBody(): void
    {
        $this->assertNull($this->response->getBody());
    }

    // ==========================================
    // JSON RESPONSE TESTS
    // ==========================================

    public function testJsonResponse(): void
    {
        $data = ['message' => 'Hello', 'status' => 'success'];
        $this->response->json($data);
        
        $this->assertEquals($data, $this->response->getBody());
        $this->assertEquals('application/json; charset=utf-8', $this->response->getHeaders()['Content-Type']);
    }

    public function testJsonWithCustomStatus(): void
    {
        $data = ['error' => 'Not found'];
        $this->response->json($data, Response::HTTP_NOT_FOUND);
        
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->response->getStatus());
        $this->assertEquals($data, $this->response->getBody());
    }

    // ==========================================
    // TEXT RESPONSE TESTS
    // ==========================================

    public function testTextResponse(): void
    {
        $text = 'Plain text content';
        $this->response->text($text);
        
        $this->assertEquals($text, $this->response->getBody());
        $this->assertEquals('text/plain; charset=utf-8', $this->response->getHeaders()['Content-Type']);
    }

    public function testTextWithCustomStatus(): void
    {
        $text = 'Internal Server Error';
        $this->response->text($text, Response::HTTP_INTERNAL_SERVER_ERROR);
        
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $this->response->getStatus());
    }

    // ==========================================
    // HTML RESPONSE TESTS
    // ==========================================

    public function testHtmlResponse(): void
    {
        $html = '<h1>Hello World</h1>';
        $this->response->html($html);
        
        $this->assertEquals($html, $this->response->getBody());
        $this->assertEquals('text/html; charset=utf-8', $this->response->getHeaders()['Content-Type']);
    }

    // ==========================================
    // XML RESPONSE TESTS
    // ==========================================

    public function testXmlResponse(): void
    {
        $xml = '<?xml version="1.0"?><root><message>Hello</message></root>';
        $this->response->xml($xml);
        
        $this->assertEquals($xml, $this->response->getBody());
        $this->assertEquals('application/xml; charset=utf-8', $this->response->getHeaders()['Content-Type']);
    }

    // ==========================================
    // REDIRECT TESTS
    // ==========================================

    public function testRedirectValidUrl(): void
    {        
        // Test mit gültiger URL
        ob_start();
        try {
            $this->response->setExitAfterRedirect(false);
            $this->response->redirect('https://example.com');
        } finally {
            ob_end_clean();
        }
        
        $headers = $this->response->getHeaders();
        $this->assertEquals('https://example.com', $headers['Location']);
        $this->assertEquals(Response::HTTP_FOUND, $this->response->getStatus());
    }

    public function testRedirectRelativePath(): void
    {
        ob_start();
        try {
            $this->response->setExitAfterRedirect(false);
            $this->response->redirect('/dashboard');
        } finally {
            ob_end_clean();
        }
        
        $headers = $this->response->getHeaders();
        $this->assertEquals('/dashboard', $headers['Location']);
    }

    public function testRedirectInvalidUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid redirect URL');
        
        $this->response->setExitAfterRedirect(false);
        $this->response->redirect('invalid-url');
    }

    public function testRedirectCustomStatus(): void
    {
        ob_start();
        try {
            $this->response->setExitAfterRedirect(false);
            $this->response->redirect('/moved', Response::HTTP_MOVED_PERMANENTLY);
        } finally {
            ob_end_clean();
        }
        
        $this->assertEquals(Response::HTTP_MOVED_PERMANENTLY, $this->response->getStatus());
    }

    // ==========================================
    // ERROR RESPONSE TESTS
    // ==========================================

    public function testErrorResponse(): void
    {
        $this->response->error('Something went wrong');
        
        $body = $this->response->getBody();
        $this->assertIsArray($body);
        $this->assertEquals('Something went wrong', $body['message']);
        $this->assertFalse($body['success']);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $body['status']);
    }

    public function testErrorWithCustomStatus(): void
    {
        $this->response->error('Bad Request', Response::HTTP_BAD_REQUEST);
        
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->response->getStatus());
    }

    public function testErrorWithErrors(): void
    {
        $errors = ['field1' => 'Required', 'field2' => 'Invalid format'];
        $this->response->error('Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY, $errors);
        
        $body = $this->response->getBody();
        $this->assertEquals($errors, $body['errors']);
    }

    // ==========================================
    // SUCCESS RESPONSE TESTS
    // ==========================================

    public function testSuccessResponse(): void
    {
        $this->response->success('Operation completed');
        
        $body = $this->response->getBody();
        $this->assertIsArray($body);
        $this->assertEquals('Operation completed', $body['message']);
        $this->assertTrue($body['success']);
    }

    public function testSuccessWithData(): void
    {
        $data = ['user_id' => 123, 'username' => 'john'];
        $this->response->success('User created', $data);
        
        $body = $this->response->getBody();
        $this->assertEquals($data, $body['data']);
    }

    // ==========================================
    // SPECIFIC ERROR RESPONSE TESTS
    // ==========================================

    public function testNotFoundResponse(): void
    {
        $this->response->notFound('Resource not found');
        
        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->response->getStatus());
        
        $body = $this->response->getBody();
        $this->assertEquals('Resource not found', $body['message']);
    }

    public function testUnauthorizedResponse(): void
    {
        $this->response->unauthorized('Please login');
        
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->response->getStatus());
    }

    public function testForbiddenResponse(): void
    {
        $this->response->forbidden('Access denied');
        
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->response->getStatus());
    }

    public function testValidationErrorResponse(): void
    {
        $errors = ['email' => 'Invalid email format'];
        $this->response->validationError($errors);
        
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response->getStatus());
        
        $body = $this->response->getBody();
        $this->assertEquals($errors, $body['errors']);
    }

    // ==========================================
    // SECURITY HEADERS TESTS
    // ==========================================

    public function testSecureHeaders(): void
    {
        $this->response->secureHeaders();
        
        $headers = $this->response->getHeaders();
        $this->assertEquals('nosniff', $headers['X-Content-Type-Options']);
        $this->assertEquals('DENY', $headers['X-Frame-Options']);
        $this->assertEquals('1; mode=block', $headers['X-XSS-Protection']);
        $this->assertTrue(str_contains($headers['Referrer-Policy'], 'strict-origin-when-cross-origin'));
    }

    // ==========================================
    // CORS TESTS
    // ==========================================

    public function testCorsHeaders(): void
    {
        $this->response->cors('https://example.com', ['GET', 'POST'], ['Content-Type']);
        
        $headers = $this->response->getHeaders();
        $this->assertEquals('https://example.com', $headers['Access-Control-Allow-Origin']);
        $this->assertEquals('GET, POST', $headers['Access-Control-Allow-Methods']);
        $this->assertEquals('Content-Type', $headers['Access-Control-Allow-Headers']);
    }

    public function testCorsDefaultValues(): void
    {
        $this->response->cors();
        
        $headers = $this->response->getHeaders();
        $this->assertEquals('*', $headers['Access-Control-Allow-Origin']);
        $this->assertTrue(str_contains($headers['Access-Control-Allow-Methods'], 'GET'));
    }

    // ==========================================
    // CACHE TESTS
    // ==========================================

    public function testCacheHeaders(): void
    {
        $this->response->cache(3600, true);
        
        $headers = $this->response->getHeaders();
        $this->assertTrue(str_contains($headers['Cache-Control'], 'public'));
        $this->assertTrue(str_contains($headers['Cache-Control'], 'max-age=3600'));
        $this->assertArrayHasKey('Expires', $headers);
    }

    public function testPrivateCache(): void
    {
        $this->response->cache(1800, false);
        
        $headers = $this->response->getHeaders();
        $this->assertTrue(str_contains($headers['Cache-Control'], 'private'));
    }

    public function testNoCache(): void
    {
        $this->response->noCache();
        
        $headers = $this->response->getHeaders();
        $this->assertTrue(str_contains($headers['Cache-Control'], 'no-cache'));
        $this->assertEquals('no-cache', $headers['Pragma']);
        $this->assertEquals('0', $headers['Expires']);
    }

    // ==========================================
    // UTILITY TESTS
    // ==========================================

    public function testIsSent(): void
    {
        $this->assertFalse($this->response->isSent());
    }

    public function testReset(): void
    {
        $this->response
            ->status(Response::HTTP_NOT_FOUND)
            ->header('X-Test', 'value')
            ->body('test content');
        
        $this->response->reset();
        
        $this->assertEquals(Response::HTTP_OK, $this->response->getStatus());
        $this->assertEmpty($this->response->getHeaders());
        $this->assertNull($this->response->getBody());
    }

    // ==========================================
    // CONSTANTS TESTS
    // ==========================================

    public function testHttpConstants(): void
    {
        $this->assertEquals(200, Response::HTTP_OK);
        $this->assertEquals(201, Response::HTTP_CREATED);
        $this->assertEquals(204, Response::HTTP_NO_CONTENT);
        $this->assertEquals(301, Response::HTTP_MOVED_PERMANENTLY);
        $this->assertEquals(302, Response::HTTP_FOUND);
        $this->assertEquals(400, Response::HTTP_BAD_REQUEST);
        $this->assertEquals(401, Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(403, Response::HTTP_FORBIDDEN);
        $this->assertEquals(404, Response::HTTP_NOT_FOUND);
        $this->assertEquals(422, Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertEquals(500, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    // ==========================================
    // JSON ENCODING TESTS
    // ==========================================

    public function testJsonEncodingWithSpecialCharacters(): void
    {
        $data = [
            'message' => 'Hëllö Wörld! 🌍',
            'symbols' => ['<script>', '&amp;', '"quotes"'],
            'number' => 42.5
        ];
        
        $this->response->json($data);
        $this->assertEquals($data, $this->response->getBody());
    }

    public function testEmptyJsonResponse(): void
    {
        $this->response->json([]);
        $this->assertEquals([], $this->response->getBody());
    }

    // ==========================================
    // CONFIGURATION TESTS
    // ==========================================

    public function testExitAfterRedirectConfiguration(): void
    {
        $this->response->setExitAfterRedirect(false);
        
        // Test mit Reflection, da die Property protected ist
        $reflection = new \ReflectionClass($this->response);
        $property = $reflection->getProperty('exitAfterRedirect');
        $property->setAccessible(true);
        
        $this->assertFalse($property->getValue($this->response));
    }

    public function testSecurityHeadersConfiguration(): void
    {
        $this->response->setSecurityHeaders(false);
        
        // Test mit Reflection
        $reflection = new \ReflectionClass($this->response);
        $property = $reflection->getProperty('securityHeadersEnabled');
        $property->setAccessible(true);
        
        $this->assertFalse($property->getValue($this->response));
    }
}
<?php

namespace Tests\Brick\Core;

use PHPUnit\Framework\TestCase;
use Brick\Core\Router;
use Brick\Core\RouterGroup;
use InvalidArgumentException;
use RuntimeException;

/**
 * Tests für die Router-Klasse des Brick Frameworks
 *
 * @package Tests\Brick\Core
 * @author JP Behrens <jp@bitka.de>
 */
class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router();
    }

    public function testRouterInstantiation(): void
    {
        $this->assertInstanceOf(Router::class, $this->router);
    }

    public function testAddRoute(): void
    {
        $handler = fn() => 'test response';
        $result = $this->router->add('GET', '/test', $handler);
        
        $this->assertInstanceOf(Router::class, $result);
        
        $routes = $this->router->getRoutes();
        $this->assertArrayHasKey('GET', $routes);
        $this->assertCount(1, $routes['GET']);
    }

    public function testHttpMethodHelpers(): void
    {
        $handler = fn() => 'response';
        
        $this->router->get('/get', $handler);
        $this->router->post('/post', $handler);
        $this->router->put('/put', $handler);
        $this->router->patch('/patch', $handler);
        $this->router->delete('/delete', $handler);
        $this->router->options('/options', $handler);
        
        $routes = $this->router->getRoutes();
        
        $this->assertArrayHasKey('GET', $routes);
        $this->assertArrayHasKey('POST', $routes);
        $this->assertArrayHasKey('PUT', $routes);
        $this->assertArrayHasKey('PATCH', $routes);
        $this->assertArrayHasKey('DELETE', $routes);
        $this->assertArrayHasKey('OPTIONS', $routes);
    }

    public function testInvalidHttpMethod(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Invalid HTTP method/');
        
        $this->router->add('INVALID', '/test', fn() => 'test');
    }

    public function testMultipleHttpMethods(): void
    {
        $handler = fn() => 'response';
        $this->router->add(['GET', 'POST'], '/multi', $handler);
        
        $routes = $this->router->getRoutes();
        
        $this->assertArrayHasKey('GET', $routes);
        $this->assertArrayHasKey('POST', $routes);
        $this->assertCount(1, $routes['GET']);
        $this->assertCount(1, $routes['POST']);
    }

    public function testStaticRouteMatching(): void
    {
        $handler = fn() => 'home page';
        $this->router->get('/', $handler);
        $this->router->get('/about', $handler);
        
        $result = $this->router->dispatch('GET', '/');
        $this->assertTrue($result['found']);
        $this->assertEquals('home page', $result['handler']());
        $this->assertEmpty($result['params']);
        
        $result = $this->router->dispatch('GET', '/about');
        $this->assertTrue($result['found']);
        $this->assertEquals('home page', $result['handler']());
    }

    public function testDynamicRouteMatching(): void
    {
        $handler = fn() => 'user profile';
        $this->router->get('/users/{id}', $handler);
        
        $result = $this->router->dispatch('GET', '/users/123');
        
        $this->assertTrue($result['found']);
        $this->assertEquals('user profile', $result['handler']());
        $this->assertEquals(['id' => '123'], $result['params']);
    }

    public function testDynamicRouteWithRegex(): void
    {
        $handler = fn() => 'numeric user';
        $this->router->get('/users/{id:\d+}', $handler);
        
        // Should match numeric ID
        $result = $this->router->dispatch('GET', '/users/123');
        $this->assertTrue($result['found']);
        $this->assertEquals(['id' => '123'], $result['params']);
        
        // Should not match non-numeric ID
        $result = $this->router->dispatch('GET', '/users/abc');
        $this->assertFalse($result['found']);
    }

    public function testMultipleParameters(): void
    {
        $handler = fn() => 'user post';
        $this->router->get('/users/{userId}/posts/{postId}', $handler);
        
        $result = $this->router->dispatch('GET', '/users/123/posts/456');
        
        $this->assertTrue($result['found']);
        $this->assertEquals([
            'userId' => '123',
            'postId' => '456'
        ], $result['params']);
    }

    public function testRouteGroups(): void
    {
        $this->router->group('/api', function(RouterGroup $group) {
            $group->get('/users', fn() => 'api users');
            $group->post('/users', fn() => 'create user');
            $group->get('/posts', fn() => 'api posts');
        });
        
        $result = $this->router->dispatch('GET', '/api/users');
        $this->assertTrue($result['found']);
        $this->assertEquals('api users', $result['handler']());
        
        $result = $this->router->dispatch('POST', '/api/users');
        $this->assertTrue($result['found']);
        $this->assertEquals('create user', $result['handler']());
        
        $result = $this->router->dispatch('GET', '/api/posts');
        $this->assertTrue($result['found']);
        $this->assertEquals('api posts', $result['handler']());
    }

    public function testNestedRouteGroups(): void
    {
        $this->router->group('/api', function(RouterGroup $api) {
            // RouterGroup doesn't support nested groups, so we test prefix concatenation
            $api->get('/v1/users', fn() => 'v1 users');
        });
        
        $result = $this->router->dispatch('GET', '/api/v1/users');
        $this->assertTrue($result['found']);
        $this->assertEquals('v1 users', $result['handler']());
    }

    public function testRouteGroupsWithParameters(): void
    {
        $this->router->group('/api', function(RouterGroup $group) {
            $group->get('/users/{id}', fn() => 'api user');
        });
        
        $result = $this->router->dispatch('GET', '/api/users/123');
        $this->assertTrue($result['found']);
        $this->assertEquals(['id' => '123'], $result['params']);
    }

    public function testNoMatchReturnsNull(): void
    {
        $this->router->get('/exists', fn() => 'exists');
        
        $result = $this->router->dispatch('GET', '/does-not-exist');
        $this->assertFalse($result['found']);
        
        $result = $this->router->dispatch('POST', '/exists');
        $this->assertFalse($result['found']);
    }

    public function testGetStats(): void
    {
        $this->router->get('/static', fn() => 'static');
        $this->router->get('/dynamic/{id}', fn() => 'dynamic');
        
        $stats = $this->router->getStats();
        
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_routes', $stats);
        $this->assertArrayHasKey('static_routes', $stats);
        $this->assertArrayHasKey('dynamic_routes', $stats);
        $this->assertEquals(2, $stats['total_routes']);
        $this->assertEquals(1, $stats['static_routes']);
        $this->assertEquals(1, $stats['dynamic_routes']);
    }

    public function testDumpRoutes(): void
    {
        $this->router->get('/test', fn() => 'test');
        
        $dump = $this->router->dumpRoutes();
        
        $this->assertIsString($dump);
        $this->assertStringContainsString('Route Dump', $dump);
        $this->assertStringContainsString('/test', $dump);
    }

    public function testRouteCompilationWithInvalidParameterName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid parameter name');
        
        $this->router->get('/users/{123invalid}', fn() => 'test');
    }

    public function testRouteCompilationWithValidRegex(): void
    {
        $handler = fn() => 'test';
        $this->router->get('/users/{id:\\d{3,5}}', $handler);
        
        // Should match 3-5 digits
        $result = $this->router->dispatch('GET', '/users/1234');
        $this->assertTrue($result['found']);
        $this->assertEquals(['id' => '1234'], $result['params']);
        
        // Should not match 1-2 digits
        $result = $this->router->dispatch('GET', '/users/12');
        $this->assertFalse($result['found']);
    }

    public function testRootPath(): void
    {
        $handler = fn() => 'root';
        $this->router->get('/', $handler);
        
        $result = $this->router->dispatch('GET', '/');
        $this->assertTrue($result['found']);
        $this->assertEquals('root', $result['handler']());
    }

    public function testPathNormalization(): void
    {
        $handler = fn() => 'normalized';
        $this->router->get('/path/', $handler);
        
        $result = $this->router->dispatch('GET', '/path');
        $this->assertTrue($result['found']);
        
        $result = $this->router->dispatch('GET', '/path/');
        $this->assertTrue($result['found']);
    }

    public function testCaseSensitiveRoutes(): void
    {
        $handler = fn() => 'case sensitive';
        $this->router->get('/CamelCase', $handler);
        
        $result = $this->router->dispatch('GET', '/CamelCase');
        $this->assertTrue($result['found']);
        
        $result = $this->router->dispatch('GET', '/camelcase');
        $this->assertFalse($result['found']);
    }
}
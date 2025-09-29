<?php

declare(strict_types=1);

namespace Brick\Core;

use InvalidArgumentException;
use RuntimeException;

/**
 * 🧱 Brick Framework - High-Performance HTTP Router
 *
 * Ultra-fast HTTP router with intelligent route matching, parameter extraction,
 * and comprehensive developer experience features.
 *
 * ✨ Features:
 * - O(1) static route lookup
 * - Segment-count based dynamic route pre-filtering
 * - Minimal regex usage for maximum performance
 * - Custom parameter patterns
 * - Route groups and prefixes
 * - Comprehensive error handling
 * - Debug and introspection tools
 *
 * 📖 Usage:
 * ```php
 * $router = new Router();
 * 
 * // Static routes
 * $router->get('/', HomeController::class);
 * $router->post('/login', [AuthController::class, 'login']);
 * 
 * // Dynamic routes with parameters
 * $router->get('/user/{id:\d+}', UserController::class);
 * $router->get('/post/{slug:[a-z0-9-]+}', PostController::class);
 * 
 * // Route groups
 * $router->group('/api/v1', function($router) {
 *     $router->get('/users', ApiController::class);
 *     $router->post('/users', [ApiController::class, 'create']);
 * });
 * 
 * // Dispatch
 * $result = $router->dispatch('GET', '/user/123');
 * if ($result['found']) {
 *     $handler = $result['handler'];
 *     $params = $result['params'];
 * }
 * ```
 *
 * @package Brick\Core
 * @version 1.1
 * @author  JP Behrens <jp@bitka.de>
 * @since   1.0
 */
final class Router
{
    // ==========================================
    // HTTP METHODS CONSTANTS
    // ==========================================

    /** @var array<string> Valid HTTP methods */
    private const VALID_METHODS = [
        'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 
        'OPTIONS', 'HEAD', 'CONNECT', 'TRACE'
    ];

    // ==========================================
    // PROPERTIES
    // ==========================================

    /** @var array<string, array<string, mixed>> Static routes for O(1) lookup */
    private array $static = [];

    /** @var array<string, array<int, list<Route>>> Dynamic routes grouped by method and segment count */
    private array $dynamic = [];

    /** @var array<string, mixed> Compiled route cache for performance */
    private array $compiledCache = [];

    /** @var int Total number of registered routes */
    private int $routeCount = 0;

    /** @var array<string, int> Route statistics */
    private array $stats = [];

    // ==========================================
    // CONSTRUCTOR
    // ==========================================

    /**
     * Creates a new router instance
     *
     * @param bool $ignoreTrailingSlash Whether to ignore trailing slashes
     * @param bool $decodeUrl Whether to decode URLs
     * @param bool $cacheCompiledRoutes Whether to cache compiled routes
     */
    public function __construct(
        private readonly bool $ignoreTrailingSlash = true,
        private readonly bool $decodeUrl = true,
        private readonly bool $cacheCompiledRoutes = true
    ) {
        $this->initializeStats();
    }

    // ==========================================
    // ROUTE REGISTRATION
    // ==========================================

    /**
     * 📝 Registers a route with specified HTTP method(s)
     *
     * @param string|array<string> $method HTTP method(s)
     * @param string $path Route path with optional parameters
     * @param mixed $handler Route handler (callable, class, etc.)
     * @return self For method chaining
     * @throws InvalidArgumentException For invalid methods or paths
     */
    public function add(string|array $method, string $path, mixed $handler): self
    {
        $methods = (array) $method;
        $this->validatePath($path);

        $normalizedPath = $this->normalize($path);
        $cacheKey = $this->getCacheKey($normalizedPath, $handler);
        
        // Use cache if enabled
        if ($this->cacheCompiledRoutes && isset($this->compiledCache[$cacheKey])) {
            $compiled = $this->compiledCache[$cacheKey];
        } else {
            $compiled = Route::compile($normalizedPath, $handler);
            if ($this->cacheCompiledRoutes) {
                $this->compiledCache[$cacheKey] = $compiled;
            }
        }

        foreach ($methods as $m) {
            $validatedMethod = $this->validateMethod($m);
            
            if ($compiled->isStatic) {
                $this->static[$validatedMethod][$compiled->raw] = $handler;
                $this->stats['static']++;
            } else {
                $this->dynamic[$validatedMethod][$compiled->segmentCount][] = $compiled;
                $this->stats['dynamic']++;
            }
            
            $this->routeCount++;
        }

        return $this;
    }

    /**
     * 🌐 Convenience methods for common HTTP verbs
     */
    public function get(string $path, mixed $handler): self
    {
        return $this->add('GET', $path, $handler);
    }

    public function post(string $path, mixed $handler): self
    {
        return $this->add('POST', $path, $handler);
    }

    public function put(string $path, mixed $handler): self
    {
        return $this->add('PUT', $path, $handler);
    }

    public function patch(string $path, mixed $handler): self
    {
        return $this->add('PATCH', $path, $handler);
    }

    public function delete(string $path, mixed $handler): self
    {
        return $this->add('DELETE', $path, $handler);
    }

    public function options(string $path, mixed $handler): self
    {
        return $this->add('OPTIONS', $path, $handler);
    }

    /**
     * 👥 Route group with common prefix
     *
     * @param string $prefix Common path prefix
     * @param callable $callback Callback to register routes
     * @return self For method chaining
     */
    public function group(string $prefix, callable $callback): self
    {
        $groupRouter = new RouterGroup($this, $prefix);
        $callback($groupRouter);
        return $this;
    }

    // ==========================================
    // ROUTE MATCHING & DISPATCHING
    // ==========================================

    /**
     * 🔍 Dispatches a request and finds matching route
     *
     * @param string $method HTTP method
     * @param string $path Request path
     * @return array{found: bool, handler?: mixed, params?: array<string,string>, route?: Route}
     * @throws InvalidArgumentException For invalid HTTP methods
     */
    public function dispatch(string $method, string $path): array
    {
        $validatedMethod = $this->validateMethod($method);
        $normalizedPath = $this->normalize($path);

        // 1) O(1) static route lookup
        if (isset($this->static[$validatedMethod][$normalizedPath])) {
            return [
                'found' => true, 
                'handler' => $this->static[$validatedMethod][$normalizedPath], 
                'params' => []
            ];
        }

        // 2) Dynamic route matching with segment count optimization
        $segments = $this->getPathSegments($normalizedPath);
        $segmentCount = count($segments);
        $candidates = $this->dynamic[$validatedMethod][$segmentCount] ?? [];

        if (empty($candidates)) {
            return ['found' => false];
        }

        // 3) Fast matching: static segments first, then regex validation
        foreach ($candidates as $route) {
            if (!$route->matchSegmentStatics($segments)) {
                continue;
            }

            try {
                $params = $route->matchParams($segments);
                if ($params !== null) {
                    return [
                        'found' => true,
                        'handler' => $route->handler,
                        'params' => $params,
                        'route' => $route
                    ];
                }
            } catch (\Exception $e) {
                throw new RuntimeException(
                    "Route matching failed for path '{$path}': {$e->getMessage()}", 
                    0, 
                    $e
                );
            }
        }

        return ['found' => false];
    }

    // ==========================================
    // DEBUGGING & INTROSPECTION
    // ==========================================

    /**
     * 📊 Gets all registered routes
     *
     * @return array<string, array<string, mixed>> All routes grouped by method
     */
    public function getRoutes(): array
    {
        $routes = [];
        
        // Static routes
        foreach ($this->static as $method => $paths) {
            foreach ($paths as $path => $handler) {
                $routes[$method][] = [
                    'path' => $path,
                    'handler' => $handler,
                    'type' => 'static',
                    'parameters' => []
                ];
            }
        }

        // Dynamic routes
        foreach ($this->dynamic as $method => $segmentGroups) {
            foreach ($segmentGroups as $segmentCount => $routeList) {
                foreach ($routeList as $route) {
                    $routes[$method][] = [
                        'path' => $route->path,
                        'handler' => $route->handler,
                        'type' => 'dynamic',
                        'parameters' => $route->getParameters(),
                        'segment_count' => $segmentCount
                    ];
                }
            }
        }

        return $routes;
    }

    /**
     * 🔍 Dumps all routes in a readable format
     *
     * @param bool $includeStats Whether to include performance statistics
     * @return string Human-readable route dump
     */
    public function dumpRoutes(bool $includeStats = true): string
    {
        $output = "🧱 Brick Framework - Route Dump\n";
        $output .= str_repeat('=', 50) . "\n\n";

        if ($includeStats) {
            $output .= $this->getStatsString() . "\n\n";
        }

        $routes = $this->getRoutes();
        
        foreach ($routes as $method => $methodRoutes) {
            $output .= "📋 {$method} Routes:\n";
            $output .= str_repeat('-', 30) . "\n";
            
            foreach ($methodRoutes as $route) {
                $type = $route['type'] === 'static' ? '⚡' : '🔄';
                $output .= "{$type} {$route['path']}";
                
                if (!empty($route['parameters'])) {
                    $params = implode(', ', array_keys($route['parameters']));
                    $output .= " [params: {$params}]";
                }
                
                $output .= "\n";
                $output .= "   → " . $this->formatHandler($route['handler']) . "\n";
            }
            $output .= "\n";
        }

        return $output;
    }

    /**
     * 📈 Gets router performance statistics
     *
     * @return array<string, mixed> Performance and usage statistics
     */
    public function getStats(): array
    {
        return [
            'total_routes' => $this->routeCount,
            'static_routes' => $this->stats['static'],
            'dynamic_routes' => $this->stats['dynamic'],
            'cached_compilations' => count($this->compiledCache),
            'cache_enabled' => $this->cacheCompiledRoutes,
            'memory_usage' => $this->getMemoryUsage()
        ];
    }

    // ==========================================
    // VALIDATION METHODS
    // ==========================================

    /**
     * Validates and normalizes HTTP method
     *
     * @param string $method HTTP method to validate
     * @return string Uppercase, validated method
     * @throws InvalidArgumentException For invalid methods
     */
    private function validateMethod(string $method): string
    {
        $method = strtoupper(trim($method));
        
        if (!in_array($method, self::VALID_METHODS, true)) {
            throw new InvalidArgumentException(
                "Invalid HTTP method: '{$method}'. Valid methods: " . implode(', ', self::VALID_METHODS)
            );
        }

        return $method;
    }

    /**
     * Validates route path format
     *
     * @param string $path Route path to validate
     * @throws InvalidArgumentException For invalid paths
     */
    private function validatePath(string $path): void
    {
        if (empty($path)) {
            throw new InvalidArgumentException('Route path cannot be empty');
        }

        // Check for malformed parameter syntax
        if (str_contains($path, '{') && !str_contains($path, '}')) {
            throw new InvalidArgumentException("Malformed route path: '{$path}' - unclosed parameter");
        }

        if (str_contains($path, '}') && !str_contains($path, '{')) {
            throw new InvalidArgumentException("Malformed route path: '{$path}' - unopened parameter");
        }
    }

    // ==========================================
    // UTILITY METHODS
    // ==========================================

    /**
     * Normalizes a path for consistent matching
     *
     * @param string $path Path to normalize
     * @return string Normalized path
     */
    private function normalize(string $path): string
    {
        if ($path === '') {
            $path = '/';
        }

        if ($this->decodeUrl) {
            $path = rawurldecode($path);
        }

        if ($this->ignoreTrailingSlash && $path !== '/') {
            $path = rtrim($path, '/');
            if ($path === '') {
                $path = '/';
            }
        }

        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        return $path;
    }

    /**
     * Splits path into segments
     *
     * @param string $path Normalized path
     * @return array<string> Path segments
     */
    private function getPathSegments(string $path): array
    {
        return $path === '/' ? [] : explode('/', trim($path, '/'));
    }

    /**
     * Generates cache key for compiled routes
     */
    private function getCacheKey(string $path, mixed $handler): string
    {
        // Use a hash for the handler instead of serializing it to avoid closure issues
        $handlerHash = is_callable($handler) ? 'callable_' . spl_object_hash((object)$handler) : (string)$handler;
        return md5($path . '|' . $handlerHash);
    }

    /**
     * Initializes statistics tracking
     */
    private function initializeStats(): void
    {
        $this->stats = [
            'static' => 0,
            'dynamic' => 0
        ];
    }

    /**
     * Formats handler for display
     */
    private function formatHandler(mixed $handler): string
    {
        return match (true) {
            is_string($handler) => $handler,
            is_array($handler) => implode('::', $handler),
            is_callable($handler) => 'Closure',
            is_object($handler) => get_class($handler),
            default => gettype($handler)
        };
    }

    /**
     * Gets formatted statistics string
     */
    private function getStatsString(): string
    {
        $stats = $this->getStats();
        $output = "📊 Router Statistics:\n";
        $output .= "• Total Routes: {$stats['total_routes']}\n";
        $output .= "• Static Routes: {$stats['static_routes']} (⚡ Fast)\n";
        $output .= "• Dynamic Routes: {$stats['dynamic_routes']} (🔄 Flexible)\n";
        $output .= "• Cached Compilations: {$stats['cached_compilations']}\n";
        $output .= "• Memory Usage: {$stats['memory_usage']}";
        
        return $output;
    }

    /**
     * Gets current memory usage
     */
    private function getMemoryUsage(): string
    {
        $bytes = memory_get_usage(true);
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}

/**
 * 👥 Router Group Helper Class
 * 
 * Provides convenient route grouping with shared prefixes
 */
final class RouterGroup
{
    public function __construct(
        private readonly Router $router,
        private readonly string $prefix
    ) {}

    /**
     * Delegates method calls to main router with prefix
     */
    public function __call(string $method, array $args): self
    {
        if (in_array(strtoupper($method), ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'])) {
            $path = $args[0] ?? '';
            $handler = $args[1] ?? null;
            $fullPath = rtrim($this->prefix, '/') . '/' . ltrim($path, '/');
            $this->router->$method($fullPath, $handler);
        } elseif ($method === 'add') {
            $httpMethod = $args[0] ?? '';
            $path = $args[1] ?? '';
            $handler = $args[2] ?? null;
            $fullPath = rtrim($this->prefix, '/') . '/' . ltrim($path, '/');
            $this->router->add($httpMethod, $fullPath, $handler);
        }
        
        return $this;
    }
}

/**
 * 🧱 Compiled Route Class
 * 
 * Represents a compiled route with optimized matching capabilities
 */
final class Route
{
    public readonly bool $isStatic;
    public readonly string $raw;
    public readonly int $segmentCount;

    /** @var list<array{static?: string, name?: string, regex?: string}> Route segments */
    private array $parts;

    /** @var array<string, string> Parameter definitions */
    private array $parameters = [];

    private function __construct(
        public readonly string $path,
        public readonly mixed $handler,
        bool $isStatic,
        array $parts
    ) {
        $this->raw = $path;
        $this->isStatic = $isStatic;
        $this->parts = $parts;
        $this->segmentCount = $path === '/' ? 0 : count(explode('/', trim($path, '/')));
        $this->extractParameters();
    }

    /**
     * 🔧 Compiles a route path into an optimized Route instance
     *
     * @param string $path Route path with optional parameters
     * @param mixed $handler Route handler
     * @return self Compiled route instance
     * @throws InvalidArgumentException For invalid route syntax
     */
    public static function compile(string $path, mixed $handler): self
    {
        if ($path === '/') {
            return new self('/', $handler, true, []);
        }

        $segments = explode('/', trim($path, '/'));
        $parts = [];
        $isStatic = true;

        foreach ($segments as $seg) {
            if ($seg === '') {
                continue;
            }
            
            if (str_starts_with($seg, '{') && str_ends_with($seg, '}')) {
                $isStatic = false;
                $inner = substr($seg, 1, -1);
                
                if (str_contains($inner, ':')) {
                    [$name, $regex] = explode(':', $inner, 2);
                    $name = trim($name);
                    $regex = trim($regex);
                    
                    // Validate parameter name
                    if (empty($name) || !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name)) {
                        throw new InvalidArgumentException("Invalid parameter name: '{$name}'");
                    }
                    
                    $parts[] = ['name' => $name, 'regex' => $regex];
                } else {
                    $name = trim($inner);
                    
                    if (empty($name) || !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name)) {
                        throw new InvalidArgumentException("Invalid parameter name: '{$name}'");
                    }
                    
                    $parts[] = ['name' => $name, 'regex' => '[^/]+'];
                }
            } else {
                $parts[] = ['static' => $seg];
            }
        }

        return new self($path, $handler, $isStatic, $parts);
    }

    /**
     * 🔍 Fast static segment matching
     *
     * @param array<string> $segments Path segments to match
     * @return bool Whether static segments match
     */
    public function matchSegmentStatics(array $segments): bool
    {
        if ($this->segmentCount !== count($segments)) {
            return false;
        }
        
        $i = 0;
        foreach ($this->parts as $part) {
            if (isset($part['static']) && $part['static'] !== $segments[$i]) {
                return false;
            }
            $i++;
        }
        
        return true;
    }

    /**
     * 🎯 Parameter extraction with regex validation
     *
     * @param array<string> $segments Path segments to extract parameters from
     * @return array<string,string>|null Extracted parameters or null on failure
     * @throws RuntimeException For regex compilation errors
     */
    public function matchParams(array $segments): ?array
    {
        $params = [];
        $i = 0;
        
        foreach ($this->parts as $part) {
            $value = $segments[$i++];
            
            // Skip static segments
            if (isset($part['static'])) {
                continue;
            }
            
            $regex = $part['regex'] ?? '[^/]+';
            $paramName = $part['name'];
            
            // Only validate with regex if it's not the default pattern
            if ($regex !== '[^/]+') {
                try {
                    $pattern = '/^' . str_replace('/', '\/', $regex) . '$/u';
                    if (!preg_match($pattern, $value)) {
                        return null;
                    }
                } catch (\Exception $e) {
                    throw new RuntimeException(
                        "Invalid regex pattern '{$regex}' for parameter '{$paramName}': {$e->getMessage()}",
                        0,
                        $e
                    );
                }
            }
            
            $params[$paramName] = $value;
        }
        
        return $params;
    }

    /**
     * 📋 Gets parameter definitions
     *
     * @return array<string, string> Parameter names mapped to their regex patterns
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * 🏷️ Gets parameter names only
     *
     * @return array<string> List of parameter names
     */
    public function getParameterNames(): array
    {
        return array_keys($this->parameters);
    }

    /**
     * ❓ Checks if route has parameters
     */
    public function hasParameters(): bool
    {
        return !empty($this->parameters);
    }

    /**
     * 🔍 Gets information about a specific parameter
     *
     * @param string $name Parameter name
     * @return array{name: string, regex: string}|null Parameter info or null
     */
    public function getParameterInfo(string $name): ?array
    {
        if (!isset($this->parameters[$name])) {
            return null;
        }

        return [
            'name' => $name,
            'regex' => $this->parameters[$name]
        ];
    }

    /**
     * Extracts parameter definitions from route parts
     */
    private function extractParameters(): void
    {
        foreach ($this->parts as $part) {
            if (isset($part['name'])) {
                $this->parameters[$part['name']] = $part['regex'] ?? '[^/]+';
            }
        }
    }
}

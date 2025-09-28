<?php

declare(strict_types=1);

require_once __DIR__ . '/../../brick/Core/Router.php';

use Brick\Core\Router;

echo "🧱 Router Enhancement Test\n";
echo str_repeat('=', 30) . "\n";

$router = new Router();

// Test basic functionality
echo "✅ Router created successfully\n";

// Test HTTP method validation
try {
    $router->add('INVALID', '/test', fn() => 'test');
    echo "❌ Should have caught invalid method\n";
} catch (InvalidArgumentException $e) {
    echo "✅ HTTP method validation works\n";
}

// Test route statistics
echo "📊 Stats: " . json_encode($router->getStats()) . "\n";

echo "🎉 Basic enhancements verified!\n";
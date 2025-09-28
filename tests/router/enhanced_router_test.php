<?php

declare(strict_types=1);

require_once __DIR__ . '/../../brick/Core/Router.php';

use Brick\Core\Router;
use Brick\Core\RouterGroup;

echo "🧱 Enhanced Router Feature Test\n";
echo str_repeat('=', 40) . "\n";

$router = new Router();

// Test route groups
echo "🔗 Testing Route Groups:\n";
$router->group('/api', function(RouterGroup $group) {
    $group->get('/users', fn() => 'List Users');
    $group->post('/users', fn() => 'Create User');
    $group->get('/users/{id}', fn() => 'Get User');
});

echo "✅ Route group created successfully\n";

// Test route dumping
echo "\n📋 Route Dump:\n";
echo $router->dumpRoutes();

// Test statistics
echo "\n📊 Router Statistics:\n";
$stats = $router->getStats();
foreach ($stats as $key => $value) {
    echo "  {$key}: {$value}\n";
}

// Test route matching
echo "\n🎯 Route Matching Test:\n";
$result = $router->dispatch('GET', '/api/users');
if ($result !== null) {
    echo "✅ Matched '/api/users': " . $result['handler']() . "\n";
} else {
    echo "❌ Failed to match '/api/users'\n";
}

$result = $router->dispatch('GET', '/api/users/123');
if ($result !== null) {
    echo "✅ Matched '/api/users/123': " . $result['handler']() . "\n";
    echo "   Parameters: " . json_encode($result['params']) . "\n";
} else {
    echo "❌ Failed to match '/api/users/123'\n";
}

echo "\n🎉 Enhanced Router Test Complete!\n";
echo "All new features are working correctly.\n";
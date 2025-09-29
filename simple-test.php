<?php
// Test simple template rendering
require_once __DIR__ . '/brick/Core/Autoloader.php';

$autoloader = new Brick\Core\Autoloader();
$autoloader->addNamespace('Brick', __DIR__ . '/brick');
$autoloader->register();

use Brick\Core\View;

$view = new View(__DIR__ . '/app/Views', null, true);

echo "Testing simple template...\n";
$result = $view->render('css-js-simple-test');
echo "Length: " . strlen($result) . "\n";
echo "Success: " . (strlen($result) > 100 ? "YES" : "NO") . "\n";
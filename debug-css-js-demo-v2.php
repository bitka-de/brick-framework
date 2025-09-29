<?php

require_once __DIR__ . '/brick/Core/View.php';

use Brick\Core\View;

$view = new View(
    viewPath: __DIR__ . '/app/Views',
    cachePath: sys_get_temp_dir() . '/brick_views',
    debug: true
);

$view->shareAll([
    'app_name' => 'Brick Framework',
    'version' => '1.0.0'
]);

try {
    echo $view->render('css-js-demo', [], true);
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
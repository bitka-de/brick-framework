<?php

/**
 * test_routes.php
 *
 * Test-Script für die neue Routing-Architektur
 */

// Simuliere Web-Request
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = $argv[1] ?? '/';
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['SERVER_PORT'] = '8000';

// Bootstrap laden
require_once __DIR__ . '/brick/Core/bootstrap.php';
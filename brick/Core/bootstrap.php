<?php

/**
 * bootstrap.php
 *
 * Bootstrap-Datei für die Initialisierung des Brick Frameworks.
 * 
 * @author  Jan P. Behrens <jp@bitka.de>
 * @version 1.0
 */
 
// Autoloader einbinden
require_once __DIR__ . '/Autoloader.php';

// Autoloader initialisieren
$autoloader = new Brick\Core\Autoloader();
$autoloader->addNamespace('App', __DIR__ . '/../app');  // App Namespace
$autoloader->addNamespace('Brick', __DIR__);            // Core Namespace
$autoloader->register();

// Weitere Initialisierungen
echo 'Hallo Brick Framework!';
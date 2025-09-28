<?php

namespace Brick\Core;

/**
 * Autoloader.php
 *
 * Ein minimalistischer PSR-4 Autoloader für Framework und App.
 * Registriert Namespaces und lädt Klassen bei Bedarf.
 *
 * @package Brick\Core
 *
 * @author  Jan P. Behrens <jp@bitka.de>
 * @version 1.0
 */

class Autoloader
{
  private array $prefixes = [];

  /**
   * Fügt einen Namespace-Präfix und das zugehörige Basisverzeichnis hinzu.
   *
   * @param string $prefix  Der Namespace-Präfix (z.B. "App\").
   * @param string $baseDir Das Basisverzeichnis für diesen Präfix.
   */
  public function addNamespace(string $prefix, string $baseDir): void
  {
    $prefix = trim($prefix, '\\') . '\\';
    $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';
    $this->prefixes[$prefix] = $baseDir;
  }

  /**
   * Registriert den Autoloader bei SPL.
   */
  public function register(): void
  {
    spl_autoload_register([$this, 'loadClass']);
  }

  /**
   * Lädt die Klasse basierend auf dem Namespace-Präfix.
   *
   * @param string $class Der vollqualifizierte Klassenname.
   */
  private function loadClass(string $class): void
  {
    foreach ($this->prefixes as $prefix => $baseDir) {
      if (str_starts_with($class, $prefix)) {
        $relative = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
          require $file;
          return;
        }
      }
    }
  }
}

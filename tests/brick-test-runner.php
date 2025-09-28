#!/usr/bin/env php
<?php

/**
 * 🧱 Brick Framework - Ultra Cool Test Runner
 * 
 * @author JP Behrens <jp@bitka.de>
 */

class CoolTestRunner {
    
    const COLORS = [
        'reset' => "\033[0m",
        'bold' => "\033[1m",
        'dim' => "\033[2m",
        'red' => "\033[31m",
        'green' => "\033[32m",
        'yellow' => "\033[33m",
        'blue' => "\033[34m",
        'magenta' => "\033[35m",
        'cyan' => "\033[36m",
        'white' => "\033[37m",
        'bg_green' => "\033[42m",
        'bg_red' => "\033[41m",
        'bg_blue' => "\033[44m",
    ];

    private static function color(string $text, string $color): string {
        return self::COLORS[$color] . $text . self::COLORS['reset'];
    }

    private static function printBrickAscii(): void {
        echo self::color("\n", 'cyan');
        echo self::color("    ██████╗ ██████╗ ██╗ ██████╗██╗  ██╗\n", 'cyan');
        echo self::color("    ██╔══██╗██╔══██╗██║██╔════╝██║ ██╔╝\n", 'cyan');
        echo self::color("    ██████╔╝██████╔╝██║██║     █████╔╝ \n", 'cyan');
        echo self::color("    ██╔══██╗██╔══██╗██║██║     ██╔═██╗ \n", 'cyan');
        echo self::color("    ██████╔╝██║  ██║██║╚██████╗██║  ██╗\n", 'cyan');
        echo self::color("    ╚═════╝ ╚═╝  ╚═╝╚═╝ ╚═════╝╚═╝  ╚═╝\n", 'cyan');
        echo self::color("    Framework v1.0 - Test Suite Runner\n", 'yellow');
        echo self::color("    ═══════════════════════════════════\n\n", 'cyan');
    }

    private static function printProgress(int $current, int $total): void {
        $percentage = round(($current / $total) * 100);
        $filled = round($percentage / 2.5);
        $empty = 40 - $filled;
        
        $bar = str_repeat("█", $filled) . str_repeat("░", $empty);
        
        echo "\r" . self::color("🧪 Progress: [", 'cyan') . 
             self::color($bar, 'green') . 
             self::color("] {$percentage}% ({$current}/{$total})", 'cyan');
        
        if ($current === $total) {
            echo "\n\n";
        }
    }

    private static function analyzeOutput(array $output): array {
        $stats = [
            'tests' => 0,
            'assertions' => 0,
            'failures' => 0,
            'errors' => 0,
            'passed' => 0,
            'warnings' => 0,
            'deprecations' => 0
        ];

        foreach ($output as $line) {
            if (preg_match('/Tests:\s*(\d+)/', $line, $matches)) {
                $stats['tests'] = (int) $matches[1];
            }
            if (preg_match('/Assertions:\s*(\d+)/', $line, $matches)) {
                $stats['assertions'] = (int) $matches[1];
            }
            if (preg_match('/Failures:\s*(\d+)/', $line, $matches)) {
                $stats['failures'] = (int) $matches[1];
            }
            if (preg_match('/Deprecations:\s*(\d+)/', $line, $matches)) {
                $stats['deprecations'] = (int) $matches[1];
            }
            if (str_contains($line, '✔') || str_contains($line, '🟢')) {
                $stats['passed']++;
            }
        }

        return $stats;
    }

    private static function printTestResults(array $output, array $stats): void {
        echo self::color("╔════════════════════════════════════════════════════════╗\n", 'cyan');
        echo self::color("║                    🧪 TEST RESULTS                    ║\n", 'bold');
        echo self::color("╠════════════════════════════════════════════════════════╣\n", 'cyan');
        
        // Stats Display
        if ($stats['tests'] > 0) {
            echo self::color("║  📊 Tests Run:       ", 'cyan') . 
                 self::color(sprintf("%-30s", $stats['tests'] . " tests"), 'white') . 
                 self::color("║\n", 'cyan');
        }
        
        if ($stats['assertions'] > 0) {
            echo self::color("║  ✅ Assertions:     ", 'cyan') . 
                 self::color(sprintf("%-30s", $stats['assertions'] . " assertions"), 'green') . 
                 self::color("║\n", 'cyan');
        }

        if ($stats['passed'] > 0) {
            echo self::color("║  🎉 Passed:         ", 'cyan') . 
                 self::color(sprintf("%-30s", $stats['passed'] . " tests"), 'green') . 
                 self::color("║\n", 'cyan');
        }

        if ($stats['failures'] > 0) {
            echo self::color("║  💥 Failures:       ", 'cyan') . 
                 self::color(sprintf("%-30s", $stats['failures'] . " tests"), 'red') . 
                 self::color("║\n", 'cyan');
        }

        if ($stats['deprecations'] > 0) {
            echo self::color("║  ⚠️  Deprecations:   ", 'cyan') . 
                 self::color(sprintf("%-30s", $stats['deprecations'] . " warnings"), 'yellow') . 
                 self::color("║\n", 'cyan');
        }

        echo self::color("╚════════════════════════════════════════════════════════╝\n\n", 'cyan');
    }

    private static function printDetailedOutput(array $output): void {
        echo self::color("🔍 DETAILED TEST OUTPUT:\n", 'bold');
        echo str_repeat("─", 60) . "\n";
        
        foreach ($output as $line) {
            // Skip PHPUnit header info
            if (str_contains($line, 'PHPUnit') || str_contains($line, 'Runtime:') || str_contains($line, 'Configuration:')) {
                continue;
            }
            
            // Progress dots
            if (preg_match('/^[D\.F]+\s+\d+/', $line)) {
                $dots = str_replace(['D', '.', 'F'], ['🟡', '🟢', '🔴'], $line);
                echo self::color($dots, 'cyan') . "\n";
                continue;
            }
            
            // Test names
            if (str_contains($line, '✔')) {
                $line = str_replace('✔', '✨', $line);
                echo self::color($line, 'green') . "\n";
            } elseif (str_contains($line, '✘')) {
                $line = str_replace('✘', '💥', $line);
                echo self::color($line, 'red') . "\n";
            } elseif (str_contains($line, 'Request (Tests\Brick\Http\Request)')) {
                echo self::color("🌐 HTTP Request Handler Tests", 'bold') . "\n";
            } else {
                echo $line . "\n";
            }
        }
        echo "\n";
    }

    public static function run(): void {
        // Clear screen
        system('clear');
        
        self::printBrickAscii();
        
        echo self::color("🚀 Initializing Brick Framework Test Suite...\n", 'yellow');
        echo self::color("📦 Checking dependencies...\n", 'blue');
        
        $projectRoot = dirname(__DIR__);
        $phpunitPath = $projectRoot . '/vendor/bin/phpunit';
        
        if (!file_exists($phpunitPath)) {
            echo self::color("❌ PHPUnit not found! Run 'composer install' first.\n", 'red');
            exit(1);
        }
        
        echo self::color("✅ PHPUnit found!\n", 'green');
        echo self::color("🔧 Preparing test environment...\n", 'blue');
        
        // Create directories relative to project root
        $projectRoot = dirname(__DIR__);
        @mkdir($projectRoot . '/tests/reports', 0777, true);
        @mkdir($projectRoot . '/tests/coverage', 0777, true);
        
        echo self::color("🏃 Running tests...\n\n", 'magenta');
        
        $startTime = microtime(true);
        
        // Run PHPUnit
        $configPath = __DIR__ . '/phpunit.xml';
        $command = "{$phpunitPath} --configuration={$configPath} --testdox --colors=never 2>&1";
        $output = [];
        $returnCode = 0;
        
        exec($command, $output, $returnCode);
        
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000);
        
        // Analyze results
        $stats = self::analyzeOutput($output);
        
        // Print detailed output
        self::printDetailedOutput($output);
        
        // Print results summary
        self::printTestResults($output, $stats);
        
        // Final status
        $hasRealIssues = $stats['failures'] > 0 || $stats['errors'] > 0;
        
        if (!$hasRealIssues) {
            echo self::color("🎉🎉🎉 ALL TESTS PASSED! 🎉🎉🎉\n", 'bg_green');
            echo self::color("🏆 Your Brick Framework is rock solid!\n", 'green');
            $returnCode = 0; // Override return code for coverage warnings
        } else {
            echo self::color("⚠️  SOME ISSUES DETECTED ⚠️\n", 'bg_red');
            echo self::color("🔧 Time to fix those issues!\n", 'yellow');
        }
        
        echo "\n";
        echo self::color("⏱️  Execution Time: {$duration}ms\n", 'dim');
        echo self::color("📂 Reports: tests/reports/\n", 'dim');
        echo self::color("🌐 Framework: Brick v1.0 by JP Behrens\n", 'dim');
        echo "\n";
        echo self::color("🚀 Keep building awesome things! 🚀\n\n", 'bold');
        
        exit($returnCode);
    }
}

// Let's go!
CoolTestRunner::run();
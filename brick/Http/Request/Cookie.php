<?php

namespace Brick\Http\Request;

/**
 * Cookie-Management für das Brick Framework
 *
 * @package Brick\Http\Request
 * @author Jan P. Behrens <jp@bitka.de>
 */
class Cookie
{
    private array $cookies;

    public function __construct()
    {
        $this->cookies = $_COOKIE;
    }

    public function get(string $name, mixed $default = null): mixed
    {
        return $this->cookies[$name] ?? $default;
    }

    public function has(string $name): bool
    {
        return isset($this->cookies[$name]);
    }

    public function all(): array
    {
        return $this->cookies;
    }

    public function set(
        string $name,
        string $value,
        int $expires = 0,
        string $path = '/',
        string $domain = '',
        ?bool $secure = null,
        bool $httpOnly = true,
        string $sameSite = 'Lax'
    ): static {
        // Auto-detect secure flag for HTTPS
        if ($secure === null) {
            $secure = $this->isSecureConnection();
        }

        $options = [
            'expires' => $expires,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httpOnly,
            'samesite' => $sameSite
        ];

        if (setcookie($name, $value, $options)) {
            $this->cookies[$name] = $value;
        }

        return $this;
    }

    public function delete(string $name, string $path = '/', string $domain = ''): static
    {
        $options = [
            'expires' => time() - 3600,
            'path' => $path,
            'domain' => $domain
        ];

        if (setcookie($name, '', $options)) {
            unset($this->cookies[$name]);
        }

        return $this;
    }

    public function forever(
        string $name,
        string $value,
        string $path = '/',
        string $domain = '',
        ?bool $secure = null,
        bool $httpOnly = true
    ): static {
        return $this->set(
            $name,
            $value,
            time() + (365 * 24 * 60 * 60), // 1 Jahr
            $path,
            $domain,
            $secure,
            $httpOnly
        );
    }

    private function isSecureConnection(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
               (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    }
}
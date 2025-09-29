<?php

namespace Brick\Http;

use Brick\Http\Request\Session;
use Brick\Http\Request\Cookie;
use Brick\Http\Request\HeaderBag;

/**
 * HTTP-Request-Handling-Klasse für das Brick Framework.
 *
 * Zentrale Klasse für HTTP-Request-Verarbeitung mit delegierter Verantwortung
 * für Sessions, Cookies und Headers an spezialisierte Klassen.
 *
 * @package Brick\Http
 * @version 1.0
 * @author  Jan P. Behrens <jp@bitka.de>
 */
class Request
{
    private readonly array $get;
    private readonly array $post;
    private readonly array $server;
    private readonly array $files;
    private readonly string $method;
    private readonly string $uri;
    
    private readonly Session $session;
    private readonly Cookie $cookie;
    private readonly HeaderBag $headers;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->method = strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
        $this->uri = $this->parseUri();
        
        // Delegierte Komponenten initialisieren
        $this->session = new Session();
        $this->cookie = new Cookie();
        $this->headers = new HeaderBag($this->server);
    }

    // ===== HTTP METHOD HELPERS =====
    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function isMethod(string $method): bool
    {
        return $this->method === strtoupper($method);
    }

    public function isPost(): bool { return $this->isMethod('POST'); }
    public function isGet(): bool { return $this->isMethod('GET'); }
    public function isPut(): bool { return $this->isMethod('PUT'); }
    public function isDelete(): bool { return $this->isMethod('DELETE'); }
    public function isPatch(): bool { return $this->isMethod('PATCH'); }

    // ===== INPUT HANDLING =====
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post);
    }

    public function only(string ...$keys): array
    {
        return array_intersect_key($this->all(), array_flip($keys));
    }

    public function has(string ...$keys): bool
    {
        return !array_diff($keys, array_keys($this->all()));
    }

    // ===== DELEGATED METHODS =====
    
    // Session-Delegation
    public function session(?string $key = null, mixed $default = null): mixed
    {
        return $key === null ? $this->session->all() : $this->session->get($key, $default);
    }

    public function setSession(string $key, mixed $value): static
    {
        $this->session->set($key, $value);
        return $this;
    }

    // Cookie-Delegation
    public function cookie(string $name, mixed $default = null): mixed
    {
        return $this->cookie->get($name, $default);
    }

    public function setCookie(string $name, string $value, int $expires = 0): static
    {
        $this->cookie->set($name, $value, $expires);
        return $this;
    }

    // Header-Delegation
    public function getHeader(string $name): ?string
    {
        return $this->headers->get($name);
    }

    public function bearerToken(): ?string
    {
        return $this->headers->getBearerToken();
    }

    // ===== DIRECT COMPONENT ACCESS =====
    public function getSession(): Session
    {
        return $this->session;
    }

    public function getCookie(): Cookie
    {
        return $this->cookie;
    }

    public function getHeaders(): HeaderBag
    {
        return $this->headers;
    }

    // ===== REQUEST INFO =====
    public function isSecure(): bool
    {
        return ($this->server['HTTPS'] ?? '') === 'on' ||
               ($this->server['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
    }

    public function isAjax(): bool
    {
        return $this->headers->isAjax();
    }

    public function wantsJson(): bool
    {
        return $this->headers->acceptsJson();
    }

    public function ip(): string
    {
        return $this->server['HTTP_X_FORWARDED_FOR'] ??
               $this->server['HTTP_X_REAL_IP'] ??
               $this->server['REMOTE_ADDR'] ??
               '0.0.0.0';
    }

    public function userAgent(): string
    {
        return $this->headers->getUserAgent() ?? '';
    }

    // ===== MAGIC METHODS =====
    public function __get(string $name): mixed
    {
        return match($name) {
            'method' => $this->method,
            'uri' => $this->uri,
            'ip' => $this->ip(),
            'userAgent' => $this->userAgent(),
            default => $this->input($name)
        };
    }

    public function __isset(string $name): bool
    {
        return in_array($name, ['method', 'uri', 'ip', 'userAgent']) ||
               isset($this->all()[$name]);
    }

    // ===== PRIVATE HELPERS =====
    private function parseUri(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        return parse_url($uri, PHP_URL_PATH) ?? '/';
    }
}

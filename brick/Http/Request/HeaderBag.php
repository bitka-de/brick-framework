<?php

namespace Brick\Http\Request;

/**
 * HTTP-Header-Management für das Brick Framework
 *
 * @package Brick\Http\Request
 * @author Jan P. Behrens <jp@bitka.de>
 */
class HeaderBag
{
    private array $headers;

    public function __construct(?array $server = null)
    {
        $this->headers = $this->parseHeaders($server ?? $_SERVER);
    }

    public function get(string $name, ?string $default = null): ?string
    {
        $name = $this->normalizeHeaderName($name);
        return $this->headers[$name] ?? $default;
    }

    public function has(string $name): bool
    {
        $name = $this->normalizeHeaderName($name);
        return isset($this->headers[$name]);
    }

    public function all(): array
    {
        return $this->headers;
    }

    public function getBearerToken(): ?string
    {
        $authorization = $this->get('Authorization');
        
        if ($authorization && str_starts_with($authorization, 'Bearer ')) {
            return substr($authorization, 7);
        }

        return null;
    }

    public function getContentType(): ?string
    {
        return $this->get('Content-Type');
    }

    public function getUserAgent(): ?string
    {
        return $this->get('User-Agent');
    }

    public function getAccept(): ?string
    {
        return $this->get('Accept');
    }

    public function acceptsJson(): bool
    {
        $accept = $this->getAccept();
        return $accept && str_contains($accept, 'application/json');
    }

    public function acceptsHtml(): bool
    {
        $accept = $this->getAccept();
        return $accept && str_contains($accept, 'text/html');
    }

    public function isAjax(): bool
    {
        return strtolower($this->get('X-Requested-With', '')) === 'xmlhttprequest';
    }

    private function parseHeaders(array $server): array
    {
        $headers = [];

        foreach ($server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headerName = substr($key, 5);
                $headerName = str_replace('_', '-', $headerName);
                $headerName = $this->normalizeHeaderName($headerName);
                $headers[$headerName] = $value;
            }
        }

        // Spezielle Headers die nicht HTTP_ Prefix haben
        $specialHeaders = [
            'CONTENT_TYPE' => 'Content-Type',
            'CONTENT_LENGTH' => 'Content-Length',
            'CONTENT_MD5' => 'Content-Md5',
        ];

        foreach ($specialHeaders as $serverKey => $headerName) {
            if (isset($server[$serverKey])) {
                $headers[$this->normalizeHeaderName($headerName)] = $server[$serverKey];
            }
        }

        return $headers;
    }

    private function normalizeHeaderName(string $name): string
    {
        return str_replace(' ', '-', ucwords(str_replace(['-', '_'], ' ', strtolower($name))));
    }
}
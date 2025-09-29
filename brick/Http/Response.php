<?php

declare(strict_types=1);

namespace Brick\Http;

/**
 * 🧱 Brick Framework - HTTP Response Handler
 *
 * Modern HTTP response management with security-first approach, 
 * fluent interface, and comprehensive content-type support.
 *
 * ✨ Features:
 * - Fluent interface for method chaining
 * - Built-in security headers
 * - CORS support for APIs
 * - Cache control management
 * - Multiple content types (JSON, HTML, XML, Text)
 * - Robust error handling
 * - Production-ready security features
 *
 * 📖 Usage:
 * ```php
 * $response = new Response();
 * $response->json(['message' => 'Hello World'])
 *          ->secureHeaders()
 *          ->send();
 * ```
 *
 * @package Brick\Http
 * @version 1.1
 * @author  JP Behrens <jp@bitka.de>
 * @since   1.0
 */
final class Response
{
    // ==========================================
    // HTTP STATUS CODE CONSTANTS
    // ==========================================
    
    /** @var int Success responses */
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_NO_CONTENT = 204;

    /** @var int Redirection responses */
    public const HTTP_MOVED_PERMANENTLY = 301;
    public const HTTP_FOUND = 302;
    public const HTTP_NOT_MODIFIED = 304;

    /** @var int Client error responses */
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_UNPROCESSABLE_ENTITY = 422;

    /** @var int Server error responses */
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_BAD_GATEWAY = 502;
    public const HTTP_SERVICE_UNAVAILABLE = 503;

    // ==========================================
    // PROPERTIES
    // ==========================================

    private int $status = self::HTTP_OK;
    private array $headers = [];
    private string|array|null $body = null;
    private bool $exitAfterRedirect = true;
    private bool $securityHeadersEnabled = true;

    // ==========================================
    // STATUS MANAGEMENT
    // ==========================================

    /**
     * Sets the HTTP status code
     *
     * @param int $code HTTP status code
     * @return self For method chaining
     */
    public function status(int $code): self
    {
        $this->status = $code;
        return $this;
    }

    /**
     * Gets the current HTTP status code
     *
     * @return int Current status code
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    // ==========================================
    // HEADER MANAGEMENT
    // ==========================================

    /**
     * Adds or overwrites a single header
     *
     * @param string $name Header name
     * @param string $value Header value
     * @return self For method chaining
     */
    public function header(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Adds multiple headers at once
     *
     * @param array<string, string> $headers Associative array of headers
     * @return self For method chaining
     */
    public function headers(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->header($name, $value);
        }
        return $this;
    }

    /**
     * Gets all headers
     *
     * @return array<string, string> All set headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    // ==========================================
    // BODY MANAGEMENT
    // ==========================================

    /**
     * Sets the response body
     *
     * @param string|array $content Response content
     * @return self For method chaining
     */
    public function body(string|array $content): self
    {
        $this->body = $content;
        return $this;
    }

    /**
     * Gets the current response body
     *
     * @return string|array|null Current body content
     */
    public function getBody(): string|array|null
    {
        return $this->body;
    }

    // ==========================================
    // CONTENT TYPE RESPONSES
    // ==========================================

    /**
     * 📝 JSON response with robust error handling
     *
     * @param array $data Response data
     * @param int $status HTTP status code
     * @param bool $sendNow Whether to send immediately
     * @return self For method chaining
     */
    public function json(array $data, int $status = self::HTTP_OK, bool $sendNow = false): self
    {
        $this->status($status)
             ->header('Content-Type', 'application/json; charset=utf-8')
             ->body($data);

        if ($sendNow) {
            $this->send();
        }
        return $this;
    }

    /**
     * 📄 Plain text response
     *
     * @param string $text Plain text content
     * @param int $status HTTP status code
     * @param bool $sendNow Whether to send immediately
     * @return self For method chaining
     */
    public function text(string $text, int $status = self::HTTP_OK, bool $sendNow = false): self
    {
        $this->status($status)
             ->header('Content-Type', 'text/plain; charset=utf-8')
             ->body($text);

        if ($sendNow) {
            $this->send();
        }
        return $this;
    }

    /**
     * 🌐 HTML response
     *
     * @param string $html HTML content
     * @param int $status HTTP status code
     * @param bool $sendNow Whether to send immediately
     * @return self For method chaining
     */
    public function html(string $html, int $status = self::HTTP_OK, bool $sendNow = false): self
    {
        $this->status($status)
             ->header('Content-Type', 'text/html; charset=utf-8')
             ->body($html);

        if ($sendNow) {
            $this->send();
        }
        return $this;
    }

    /**
     * 📋 XML response
     *
     * @param string $xml XML content
     * @param int $status HTTP status code
     * @param bool $sendNow Whether to send immediately
     * @return self For method chaining
     */
    public function xml(string $xml, int $status = self::HTTP_OK, bool $sendNow = false): self
    {
        $this->status($status)
             ->header('Content-Type', 'application/xml; charset=utf-8')
             ->body($xml);

        if ($sendNow) {
            $this->send();
        }
        return $this;
    }

    // ==========================================
    // REDIRECT HANDLING
    // ==========================================

    /**
     * 🔄 HTTP redirect with URL validation
     *
     * @param string $url Target URL or relative path
     * @param int $status Redirect status code (301, 302, etc.)
     * @param bool|null $exit Whether to exit after redirect
     * @throws \InvalidArgumentException For invalid URLs
     */
    public function redirect(string $url, int $status = self::HTTP_FOUND, ?bool $exit = null): void
    {
        // Validate URL format
        if (!filter_var($url, FILTER_VALIDATE_URL) && !str_starts_with($url, '/')) {
            throw new \InvalidArgumentException("Invalid redirect URL: {$url}");
        }

        $this->status($status)
             ->header('Location', $url)
             ->send();

        if ($exit ?? $this->exitAfterRedirect) {
            exit;
        }
    }

    // ==========================================
    // SUCCESS & ERROR RESPONSES
    // ==========================================

    /**
     * ✅ Success response with optional data
     *
     * @param string $message Success message
     * @param array $data Additional response data
     * @param int $status HTTP status code
     * @param bool $sendNow Whether to send immediately
     * @return self For method chaining
     */
    public function success(
        string $message = 'Success',
        array $data = [],
        int $status = self::HTTP_OK,
        bool $sendNow = false
    ): self {
        $response = [
            'success' => true,
            'message' => $message
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return $this->json($response, $status, $sendNow);
    }

    /**
     * ❌ Error response with details
     *
     * @param string $message Error message
     * @param int $status HTTP status code
     * @param array $errors Detailed error information
     * @param bool $sendNow Whether to send immediately
     * @return self For method chaining
     */
    public function error(
        string $message = 'Internal Server Error',
        int $status = self::HTTP_INTERNAL_SERVER_ERROR,
        array $errors = [],
        bool $sendNow = false
    ): self {
        $response = [
            'success' => false,
            'message' => $message,
            'status' => $status
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return $this->json($response, $status, $sendNow);
    }

    // ==========================================
    // SPECIALIZED ERROR RESPONSES
    // ==========================================

    /**
     * 🚫 Validation error response (422)
     */
    public function validationError(array $errors, string $message = 'Validation failed', bool $sendNow = false): self
    {
        return $this->error($message, self::HTTP_UNPROCESSABLE_ENTITY, $errors, $sendNow);
    }

    /**
     * 🔍 Not found response (404)
     */
    public function notFound(string $message = 'Resource not found', bool $sendNow = false): self
    {
        return $this->error($message, self::HTTP_NOT_FOUND, [], $sendNow);
    }

    /**
     * 🔐 Unauthorized response (401)
     */
    public function unauthorized(string $message = 'Authentication required', bool $sendNow = false): self
    {
        return $this->error($message, self::HTTP_UNAUTHORIZED, [], $sendNow);
    }

    /**
     * 🛡️ Forbidden response (403)
     */
    public function forbidden(string $message = 'Access denied', bool $sendNow = false): self
    {
        return $this->error($message, self::HTTP_FORBIDDEN, [], $sendNow);
    }

    // ==========================================
    // SECURITY FEATURES
    // ==========================================

    /**
     * 🛡️ Applies comprehensive security headers
     *
     * Protects against XSS, clickjacking, MIME sniffing, etc.
     *
     * @return self For method chaining
     */
    public function secureHeaders(): self
    {
        return $this->headers([
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains'
        ]);
    }

    /**
     * 🌐 CORS headers for cross-origin requests
     *
     * @param string $origin Allowed origin (default: *)
     * @param array<string> $methods Allowed HTTP methods
     * @param array<string> $headers Allowed headers
     * @return self For method chaining
     */
    public function cors(
        string $origin = '*',
        array $methods = ['GET', 'POST', 'PUT', 'DELETE'],
        array $headers = ['Content-Type', 'Authorization']
    ): self {
        return $this->headers([
            'Access-Control-Allow-Origin' => $origin,
            'Access-Control-Allow-Methods' => implode(', ', $methods),
            'Access-Control-Allow-Headers' => implode(', ', $headers),
            'Access-Control-Max-Age' => '86400'
        ]);
    }

    // ==========================================
    // CACHE CONTROL
    // ==========================================

    /**
     * 💾 Cache control headers
     *
     * @param int $maxAge Cache max age in seconds
     * @param bool $public Whether cache is public or private
     * @return self For method chaining
     */
    public function cache(int $maxAge = 3600, bool $public = true): self
    {
        $cacheControl = ($public ? 'public' : 'private') . ", max-age={$maxAge}";
        $expires = gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT';

        return $this->headers([
            'Cache-Control' => $cacheControl,
            'Expires' => $expires
        ]);
    }

    /**
     * 🚫 Disable caching completely
     *
     * @return self For method chaining
     */
    public function noCache(): self
    {
        return $this->headers([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    // ==========================================
    // CONFIGURATION
    // ==========================================

    /**
     * ⚙️ Controls exit behavior after redirects
     */
    public function setExitAfterRedirect(bool $exit): self
    {
        $this->exitAfterRedirect = $exit;
        return $this;
    }

    /**
     * ⚙️ Enables/disables automatic security headers
     */
    public function setSecurityHeaders(bool $enabled): self
    {
        $this->securityHeadersEnabled = $enabled;
        return $this;
    }

    // ==========================================
    // RESPONSE SENDING
    // ==========================================

    /**
     * 📤 Sends the HTTP response
     *
     * Handles status codes, headers, content-length, and body output
     * with comprehensive error checking.
     *
     * @throws \RuntimeException When headers are already sent
     */
    public function send(): void
    {
        // Prevent double-sending
        if (headers_sent($file, $line)) {
            throw new \RuntimeException("Headers already sent in {$file} on line {$line}");
        }

        // Set HTTP status code
        http_response_code($this->status);

        // Auto-apply security headers if enabled
        if ($this->securityHeadersEnabled && !isset($this->headers['X-Content-Type-Options'])) {
            $this->secureHeaders();
        }

        // Prepare content
        $content = $this->prepareContent();

        // Auto-set Content-Length header
        if ($content !== '' && !isset($this->headers['Content-Length'])) {
            $this->header('Content-Length', (string) strlen($content));
        }

        // Send all headers
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        // Output content
        echo $content;
    }

    // ==========================================
    // UTILITY METHODS
    // ==========================================

    /**
     * ❓ Checks if response has been sent
     */
    public function isSent(): bool
    {
        return headers_sent();
    }

    /**
     * 🔄 Resets response to initial state
     */
    public function reset(): self
    {
        $this->status = self::HTTP_OK;
        $this->headers = [];
        $this->body = null;
        return $this;
    }

    // ==========================================
    // PRIVATE HELPERS
    // ==========================================

    /**
     * Prepares content for output with robust JSON encoding
     */
    private function prepareContent(): string
    {
        return match (true) {
            is_array($this->body) => $this->encodeJson($this->body),
            is_string($this->body) => $this->body,
            default => ''
        };
    }

    /**
     * Robust JSON encoding with comprehensive error handling
     *
     * @param array $data Data to encode
     * @return string JSON string
     * @throws \RuntimeException When JSON encoding fails
     */
    private function encodeJson(array $data): string
    {
        $json = json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION
        );

        if ($json === false) {
            throw new \RuntimeException('JSON encoding failed: ' . json_last_error_msg());
        }

        return $json;
    }
}

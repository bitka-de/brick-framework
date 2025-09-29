<?php

namespace Brick\Http\Request;

/**
 * Session-Management für das Brick Framework
 *
 * @package Brick\Http\Request
 * @author Jan P. Behrens <jp@bitka.de>
 */
class Session
{
    private bool $started = false;

    public function __construct(bool $autoStart = true)
    {
        if ($autoStart) {
            $this->start();
        }
    }

    public function start(): bool
    {
        if ($this->started || session_status() === PHP_SESSION_ACTIVE) {
            return true;
        }

        $this->started = session_start();
        return $this->started;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): static
    {
        $this->ensureStarted();
        $_SESSION[$key] = $value;
        return $this;
    }

    public function remove(string $key): static
    {
        $this->ensureStarted();
        unset($_SESSION[$key]);
        return $this;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function all(): array
    {
        return $_SESSION ?? [];
    }

    public function clear(): static
    {
        $this->ensureStarted();
        session_unset();
        return $this;
    }

    public function destroy(): bool
    {
        $this->ensureStarted();
        $result = session_destroy();
        $this->started = false;
        return $result;
    }

    public function regenerate(bool $deleteOldSession = true): static
    {
        $this->ensureStarted();
        session_regenerate_id($deleteOldSession);
        return $this;
    }

    public function getId(): string
    {
        return session_id();
    }

    public function setId(string $id): static
    {
        if ($this->started) {
            throw new \RuntimeException('Cannot change session ID after session has started');
        }
        session_id($id);
        return $this;
    }

    public function isStarted(): bool
    {
        return $this->started && session_status() === PHP_SESSION_ACTIVE;
    }

    private function ensureStarted(): void
    {
        if (!$this->isStarted()) {
            $this->start();
        }
    }
}
<?php

namespace App\Framework\Http;


class Request
{
    public function __construct(
        public readonly array $getParams,
        public readonly array $postParams,
        public readonly array $cookies,
        public readonly array $files,
        public readonly array $serverParams,
    ) {
    }

    public static function createFromGlobals(): static
    {
        return new static(
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES,
            $_SERVER
        );
    }

    public function getPathInfo(): string
    {
        return strtok($this->serverParams['REQUEST_URI'], '?');
    }

    public function getMethod(): string
    {
        return $this->serverParams['REQUEST_METHOD'];
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'POST';
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->sanitize($this->postParams[$key] ?? $this->getParams[$key] ?? $default);
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->sanitize($this->postParams[$key] ?? $this->getParams[$key] ?? $default);
    }

    public function has(string $key): bool
    {
        return isset($this->postParams[$key]) || isset($this->getParams[$key]);
    }

    public function all(): array
    {
        $sanitizedInputs = array_map([$this, 'sanitize'], array_merge($this->getParams, $this->postParams));
        return $sanitizedInputs;
    }

    public function only(array $keys): array
    {
        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->get($key);
        }
        return $values;
    }

    public function file(string $key): array | null
    {
        return $this->files[$key] ?? null;
    }

    // Example of a basic sanitization function
    protected function sanitize(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map([$this, 'sanitize'], $value);
        } else {
            // Perform sanitization here as per your requirements
            return htmlspecialchars($value);
        }
    }

   
}

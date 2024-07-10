<?php


namespace App\Http;

class Request
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->data;
    }

    public function input(): array
    {
        return $_REQUEST;
    }

    public function only(array $keys): array
    {
        return array_intersect_key($this->data, array_flip($keys));
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function onlyFiles(): array
    {
        return $_FILES;
    }
}

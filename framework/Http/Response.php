<?php

namespace App\Framework\Http;

class Response
{
    protected string $content;
    protected int $statusCode;
    protected array $headers = [];
    protected array $withData = [];

    public function __construct(string $content, int $statusCode = 200)
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
    }

    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->content;
    }


    public function with(string $key, $value): self
    {
        $this->withData[$key] = $value;
        return $this;
    }
}

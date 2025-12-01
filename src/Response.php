<?php

namespace PHPTLSClient;

class Response
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the response body as text
     *
     * @return string
     */
    public function text(): string
    {
        return $this->data['body'];
    }

    /**
     * Get the response body as JSON
     *
     * @return mixed
     */
    public function json(): mixed
    {
        return json_decode($this->data['body'], true);
    }

    /**
     * Get the response status code
     *
     * @return int
     */
    public function status(): int
    {
        return $this->data['status'];
    }

    /**
     * Get the response headers
     *
     * @return array
     */
    public function headers(): array
    {
        return $this->data['headers'];
    }

    /**
     * Get the response cookies
     *
     * @return array
     */
    public function cookies(): array
    {
        return $this->data['cookies'];
    }

    /**
     * Get the target URL
     *
     * @return string
     */
    public function url(): string
    {
        return $this->data['target'];
    }

    /**
     * Check if the request was successful (status code 200-299)
     *
     * @return bool
     */
    public function ok(): bool
    {
        return $this->data['status'] >= 200 && $this->data['status'] < 300;
    }

    /**
     * Get the used protocol
     *
     * @return string
     */
    public function usedProtocol(): string
    {
        return $this->data['usedProtocol'];
    }

    /**
     * Get the session ID
     *
     * @return string
     */
    public function sessionId(): string
    {
        return $this->data['sessionId'];
    }

    /**
     * Get the response ID
     *
     * @return string
     */
    public function id(): string
    {
        return $this->data['id'];
    }
}
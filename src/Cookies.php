<?php

namespace PHPTLSClient;

class Cookies
{
    private array $cookies = [];

    public function __construct()
    {
        // Initialize with empty cookies
    }

    public function fetchAllCookies(): array
    {
        return $this->cookies;
    }

    public function mergeCookies(array $cookies, string $url): array
    {
        // Merge provided cookies with stored cookies
        $mergedCookies = array_merge($this->cookies, $cookies);
        
        $result = [];
        foreach ($mergedCookies as $name => $value) {
            $result[] = ['name' => $name, 'value' => $value];
        }
        
        return $result;
    }

    public function syncCookies(array $cookies, string $url): array
    {
        // Sync received cookies with stored cookies
        foreach ($cookies as $name => $value) {
            $this->cookies[$name] = $value;
        }
        
        return $this->cookies;
    }
}
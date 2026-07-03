<?php

namespace App;

class Request
{
    public function isAjax(): bool
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }

    public function getUri(array $params = []): string
    {
        $get = array_replace($_GET, $params);

        return parse_url($_SERVER['REQUEST_URI'])['path'] . '?' . http_build_query($get);
    }

    public function getQueryInt(string $key, ?int $default = null): ?int
    {
        if (isset($_GET[$key]) && $_GET[$key] !== '') {
            return (int) $_GET[$key];
        }

        if ($default !== null) {
            return $default;
        }

        return null;
    }

    public function getQueryString(string $key, ?string $default = null): ?string
    {
        if (isset($_GET[$key]) && $_GET[$key] !== '') {
            return $_GET[$key];
        }

        if ($default !== null) {
            return $default;
        }

        return null;
    }
}
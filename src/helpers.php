<?php

if (!function_exists('env')) {
    function env(string $key, $default = null) {
        $value = getenv($key);

        if ($value === false) {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? false;
        }

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (preg_match('/\A([\'"])(.*)\1\z/', $value, $matches)) {
            return $matches[2];
        }

        return $value;
    }
}

if (!function_exists('config')) {
    function config(string $key, $default = null) {
        static $loadedConfigs = [];

        $segments = explode('.', $key);
        $file = array_shift($segments);

        $configPath = dirname(__DIR__) . "/config/{$file}.php";

        if (!isset($loadedConfigs[$file])) {
            if (file_exists($configPath)) {
                $loadedConfigs[$file] = require $configPath;
            } else {
                $loadedConfigs[$file] = [];
            }
        }

        $config = $loadedConfigs[$file];

        foreach ($segments as $segment) {
            if (is_array($config) && array_key_exists($segment, $config)) {
                $config = $config[$segment];
            } else {
                return $default;
            }
        }

        return $config;
    }
}

if (!function_exists('getCommitId')) {
    function getCommitId() {
        if (!function_exists('shell_exec')) {
            return '';
        }
        return trim((string) shell_exec('git rev-parse --short HEAD 2>/dev/null'));
    }
}

if (!function_exists('app_log')) {
    function app_log($message, $type) {
        return null;
    }
}
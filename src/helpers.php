<?php

use Ramsey\Uuid\Uuid;


if (!function_exists('dotenv')) {
    function dotenv(string $key, $default = null) {
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

/* auth() currently not functional */
// function auth(): bool {
//     // if (isset($_SESSION['user_id']) && $user)
//     return isset($user);
// }

/* Example 

(
?arction=test,
[
    [
        'type' => 'text',
        'name' => 'username',
        'placeholder' => 'Cool Name',
        'required' => null,
        'value' => 'user99',
        'class' => 'cool-input',
     ],
    [
        'type' => 'submit',
        'value' => 'SuBmIt',
    ],
],
'testForm',
'put',
)

*/
function echoForm(string $action, array $inputs, ?string $id = null, string $method = 'post', $attriubutes = [['t', 'o',], ['d', 'o',],]) {
    // maybe automatic $action-generation using the new function ...

    $form = '';

    $id_attribute = $id !== null ? sprintf(" id=\"{$id}\"") : '';

    $form = sprintf('<form action="%s" method="%s"%s>', $action, $method, $id_attribute); // dubble-quotes would have been too painful
    
    foreach($inputs as $input) {
        $form .= '<input';

        // if(has('class', $input)) {
        //     $form .= " class=\"{$\"";
        // }

        foreach($input as $attribute => $value) {
            $form .= " {$attribute}=\"{$value}\"";
        }
        $form .= '>';
    }
    $form .= '</form>';

    echo $form;
}

function createUuid() {
    return (string) Uuid::uuid4();
}

function create_url_with_attributes(array $attributes, ?string $uri = '') {

    if (empty($attributes)) {
        return (string) $uri;
    }

    $attributes_string = http_build_query($attributes);
    $separator = str_contains((string) $uri, '?') ? '&' : '?';

    return $uri . $separator . $attributes_string;
}


function url_(array $attributes, ?string $uri = '') {
    return create_url_with_attributes($attributes, $uri);
}

function to_relative_time(DateTimeInterface|int|string $datetime, ?DateTimeInterface $now = null): string {
    $timezone = new DateTimeZone('Europe/Berlin');

    if ($datetime instanceof DateTimeInterface) {
        $target = (clone $datetime)->setTimezone($timezone);
    } elseif (is_numeric($datetime)) {
        $target = (new DateTimeImmutable('now', $timezone))->setTimestamp((int)$datetime);
    } else {
        try {
            $target = new DateTimeImmutable($datetime, $timezone);
        } catch (Exception $e) {
            throw new InvalidArgumentException("Invalid datetime string provided: {$datetime}", 0, $e);
        }
    }

    $reference = $now ? (clone $now)->setTimezone($timezone) : new DateTimeImmutable('now', $timezone);

    $secondsDelta = $target->getTimestamp() - $reference->getTimestamp();
    $absSeconds = abs($secondsDelta);

    if ($absSeconds < 5) {
        return 'just now';
    }

    $isPast = $secondsDelta < 0;
    $diff = $reference->diff($target);

    $totalDays = $diff->days;
    
    if ($diff->y > 0) {
        $value = $diff->y;
        $unit = 'year';
    } elseif ($diff->m > 0) {
        $value = $diff->m;
        $unit = 'month';
    } elseif ($totalDays >= 7) {
        $value = (int) floor($totalDays / 7);
        $unit = 'week';
    } elseif ($diff->d > 0) {
        $value = $diff->d;
        $unit = 'day';
    } elseif ($diff->h > 0) {
        $value = $diff->h;
        $unit = 'hour';
    } elseif ($diff->i > 0) {
        $value = $diff->i;
        $unit = 'minute';
    } else {
        $value = $diff->s;
        $unit = 'second';
    }

    $label = $value . ' ' . $unit . ($value > 1 ? 's' : '');
    
    return $isPast ? "{$label} ago" : "in {$label}";
}
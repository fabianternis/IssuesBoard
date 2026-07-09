<?php

function config($query) {
    return null;
}

$action = $_GET['action'] ?? null;

if (isset($action)) {
    switch ($action) {
        case 'login':
            echo 'WIP';
            break;
        case 'signup':
            if (isset($_POST['submit'])) {

            }
            break;
        case 'logout':
            break;
    }
}

// $content = include __DIR__ . '/src/views/index.php';


include __DIR__ . '/src/views/layout/head.php';
include __DIR__ . '/src/views/index.php';
<?php

include __DIR__ . '/src/classes/controllers/signupController.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/helpers.php';

function getCommitId() {
    return trim((string) shell_exec('git rev-parse --short HEAD'));
}

$action = $_GET['action'] ?? null;

if (isset($action)) {
    switch ($action) {
        case 'login':
            echo 'WIP';
            break;
        case 'signup':
            if (isset($_POST['submit'])) {
                /*
                $data['email'] = $_POST['email'];
                $data['username'] = $_POST['username'];
                $data['password'] = $_POST['password'];
                $data['password_confirmation'] = $_POST['password_confirmation'];

                $signup = new SignupController($data);
                */

                $email = $_POST['email'] ?? '';
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                $password_confirmation = $_POST['password_confirmation'] ?? '';

                $signup = new SignupController($email, $username, $password, $password_confirmation);
            }
            break;
        case 'logout':
            break;
    }
}

// $content = include __DIR__ . '/src/views/index.php';


include __DIR__ . '/src/views/layout/head.php';
include __DIR__ . '/src/views/index.php';




$commitHash = getCommitId();

if ($commitHash) {
    $safeCommitHash = htmlspecialchars((string)$commitHash, ENT_QUOTES, 'UTF-8');
    
    echo '<span class="commit-id"><a href="https://github.com/fabianternis/IssuesBoard/commit/' . $safeCommitHash . '">' . $safeCommitHash . '</a></span>';
} else {
    echo '<span class="commit-id error"> Deployment Commit: UNKNOWN (Git execution failed) </span>';
}
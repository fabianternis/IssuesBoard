<?php

session_start();

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/helpers.php';
require __DIR__ . '/src/database.php';
//require __DIR__ . '/src/classes/Database.php';

//include __DIR__ . '/src/classes/controllers/signupController.php';

require __DIR__ . '/src/classes/controllers/AuthController.php'; // removed after rebuild of autoload
use Controllers\AuthController;



function getCommitId() {
    return trim((string) shell_exec('git rev-parse --short HEAD'));
}

include __DIR__ . '/src/classes/models/User.php';



$action = $_GET['action'] ?? null;

if (isset($action)) {
    switch ($action) {
        case 'login':
            echo 'WIP';
            break;
        case 'signup':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

                $auth = new AuthController();
                $auth->signup($email, $username, $password, $password_confirmation);
            }
            break;
        case 'logout':
            break;
    }
}



//$user = User::



// $content = include __DIR__ . '/src/views/index.php';


include __DIR__ . '/src/views/layout/head.php';
echo '<body>';
include __DIR__ . '/src/views/index.php';
echo '</body>';
include __DIR__ . '/src/views/layout/foot.php';
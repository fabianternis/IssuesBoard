<?php

session_start();

require __DIR__ . '/vendor/autoload.php';


require_once __DIR__ . '/src/helpers.php';
require_once __DIR__ . '/src/database.php';

use Models\User;
use Models\Project;

if(isset($_SESSION['user_id'])) {
$user = User::where('id', $_SESSION['user_id'])->get();
}

//require __DIR__ . '/src/classes/Database.php';

//include __DIR__ . '/src/classes/controllers/signupController.php';

require __DIR__ . '/src/classes/controllers/AuthController.php'; // removed after rebuild of autoload
use Controllers\AuthController;


function getCommitId() {
    return trim((string) shell_exec('git rev-parse --short HEAD'));
}


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$action = $_GET['action'] ?? null;
$object = $_GET['object'] ?? null;
$auth = new AuthController();
$view_name = null;
$http_code = 404;
$http_code_force = false;
$error_message = null;
$target_uri = $uri;
function Auth() {
    global $auth;
    return $auth;
}


if (isset($action)) {
    if (isset($object)){
        $dead = false;
        // switch($object) {
        //     case 'project';
    
        //     $target_uri = '/post?pid='.$project->id;
        // }        

        $className = '\\Controllers\\' . ucfirst(strtolower($object)) . 'Controller';

        if(!class_exists($className)) {
            $http_code = 404;
            $error_message = "Object '{$object}' could not be found.";
        } else {
            $controller = new $className();
            if (!method_exists($controller, $action) || !is_callable([$controller, $action])) {
                $http_code = 405;
                $error_message = "Action '{$action}' is invalid for object '{$object}'.";
            } elseif (in_array($action, ['update', 'delete', 'store']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
                $http_code = 405;
                $error_message = 'This action requires POST.';
            } elseif (in_array($action, ['show', 'edit', 'update', 'delete']) && !isset($_GET['id'])) {
                $http_code = 404;
                $error_message = "There is no specific item of object '{$object}' defined.";
            } else {
                if(isset($_GET['id'])) {
                    $controller->$action(id: $_GET['id']);
                } else {
                    $controller->$action();
                }
                //exit; THIS F***ING LINE OF CODE COST ME about 1h of DEBUGGING
            }
        }
    } else {
        switch ($action) {
            case 'login':
                $identifier = $_POST['identifier'] ?? null;
                $password = $_POST['password'] ?? null;
                $auth->login($identifier, $password);
                $target_uri = '/dashboard';
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
                    $auth->signup($email, $username, $password, $password_confirmation);
                $target_uri = '/dashboard';
                }
                break;
            case 'logout':
                $auth->logout();
                $target_uri = '/';
                break;
        }
    }
}


// if(!isset($error_message) && !isset($view_name)) {
if(!isset($error_message)) {
    $http_code = 200;
// }
if(!isset($view_name)) {
switch ($uri) {
    case '/':
        $view_name = 'home';
        // $http_code = 200;
        break;
    case '/dashboard':
        if ($auth->check()) {
            $view_name = 'dashboard';
            // $http_code = 200;
        } else {
            // $http_code = 403;
            $http_code = 401;
        }
        break;
    case '/auth':
        if (!$auth->check()) {
            $view_name = 'auth';
            // $http_code = 200;
        } else {
            $target_uri = '/';
            $http_code = 302;
        }
        break;
    default:
        $http_code = 404;
        // $view_name = 'error';
}
}
}
if (isset($_GET['pid']) && $auth->check()) {
    $project = Project::where('id', $_GET['pid'])->first();
}

if(!isset($view_name)) {
    $view_name = 'error';
}

// http_response_code($http_code);
// if(!($uri == '/' && $target_uri == '/')) {
//     header('Location: '.$target_uri);
// }
if ($target_uri !== $uri) {
    $redirect_code = ($http_code_force || isset($error_message)) ? $http_code : (($http_code === 404) ? 302 : $http_code);
    // http_response_code($redirect_code);
    $http_code = $redirect_code;
    header('Location: ' . $target_uri);
    exit;
}
http_response_code($http_code);
if ($http_code == 404) {
    $view_name = 'error';
}


// $content = include __DIR__ . '/src/views/index.php';
if ((isset($_GET['debug']) && $_GET['debug'] === 'hard') || (isset($_GET['debug_view']) && $_GET['debug_view'] == 1)) {
    die($view_name);
}
include __DIR__ . '/src/views/layout/head.php';
echo "<body class=\"{$view_name}-page\">";
include __DIR__ . '/src/views/layout/navbar.php';
echo '<main>';
include __DIR__ . "/src/views/{$view_name}.php";
echo '</main>';
echo '</body>';
include __DIR__ . '/src/views/layout/foot.php';




echo "<!--";


echo(json_encode($user));
echo "<hr>";
echo "<hr>";
echo($auth->check());
echo "<hr>";
echo(isset($user));
?>




<?php if($auth->check()): ?>
<a href="?action=logout">Log out</a>
<?php endif ?>
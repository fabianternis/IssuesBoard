<?php

session_start();

require __DIR__ . '/vendor/autoload.php';


require_once __DIR__ . '/src/helpers.php';
require_once __DIR__ . '/src/database.php';

use Models\User;
use Models\Project;

$user = User::where('id', $_SESSION['user_id'])->get();

//require __DIR__ . '/src/classes/Database.php';

//include __DIR__ . '/src/classes/controllers/signupController.php';

require __DIR__ . '/src/classes/controllers/AuthController.php'; // removed after rebuild of autoload
use Controllers\AuthController;


function getCommitId() {
    return trim((string) shell_exec('git rev-parse --short HEAD'));
}



$action = $_GET['action'] ?? null;
$auth = new AuthController();

if (isset($action)) {
    switch ($action) {
        case 'login':
            $identifier = $_POST['identifier'] ?? null;
            $password = $_POST['password'] ?? null;
            $auth->login($identifier, $password);
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
            }
            break;
        case 'logout':
            $auth->logout();
            break;
    }
    header('Location: /');
}


if (isset($_GET['pid']) && $auth->check()) {
    $project = Project::where('id', $_GET['pid'])->first();
}




// $content = include __DIR__ . '/src/views/index.php';


include __DIR__ . '/src/views/layout/head.php';
echo '<body>';
include __DIR__ . '/src/views/layout/navbar.php';
echo '<main>';
include __DIR__ . '/src/views/index.php';
echo '</main>';
echo '</body>';
include __DIR__ . '/src/views/layout/foot.php';




echo "<!--";


echo(json_encode($user));
echo "<hr>";
echo(auth());
echo "<hr>";
echo($auth->check());
echo "<hr>";
echo(isset($user));
?>




<?php if($auth->check()): ?>
<a href="?action=logout">Log out</a>
<?php endif ?>
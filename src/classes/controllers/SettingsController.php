<?php

namespace Controllers;

use Models\User;
use Ramsey\Uuid\Uuid;

class SettingsController extends Controller
{
    public function request_deletion()
    {
        $user = User::where('id', 'user_id'); // could have used Auth()->user() ...

        if (!($_POST['deletion_notice_accepted'] == true)) {
            // some error ...
        } else {
            $user->delete_soft();
        }
    }

    public function abort_deletion()
    {
        $user = User::where('id', 'user_id');

        if (!($_POST['recovery_notice_accepted'] == true)) {
            // error (hopefully soon)
        } else {
            $user->recover();
        }
    }

    public function privacy()
    {
        $user = User::where('id', 'user_id');

        $raw_data = $_POST;

        $_POST['show_email_owner'];
        $_POST['show_email_all'];
        $_POST['hidden_mode'];

        $_SESION['hidden_mode'] = $_POST['hidden_mode']; // ToDo: Also on login and every time the "dasboard"-view is opened ...
    }
}
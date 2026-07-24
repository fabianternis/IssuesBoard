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
}
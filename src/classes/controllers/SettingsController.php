<?php

namespace Controllers;

use Models\User;
use Ramsey\Uuid\Uuid;

class SettingsController extends Controller
{
    public function request_deletion()
    {
        $user = User::where('id', 'user_id'); // could have used Auth()->user() ...

        if (!($_POST['deletion_notice^_accepted'] == true)) {
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
        global $target_uri;

        $user = User::where('id', $_SESSION['user_id'])->firstOrFail();

        // $raw_data = $_POST;

        $settings_update['privacy'] = [
            'show_email_owner' => isset($_POST['show_email_owner']),
            'show_email_all' => isset($_POST['show_email_all']),
            'hidden_mode' => isset($_POST['hidden_mode']),
        ];

        $old_settings = $user->settings ?? [];
        $settings_array = array_merge($old_settings, $settings_update);

        $user->update(['settings' => $settings_array]);

        $_SESSION['hidden_mode'] = isset($_POST['hidden_mode']); // ToDo: Also on login and every time the "dasboard"-view is opened ...

        // $target_uri = create_url_with_attributes(['page' => 'privacy', 'object' => 'settings', 'success' => 1]); // Sem-Done: Do THIS ... also: will 1 be true is something I am asking myself
        $target_uri = create_url_with_attributes(['page' => 'privacy', 'object' => 'settings', 'success' => 1, 'action' => 'show_']); // Sem-Done: Do THIS ... also: will 1 be true is something I am asking myself
    }

    public function show_() // I was stupid enough to require "id" on show() in bootstrap.php ...
    {
        // global $page;

        global $view_name;
        $view_name = 'settings';
    }
}
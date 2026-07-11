<?php

namespace Controllers;


use BlakvGhost\PHPValidator\Validator;
use BlakvGhost\PHPValidator\ValidatorException;
use Models\User;
use Ramsey\Uuid\Uuid;

class AuthController /*extends Controller*/ {

    /*
    function signup($POST_data) {
        try {
            $data = [
                'email' => $POST_data['email'],
                'username' => $POST_data['username'],
                'password' =>  $POST_data['password'],
                'password_confirmation' => $POST_data['password_confirmation'],
            ];*/

    function signup($email, $username, $password, $password_confirmation) {
        try {
            $data = [
                'email' => $email,
                'username' => $username,
                'password' =>  $password,
                'password_confirmation' => $password_confirmation,
            ];

            $validator = new Validator($data, [
                'email' => 'required|email',
                'username' => 'required|string',
                'password' => 'required',
                'password_confirmation' => 'confirmed:password'
            ]);

            if ($validator->isValid()) {
                // log("Signup Validation passed!");
            } else {
                $errors = $validator->getErrors();
                print_r($errors);return;
            }
        } catch (ValidatorException $e) {
            echo "Validation error: " . $e->getMessage();
            return;
        }
        $validated = $validator->validated();

        $user = new User;
        $user->id = (string) Uuid::uuid4();
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->password = password_hash($validated['password'], PASSWORD_DEFAULT);
        $user->save();

        $_SESSION['user_id'] =  $user->id;
    }

    function login($identifier, $password) {
        if(isset($_SESSION['user_id']) || !isset($identifier) || !isset($password)) {
            return;
        }
        if(str_contains($identifier, '@')) {
            // $user = User::where('email', $identifier)->get();
            $user = User::where('email', $identifier)->first();
        } else {
            // $user = User::where('username', $identifier)->get();
            $user = User::where('username', $identifier)->first();
        }
        if (!$user) {
            echo "No user could be found!";
            return;
        }
        /*if(password_hash($password, PASSWORD_DEFAULT) == $user->password) {
            $_SESSION['user_id'] = $user->id;
        } else {
            echo "Authentication Failed!";
        }*/
        if(password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
        }
    }

    function logout() {
        $_SESSION['user_id'] = null;
    }

    function check() {
        return isset($_SESSION['user_id']);
    }

    function user() {
        if($this->check()) {
            return User::where('id', $_SESSION['user_id'])->first();
        };
    }
}
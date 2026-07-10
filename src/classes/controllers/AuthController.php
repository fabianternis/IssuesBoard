<?php

namespace Controllers;


use BlakvGhost\PHPValidator\Validator;
use BlakvGhost\PHPValidator\ValidatorException;
use Models\User;
use Ramsay\Uuid;

class AuthController /*extends Controller*/ {

    /*
    function signup($POST_data) {
        try {
            $data = [
                'email' => $POST_data['email'],
                'username' => $POST_data['username'],
                'password' =>  $POST_data['password'],
                'pasword_confirmation' => $POST_data['password_confirmation'],
            ];*/

    function signup($email, $username, $password, $password_confirmation) {
        try {
            $data = [
                'email' => $email,
                'username' => $username,
                'password' =>  $password,
                'pasword_confirmation' => $password_confirmation,
            ];

            $validator = new Validator($data, [
                'email' => 'required|email',
                'username' => 'required|string',
                'password' => 'required',
                'password_confirmation' => 'confirmed:password'
            ]);

            if ($validator->isValid()) {
                log("Signup Validation passed!");
            } else {
                $errors = $validator->getErrors();
                print_r($errors);
            }
        } catch (ValidatorException $e) {
            echo "Validation error: " . $e->getMessage();
        }
        $validated = $validator->validated();

        $user = new User;
        $user->id = str(Uuid::uuid4());
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->password = password_hash($validated['password']);
        $user->save();

        $_SESSION['user_id']
    }

}
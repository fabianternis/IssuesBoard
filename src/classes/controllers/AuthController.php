<?php

namespace Controllers;

use BlakvGhost\PHPValidator\Validator;
use BlakvGhost\PHPValidator\ValidatorException;
use Models\User;
use Ramsey\Uuid\Uuid;

class AuthController 
{
    public function signup($email, $username, $password, $password_confirmation) 
    {
        error_log("[Signup Initialization] Invoked for email: {$email}");

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
                error_log("[Validation Success] Data passed schema checks.");
            } else {
                $errors = $validator->getErrors();
                error_log("[Validation Failure] " . json_encode($errors));
                print_r($errors);
                return;
            }
        } catch (ValidatorException $e) {
            error_log("[Validation Exception] " . $e->getMessage());
            echo "Validation error: " . $e->getMessage();
            return;
        }
        
        $validated = $validator->validated();

        $user = new User();
        $user->id = (string) Uuid::uuid4();
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->password = password_hash($validated['password'], PASSWORD_DEFAULT);

        error_log("[Pre-Persistence State] Attempting save for User ID: {$user->id}");

        try {
            // Capture the return value of the save operation
            $saveStatus = $user->save();
            
            // Check for silent boolean failures (common in some ORMs)
            if ($saveStatus === false) {
                error_log("[Persistence Failure] save() returned false. Check ORM strict mode or database validation constraints.");
                return;
            }

            error_log("[Persistence Success] User {$user->id} committed to database.");

        } catch (\Throwable $e) {
            // Intercept all PDO/Database/Logic exceptions
            error_log("[Persistence Exception] Type: " . get_class($e));
            error_log("[Persistence Exception] Message: " . $e->getMessage());
            error_log("[Persistence Exception] File: " . $e->getFile() . " on line " . $e->getLine());
            return;
        }

        $_SESSION['user_id'] = $user->id;
        error_log("[Session State] user_id assigned to session.");
    }
}
<?php

namespace Classes\Controllers;

use BlakvGhost\PHPValidator\Validator;
use BlakvGhost\PHPValidator\ValidatorException;
use Classes\Database;

class SignupController extends Database {

    private $username;
    private $email;
    private $password;


    public function __construct($email, $username, $password, $password_confirmation) {
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
                log("Validation passed!");
                /*
                $this->email = $email;
                $this->username = $username;
                $this->password = password_hash($password);
                */
            } else {
                $errors = $validator->getErrors();
                print_r($errors);
            }
        } catch (ValidatorException $e) {
            echo "Validation error: " . $e->getMessage();
        }
        $validated = $validator->validated();

        $this->email = $validated['email'];
        $this->username = $validated['username'];
        $this->password = password_hash($validated['password']);
    }

    // ToDo: move to User-model ...
    protected function checkUser() {
        // $statement = $this->connect()->prepare('SELECT id FROM users WHERE email = $this->email OR username = $this->username;');
        $statement = $this->connect()->prepare('SELECT id FROM users WHERE email = ? OR username = ?;');

        if(!$statement->execute(array($email, $username))) {
            $statement = null;
            // header('Location: /index.php?error=statement-failed' . ($action ? "&action={$action}" : ''));
            header('Location: /index.php?error=statement-failed' . (($_GET['action'] ?? null) ? '&action=' . urlencode($_GET['action']) : ''));

            exit();
        }

        if($statement->rowCount() > 0) {
            return false;
        } else { // does not have to be else ...
            return true;
        };
    }
}
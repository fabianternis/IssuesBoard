<?php

use BlakvGhost\PHPValidator\Validator;
use BlakvGhost\PHPValidator\ValidatorException;

class SignupController {

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



}
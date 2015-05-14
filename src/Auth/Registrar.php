<?php

namespace LaraParse\Auth;

use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use LaraParse\Subclasses\User;
use Parse\ParseObject;

class Registrar implements RegistrarContract
{
    /**
     * @var \Illuminate\Contracts\Validation\Factory
     */
    private $validator;

    /**
     * @param \Illuminate\Contracts\Validation\Factory $validator
     */
    public function __construct(ValidationFactory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        $messages = [
            'parse_user_unique' => 'Testing message.',
        ];

        $rules = [
            'email'    => ['required', 'email', 'max:255', 'parse_user_unique'],
            'password' => ['required', 'confirmed', 'min:6'],
        ];

        return $this->validator->make($data, $rules, $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return User
     */
    public function create(array $data)
    {
        $userSubclass   = ParseObject::getRegisteredSubclass('_User');
        $user           = new $userSubclass;
        $user->username = $data['email'];
        $user->email    = $data['email'];
        $user->password = $data['password'];
        $user->signUp();

        return $user;
    }
}

<?php

namespace LaraParse\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Parse\ParseException;
use Parse\ParseQuery;
use LaraParse\Subclasses\User;

class ParseUserProvider implements UserProvider
{

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|\LaraParse\Subclasses\User|null
     */
    public function retrieveById($identifier)
    {
        $query = new ParseQuery('_User');

        return $query->get($identifier, true);
    }

    /**
     * Retrieve a user by by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string $token
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|\LaraParse\Subclasses\User|null
     */
    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string                                     $token
     *
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Nothing
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|\LaraParse\Subclasses\User|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $query = new ParseQuery('_User');
        $query->equalTo('username', $credentials['username']);

        return $query->first(true);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array                                      $credentials
     *
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        try {
            User::logIn($credentials['username'], $credentials['password']);

            return true;
        } catch (ParseException $error) {
            return false;
        }
    }
}

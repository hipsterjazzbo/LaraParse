<?php

namespace LaraParse\Subclasses;

use Illuminate\Contracts\Auth\Authenticatable;
use LaraParse\Traits\CastsParseProperties;
use Parse\ParseUser;

/**
 * Class User
 *
 * @package LaraParse\Subclasses
 *
 * @property string         $username
 * @property string         $password
 * @property bool           $emailVerified
 * @property string         $email
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 */
class User extends ParseUser implements Authenticatable
{
    use CastsParseProperties;

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getObjectId();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return null;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return null;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     *
     * @return void
     */
    public function setRememberToken($value)
    {
        // Nothing
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return '';
    }
}

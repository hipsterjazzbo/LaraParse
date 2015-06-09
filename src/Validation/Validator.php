<?php

namespace LaraParse\Validation;

use Parse\ParseQuery;

class Validator
{
    public function parseUserUnique($attribute, $value, $parameters)
    {
        $query = new ParseQuery('_User');
        $query->equalTo('username', $value);

        return ! (bool) $query->count();
    }
}

<?php



namespace App\Services;

use App\Models\User;
use App\Database\Eloquent\Relations\HasOne;

class ModelLoader
{
    public static function __callStatic($method, $parameters)
    {
        if (strpos($method, 'with') === 0) {
            $relationship = lcfirst(substr($method, 4));

            $instance = new static;

            return $instance->$relationship()->get();
        }

        throw new \BadMethodCallException("Method {$method} does not exist.");
    }
}

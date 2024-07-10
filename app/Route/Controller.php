<?php


namespace App\Route;

use App\Framework\Http\Response;
use App\Util\Helper;
use BadMethodCallException;

abstract class Controller
{

    public function callAction($method, $parameters)
    {
        return $this->{$method}(...array_values($parameters));
    }

    public function __call($method, $parameters)
    {
        // throw new BadMethodCallException(sprintf(
        //     'Method %s::%s does not exist.', static::class, $method
        // ));

        return $this->methodNotFound($method);
    }

    public function methodNotFound($method)
    {
        return view('errors.method-errors', ['method' => $method, 'controller' => static::class, 'title' => 'Method not Found']);
    }
}

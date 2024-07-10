<?php



namespace App\Traits;

trait ModelLoaderTrait
{
    public static function __callStatic($method, $parameters)
    {
        // Check if the method starts with "with"
        if (strpos($method, 'with') === 0) {
            // Extract the relationship name
            $relationship = lcfirst(substr($method, 4));

            // Create a new instance of the model
            $model = new self(); // Adjust with your actual model class

            // Call the dynamic relationship method on the model instance
            return $model->$relationship()->get();
        }

        // If method not found, throw an exception or handle as needed
        throw new \BadMethodCallException("Method {$method} does not exist.");
    }
}

<?php


namespace App\Services;

use App\Models\User;
use App\Database\Eloquent\Relations\HasOne;

class RelationshipLoader
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public static function load($model, $relations)
    {
        $loader = new static($model);

        return $loader->with($relations);
    }

    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = explode(',', $relations);
        }

        foreach ($relations as $relation) {
            if (method_exists($this->model, $relation)) {
                $this->model = $this->model->$relation();
            }
        }

        return $this->model->get();
    }
}

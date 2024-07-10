<?php

namespace App\Database\Eloquent\Relations;

use App\Database\Eloquent\Model;

class HasOne
{
    protected $related;
    protected $foreignKey;
    protected $localKey;

    public function __construct(Model $related, $foreignKey, $localKey = 'id')
    {
        $this->related = $related;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
    }

    public function getResults()
    {
        return $this->related->where($this->foreignKey, $this->localKey)->first();
    }
}

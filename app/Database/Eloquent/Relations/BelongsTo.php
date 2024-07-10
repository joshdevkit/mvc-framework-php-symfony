<?php

namespace App\Database\Eloquent\Relations;

use App\Database\Database;

class BelongsTo
{
    protected $baseModel;
    protected $foreignKey;
    protected $relatedModel;
    protected $ownerKey;
    protected $relatedTable;

    public function __construct($baseModel, $relatedModel, $foreignKey, $ownerKey = 'id')
    {
        $this->baseModel = $baseModel;
        $this->relatedModel = new $relatedModel(Database::conn());
        $this->foreignKey = $foreignKey;
        $this->ownerKey = $ownerKey;
        $this->relatedTable = $this->relatedModel->getTable();
    }

    public function getResults()
    {
        $foreignKeyValue = $this->baseModel->{$this->foreignKey};
        return $this->relatedModel->where($this->ownerKey, $foreignKeyValue)->first();
    }
}

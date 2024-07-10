<?php

namespace App\Database\Eloquent\Relations;

use App\Database\Database;
use PDO;

class BelongsToMany
{
    protected $baseModel;
    protected $foreignKey;
    protected $relatedModel;
    protected $ownerKey;
    protected $relatedTable;
    protected $pivotTable;

    public function __construct($baseModel, $relatedModel, $foreignKey, $ownerKey = 'id', $pivotTable)
    {
        $this->baseModel = $baseModel;
        $this->relatedModel = new $relatedModel(Database::conn());
        $this->foreignKey = $foreignKey;
        $this->ownerKey = $ownerKey;
        $this->relatedTable = $this->relatedModel->getTable();
        $this->pivotTable = $pivotTable;
    }

    public function getResults($baseModelId)
    {
        $sql = "SELECT {$this->relatedTable}.* FROM {$this->relatedTable} 
                INNER JOIN {$this->pivotTable} ON {$this->relatedTable}.{$this->ownerKey} = {$this->pivotTable}.{$this->ownerKey} 
                WHERE {$this->pivotTable}.{$this->foreignKey} = ?";

        $stmt = $this->baseModel->getPDO()->prepare($sql);
        $stmt->execute([$baseModelId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this->relatedModel), [$this->baseModel->getPDO()]);
    }
}

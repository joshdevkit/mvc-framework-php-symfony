<?php

namespace App\Database\Eloquent\Relations;

use PDO;

class HasMany
{
    protected $related;
    protected $foreignKey;
    protected $localKey;
    protected $pdo;

    public function __construct($related, $foreignKey, $localKey, PDO $pdo)
    {
        $this->related = $related;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
        $this->pdo = $pdo;
    }

    public function getResults($parentId)
    {
        $sql = "SELECT * FROM {$this->related->getTable()} WHERE {$this->foreignKey} = :parentId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':parentId', $parentId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this->related));
    }
}

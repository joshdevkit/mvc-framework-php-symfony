<?php

namespace App\Database\Eloquent;

use App\Database\Database;
use App\Database\Eloquent\Relations\BelongsTo;
use PDO;
use Exception;
use App\Database\Eloquent\Relations\HasMany;
use App\Database\Eloquent\Relations\HasOne;
use App\Database\Eloquent\Relations\BelongsToMany;
use App\Exceptions\SqlExecutionException;

abstract class Model
{
    protected $pdo;
    protected $table;
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    protected $fillable = [];
    protected static $wheres = [];
    protected static $relationsToLoad = [];

    public function __construct(PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::conn();
    }

    public function __sleep()
    {
        return array_diff(array_keys(get_object_vars($this)), ['pdo']);
    }

    public function __wakeup()
    {
        $this->pdo = Database::conn();
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public static function create(array $attributes)
    {
        $model = new static(Database::conn());

        $fillableAttributes = array_intersect_key($attributes, array_flip($model->fillable));
        $nonFillableAttributes = array_diff_key($attributes, $fillableAttributes);

        if (!empty($nonFillableAttributes)) {
            $errorMessages = [];
            foreach ($nonFillableAttributes as $attribute => $value) {
                $errorMessages[] = "Column $attribute doesn't have a default value";
            }
            throw new SqlExecutionException(implode('<br>', $errorMessages));
        }

        return $model->insert($fillableAttributes);
    }


    public static function all()
    {
        $model = new static(Database::conn());
        return $model->selectAll();
    }

    public function first()
    {
        $parsedConditions = [];
        $paramValues = [];

        foreach (self::$wheres as $condition) {
            list($column, $operator, $value) = $condition;
            $parsedConditions[] = "{$column} {$operator} ?";
            $paramValues[] = $value;
        }

        $whereClause = implode(' AND ', $parsedConditions);
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($whereClause)) {
            $sql .= " WHERE {$whereClause}";
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($paramValues as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }

        $stmt->execute();
        return $stmt->fetchObject(get_class($this), [$this->pdo]);
    }

    public static function findOrFail($id)
    {
        $model = new static(Database::conn());
        $result = $model->selectWithParams($model->primaryKey, $id);

        if (!$result) {
            throw new Exception("Record not found");
        }

        return $result;
    }

    public static function where($column, $operator = '=', $value = null)
    {
        $model = new static(Database::conn());
        self::$wheres[] = [$column, $operator, $value];
        return $model;
    }

    protected function insert(array $attributes)
    {
        $columns = implode(', ', array_keys($attributes));
        $placeholders = implode(', ', array_fill(0, count($attributes), '?'));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute(array_values($attributes))) {
            // Get the last inserted ID
            $lastInsertedId = $this->pdo->lastInsertId();
            $data = $this->findOrFail($lastInsertedId);
            return $data;
            // Fetch the newly created record
            // $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
            // $stmt = $this->pdo->prepare($sql);
            // $stmt->execute([$lastInsertedId]);

            // // Fetch the result as an object
            // $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this));
            // $newObject = $stmt->fetch();

            // return $newObject;
        } else {
            return false;
        }
    }


    protected function selectAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_CLASS, get_class($this), [$this->pdo]);
        return $this->loadRelations($results);
    }

    protected function selectWithParams($column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        $result = $stmt->fetchObject(get_class($this), [$this->pdo]);
        return $this->loadRelations([$result])[0];
    }

    public static function find($column, $value)
    {
        $model = new static(Database::conn());
        return $model->getOne($column, $value);
    }

    protected function getOne($column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        $result = $stmt->fetchObject(get_class($this), [$this->pdo]);
        return $this->loadRelations([$result])[0];
    }

    public function setTable(string $table)
    {
        $this->table = $table;
    }

    public function setFillable(array $fillable)
    {
        $this->fillable = $fillable;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function hasOne($related, $foreignKey = null, $localKey = 'id')
    {
        $instance = new $related(Database::conn());
        $foreignKey = $foreignKey ?: $this->getTable() . '_id';

        return new HasOne($instance, $foreignKey, $localKey);
    }

    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        $foreignKey = $foreignKey ?: $this->primaryKey;
        $localKey = $localKey ?: $this->primaryKey;

        return new HasMany(new $related, $foreignKey, $localKey, $this->getPDO());
    }

    public function belongsTo($related, $foreignKey, $ownerKey = 'id')
    {
        return new BelongsTo($related, $foreignKey, $ownerKey, $this->getPDO());
    }

    public function belongsToMany($related, $foreignKey, $ownerKey = 'id', $pivotTable)
    {
        return new BelongsToMany($this, $related, $foreignKey, $ownerKey, $pivotTable);
    }

    public static function with($relations)
    {
        if (is_string($relations)) {
            $relations = explode(',', $relations);
        }

        self::$relationsToLoad = $relations;
        return new static(Database::conn());
    }

    protected function loadRelations(array $results)
    {
        if (empty(self::$relationsToLoad)) {
            return $results;
        }

        foreach (self::$relationsToLoad as $relation) {
            foreach ($results as $result) {
                if (method_exists($this, $relation)) {
                    $result->{$relation} = $this->{$relation}()->getResults($result->{$this->primaryKey});
                }
            }
        }

        return $results;
    }

    public static function update(array $attributes, array $conditions = [])
    {
        $model = new static(Database::conn());
        return $model->updateSelected($attributes, $conditions);
    }

    protected function updateSelected(array $attributes, array $conditions = [])
    {
        $fillableAttributes = array_intersect_key($attributes, array_flip($this->fillable));
        $nonFillableAttributes = array_diff_key($attributes, $fillableAttributes);

        if (!empty($nonFillableAttributes)) {
            $errorMessages = [];
            foreach ($nonFillableAttributes as $attribute => $value) {
                $errorMessages[] = "Column $attribute doesn't have a default value";
            }
            throw new SqlExecutionException(implode('<br>', $errorMessages));
        }

        $columns = [];
        $paramValues = [];

        foreach ($attributes as $column => $value) {
            $columns[] = "{$column} = ?";
            $paramValues[] = $value;
        }

        $parsedConditions = [];
        foreach ($conditions as $column => $value) {
            $parsedConditions[] = "{$column} = ?";
            $paramValues[] = $value;
        }

        $columns = implode(', ', $columns);
        $whereClause = implode(' AND ', $parsedConditions);

        $sql = "UPDATE {$this->table} SET {$columns}";

        if (!empty($whereClause)) {
            $sql .= " WHERE {$whereClause}";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($paramValues);
        return $stmt->rowCount();
    }

    public static function destroy($id)
    {
        $model = new static(Database::conn());
        return $model->delete($id);
    }

    protected function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}

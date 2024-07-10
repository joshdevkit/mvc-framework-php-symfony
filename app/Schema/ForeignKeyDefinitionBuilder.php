<?php

namespace App\Schema;

class ForeignKeyDefinitionBuilder
{
    protected $blueprint;
    protected $localColumn;
    protected $foreignColumn;
    protected $foreignTable;

    public function __construct($blueprint, $localColumn)
    {
        $this->blueprint = $blueprint;
        $this->localColumn = $localColumn;
    }

    public function references($foreignColumn)
    {
        $this->foreignColumn = $foreignColumn;
        return $this;
    }

    public function on($foreignTable)
    {
        $this->foreignTable = $foreignTable;
        return $this;
    }

    public function onDelete($action)
    {
        $definition = "FOREIGN KEY ({$this->localColumn}) REFERENCES {$this->foreignTable}({$this->foreignColumn}) ON DELETE {$action}";
        $this->blueprint->addForeignKey(new ForeignKeyDefinition($definition));
        return $this->blueprint;
    }
}

class ForeignKeyDefinition
{
    protected $definition;

    public function __construct($definition)
    {
        $this->definition = $definition;
    }

    public function getDefinition()
    {
        return $this->definition;
    }
}

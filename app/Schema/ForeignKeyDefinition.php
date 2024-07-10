<?php

namespace App\Schema;

class ForeignKeyDefinition
{
    protected $localColumn;
    protected $foreignTable;
    protected $foreignColumn;
    protected $onDeleteAction;

    public function __construct($localColumn, $foreignTable, $foreignColumn)
    {
        $this->localColumn = $localColumn;
        $this->foreignTable = $foreignTable;
        $this->foreignColumn = $foreignColumn;
    }

    public function onDelete($action)
    {
        $this->onDeleteAction = strtoupper($action);
        return $this;
    }

    public function getDefinition()
    {
        $onDelete = $this->onDeleteAction ? " ON DELETE {$this->onDeleteAction}" : "";
        return "FOREIGN KEY ({$this->localColumn}) REFERENCES {$this->foreignTable}({$this->foreignColumn}){$onDelete}";
    }
}

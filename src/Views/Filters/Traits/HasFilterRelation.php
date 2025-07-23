<?php

namespace Rappasoft\LaravelLivewireTables\Views\Filters\Traits;

trait HasFilterRelation
{
    public $relation_key;

    public function setFilterRelationKey(string $key_name): self
    {
        $this->relation_key = $key_name;
        return $this;
    }

    public function hasFilterRelationKey(): bool
    {
        return ($this->relation_key != '' && $this->relation_key != null) ? true : false;
    }
    /**
     * Get the filter options.
     */
    public function getFilterRelationKey() : ?string
    {
        return $this->relation_key;
    }

}

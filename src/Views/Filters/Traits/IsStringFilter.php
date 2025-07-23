<?php

namespace Rappasoft\LaravelLivewireTables\Views\Filters\Traits;

trait IsStringFilter
{
    public function isEmpty($value): bool
    {
        if (!is_string($value) && !is_null($value)) {
            return false;
        }
        
        return is_null($value) || $value === '';
    }

    /**
     * Gets the Default Value for this Filter via the Component
     */
    public function getFilterDefaultValue(): ?string
    {
        return $this->filterDefaultValue ?? null;
    }
}

<?php

namespace Rappasoft\LaravelLivewireTables\Traits\Filters;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Rappasoft\LaravelLivewireTables\Views\Filters\ConditionalFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\NumericConditionalFilter;

trait HandlesConditionalFilters
{
    /**
     * Apply all active conditional filters to the builder
     */
    public function applyConditionalFilters(Builder $builder): Builder
    {
        if (count($this->getAppliedFiltersWithValues())) {
            foreach ($this->getAppliedFiltersWithValues() as $key => $value) {
                $filter = $this->getFilterByKey($key);

                // Skip if filter is not found
                if (!$filter) {
                    continue;
                }
                
                // Apply filter callback if it exists
                if ($filter->hasFilterCallback()) {
                    $builder = $filter->getFilterCallback()($builder, $value);
                }
            }
        }

        return $builder;
    }

    /**
     * Set a conditional filter value and refresh the table
     */
    public function setConditionalFilter(string $key, array $value): void
    {
        $this->setFilter($key, $value);
        $this->resetPage();
    }

    /**
     * Reset a specific conditional filter
     */
    public function resetConditionalFilter(string $key): void
    {
        $filter = $this->getFilterByKey($key);
        
        if ($filter) {
            $this->resetFilter($filter);
            $this->resetPage();
        }
    }
    
    /**
     * Check if a filter is a conditional filter
     */
    public function isConditionalFilter($filter): bool
    {
        return $filter instanceof ConditionalFilter || $filter instanceof NumericConditionalFilter;
    }

    /**
     * Get active conditional filters
     */
    #[Computed]
    public function getConditionalFilters(): array
    {
        $conditionalFilters = [];
        
        foreach ($this->getFilters() as $filter) {
            if ($this->isConditionalFilter($filter)) {
                $conditionalFilters[$filter->getKey()] = $filter;
            }
        }
        
        return $conditionalFilters;
    }
    
    /**
     * Get a specific filter value from appliedFilters
     */
    public function getAppliedFilterValue(string $filterKey)
    {
        return $this->getAppliedFilters()[$filterKey] ?? null;
    }

    /**
     * Updates the condition part of a conditional filter
     */
    public function updateFilterCondition(string $key, string $condition): void
    {
        $filter = $this->getFilterByKey($key);
        
        if ($filter && $this->isConditionalFilter($filter)) {
            $value = $this->getAppliedFilterValue($key) ?? [];
            
            if (!is_array($value)) {
                $value = ['value' => $value, 'condition' => $condition];
            } else {
                $value['condition'] = $condition;
            }
            
            $this->setFilter($key, $value);
            $this->resetPage();
        }
    }
    
    /**
     * Updates the value part of a conditional filter
     */
    public function updateFilterValue(string $key, $inputValue): void
    {
        $filter = $this->getFilterByKey($key);
        
        if ($filter && $this->isConditionalFilter($filter)) {
            $value = $this->getAppliedFilterValue($key) ?? [];
            $condition = isset($value['condition']) ? $value['condition'] : $filter->getDefaultCondition();
            
            if (!is_array($value)) {
                $value = ['value' => $inputValue, 'condition' => $condition];
            } else {
                $value['value'] = $inputValue;
            }
            
            $this->setFilter($key, $value);
            $this->resetPage();
        }
    }
}

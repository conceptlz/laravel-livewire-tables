<?php

namespace Rappasoft\LaravelLivewireTables\Views\Filters\Traits;

use Illuminate\Database\Eloquent\Builder;
use Closure;
use Rappasoft\LaravelLivewireTables\Traits\Filters\HasFilterHelper;
/**
 * Trait HasFilterConditions
 * Provides helper methods for working with filter conditions
 */
trait HasFilterConditions
{
    use HasFilterHelper;
    protected array $conditions = [];

    protected string $selectedCondition;
    /**
     * Get text filter conditions
     * Can be overridden to customize available conditions
     *
     * @return array
     */
    public function getTextFilterConditions(): array
    {
        return [
            'contains' => 'Contains',
            'does_not_contain' => 'Does Not Contain',
            'equals' => 'Is Exactly',
            'not_equals' => 'Is Not',
            'starts_with' => 'Starts with',
            'ends_with' => 'Ends with',
            'is_empty' => 'Is Empty',
            'is_not_empty' => 'Is Not Empty',
        ];
    }
    
    /**
     * Get numeric filter conditions
     * Can be overridden to customize available conditions
     *
     * @return array
     */
    public function getNumericFilterConditions(): array
    {
        return [
            'equals' => 'Equals',
            'not_equals' => 'Not Equals',
            'gt' => 'Greater Than',
            'lt' => 'Less Than',
            'gte' => 'Greater Than or Equal',
            'lte' => 'Less Than or Equal',
            'is_empty' => 'Is Empty',
            'is_not_empty' => 'Is Not Empty',
        ];
    }


     /**
     * Set available conditions for this filter
     */
    public function conditions(array $conditions): self
    {
        $this->conditions = $conditions;
        return $this;
    }
    
    
     /**
     * Get available conditions for this filter
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * Set default condition
     */
    public function defaultCondition(string $condition): self
    {
        $this->defaultCondition = $condition;
        $this->selectedCondition = $condition;
        return $this;
    }

    /**
     * Get default condition
     */
    public function getDefaultCondition(): string
    {
        return $this->defaultCondition;
    }

    /**
     * Set selected condition
     */
    public function selectedCondition(string $condition): self
    {
        $this->selectedCondition = $condition;
        return $this;
    }

    /**
     * Get selected condition
     */
    public function getSelectedCondition(): string
    {
        return $this->selectedCondition;
    }

     /**
     * Apply the filter to the builder instance
     */
    public function getFilterCallback(): callable
    {
        if($this->filterCallback !== null)
        {
            return $this->filterCallback;
        }
        return function($builder, $value) {
            // Use the applyConditionFilter method directly from the trait
            return $this->applyConditionFilter($builder, $value);
        };
    }

    public function hasFilterCallback(): bool
    {
        return true;
    }


    /**
     * Get the database column name to filter on
     * This is typically the key passed when creating the filter
     */
    protected function getCustomFilterAttribute(): string
    {
        return ($this->hasFilterRelationKey()) ? $this->getFilterRelationKey() : $this->getKey();
    }

    
    /**
     * Apply conditional filtering based on condition and value
     * This method can be reused by other filters that need to apply similar conditional logic
     * 
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array|string $value The filter value which can be a string or array with condition and value keys
     * @param string|null $column The database column name to filter (defaults to the filter key)
     * @param callable|null $customCallback Optional custom callback to override default behavior
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyConditionFilter($builder, $value, ?string $column = null, ?callable $customCallback = null): \Illuminate\Database\Eloquent\Builder
    {
        if (!$column) {
            $column = $this->getCustomFilterAttribute();
        }
   
        // If a custom callback is provided, use it instead of the default logic
        if ($customCallback !== null) {
            return $customCallback($builder, $value, $column);
        }
        if (is_array($value))
        {
            $value['condition'] =(is_array($value) && isset($value['condition'])) ? $value['condition'] : $this->defaultCondition;
            \Log::info('value-condition' . $value['condition']);
            \Log::info('defaultCondition' . $this->defaultCondition);
            if (is_array($value) && isset($value['value']) && isset($value['condition'])) {
                $filterValue = $value['value'];
                $condition = $value['condition'];
                
                // Apply the filter using our helper method
                return $this->applyWhere($builder, $column, $condition, $filterValue);
            }
        }else
        {
            return $this->applyWhere($builder, $column, $this->defaultCondition, $value);
        }
        
        
        return $builder;
    }
}

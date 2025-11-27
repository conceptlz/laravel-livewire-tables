<?php

namespace Rappasoft\LaravelLivewireTables\Traits\Filters;
use Illuminate\Database\Eloquent\Builder;
use Closure;
trait HasFilterHelper
{
   
    /**
     * Get the actual value from a filter value array
     * 
     * @param mixed $value Filter value which may be array with condition and value keys
     * @return mixed The actual value or null if not found
     */
    public function getFilterValue($value)
    {
        if (is_array($value) && isset($value['value'])) {
            return $value['value'];
        }
        
        return is_array($value) ? null : $value;
    }

      
    /**
     * Get the condition from a filter value array
     * 
     * @param mixed $value Filter value which may be array with condition and value keys
     * @param string $default Default condition to return if not found
     * @return string The condition or default if not found
     */
    public function getFilterCondition($value, string $default = 'contains'): string
    {
        if (is_array($value) && isset($value['condition'])) {
            return $value['condition'];
        }
        
        return $default;
    }

    /**
     * Apply a WHERE condition to the query builder based on the condition type
     * 
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string $column The column to filter on
     * @param string $condition The condition to apply (contains, equals, etc.)
     * @param mixed $value The value to filter by
     * @param string $boolean The boolean operator (and/or)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyWhere(Builder $builder, string $column, string $condition, $value, string $boolean = 'and'): Builder
    {
        
        // Special cases for empty/not empty conditions
        if ($condition === 'is_empty') {
            return $boolean === 'and'
                ? $builder->where(function($query) use ($column) {
                    $query->whereNull($column)->orWhere($column, '');
                  })
                : $builder->orWhere(function($query) use ($column) {
                    $query->whereNull($column)->orWhere($column, '');
                  });
        }
        
        if ($condition === 'is_not_empty') {
            return $boolean === 'and'
                ? $builder->where(function($query) use ($column) {
                    $query->whereNotNull($column)->where($column, '!=', '');
                  })
                : $builder->orWhere(function($query) use ($column) {
                    $query->whereNotNull($column)->where($column, '!=', '');
                  });
        }

        // Get the correct SQL operator and formatted value for the condition
        $operator = $this->getOperatorForCondition($condition);
        $formattedValue = $this->formatValueForCondition($condition, $value);
        // \Log::info('operator:' . $operator);
        // \Log::info('formattedValue:' . $formattedValue);
        // Use formatted value for LIKE operators, otherwise use the original value
        $finalValue = in_array($operator, ['like', 'not like']) ? $formattedValue : $value;
        // \Log::info('finalValue:' . $finalValue);
        // Apply the filter to the query builder with the specified boolean
        return $boolean === 'and'
            ? $builder->where($column, $operator, $finalValue)
            : $builder->orWhere($column, $operator, $finalValue);
    }
    
    /**
     * Apply an OR WHERE condition to the query builder based on the condition type
     * 
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string $column The column to filter on
     * @param string $condition The condition to apply (contains, equals, etc.)
     * @param mixed $value The value to filter by
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyOrWhere(Builder $builder, string $column, string $condition, $value): Builder
    {
        return $this->applyWhere($builder, $column, $condition, $value, 'or');
    }
    
    /**
     * Apply a WHERE with a closure callback
     * 
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param Closure $callback The callback to apply
     * @param string $boolean The boolean operator (and/or)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyWhereCallback(Builder $builder, Closure $callback, string $boolean = 'and'): Builder
    {
        return $boolean === 'and' ? $builder->where($callback) : $builder->orWhere($callback);
    }
    
    /**
     * Get the appropriate SQL operator for the given condition
     * 
     * @param string $condition The condition type (contains, starts_with, etc.)
     * @return string The SQL operator
     */
    protected function getOperatorForCondition(string $condition): string
    {
        switch ($condition) {
            case 'contains':
            case 'starts_with':
            case 'ends_with':
                return 'like';
            case 'does_not_contain':
                return 'not like';
            case 'equals':
                return '=';
            case 'not_equals':
                return '!=';
            case 'gt':
                return '>';
            case 'lt':
                return '<';
            case 'gte':
                return '>=';
            case 'lte':
                return '<=';
            default:
                return 'like';
        }
    }

    /**
     * Format a value based on the condition type
     * 
     * @param string $condition The condition type (contains, starts_with, etc.)
     * @param string $value The value to format
     * @return string The formatted value
     */
    protected function formatValueForCondition(string $condition, string $value): string
    {
        switch ($condition) {
            case 'contains':
            case 'does_not_contain':
                return '%' . $value . '%';
            case 'starts_with':
                return $value . '%';
            case 'ends_with':
                return '%' . $value;
            case 'is_empty':
            case 'is_not_empty':
                return '';
            default:
                return $value;
        }
    }
}

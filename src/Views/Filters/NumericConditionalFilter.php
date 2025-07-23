<?php

namespace Rappasoft\LaravelLivewireTables\Views\Filters;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Rappasoft\LaravelLivewireTables\Views\Filters\Traits\{HasWireables, HasFilterConditions};

class NumericConditionalFilter extends Filter
{
    use HasWireables, HasFilterConditions;

    public string $wireMethod = 'defer';

    protected string $view = 'livewire-tables::components.tools.filters.conditional-field';
    
   
    /**
     * The default condition
     */
    protected string $defaultCondition = 'equals';

    public function __construct(string $name, ?string $key = null)
    {
        parent::__construct($name, $key);
        $this->selectedCondition = $this->defaultCondition;
        $this->conditions($this->getNumericFilterConditions());
    }


   
    public function validate($value)
    {
        if (is_array($value)) {
            $numericValue = $value['value'] ?? '';
            
            if ($this->hasConfig('min') && $numericValue < $this->getConfig('min')) {
                return false;
            }
            
            if ($this->hasConfig('max') && $numericValue > $this->getConfig('max')) {
                return false;
            }
            
            return is_numeric($numericValue) ? $value : false;
        }
        
        return false;
    }

    /**
     * Apply the filter to the builder instance
     */
    public function getFilterCallback(): callable
    {
        return function($builder, $value) {
            // Use the applyConditionFilter method directly from the trait
            return $this->applyConditionFilter($builder, $value);
        };
    }
    public function getFilterPillValue($value): array|string|bool|null
    {
        if(is_array($value) && isset($value['condition']))
        {
            return ['condition' => $value['condition'],'value' => $value['value']];
        }
        return $value;
    }
    protected function getCoreInputAttributes(): array
    {
        $attributes = array_merge(parent::getCoreInputAttributes(),
            [
                'type' => 'number',
                'placeholder' => $this->hasConfig('placeholder') ? $this->getConfig('placeholder') : null,
                'min' => $this->hasConfig('min') ? $this->getConfig('min') : null,
                'max' => $this->hasConfig('max') ? $this->getConfig('max') : null,
                'step' => $this->hasConfig('step') ? $this->getConfig('step') : null,
                'wire:key' => $this->generateWireKey($this->getGenericDisplayData()['tableName'], 'conditional-number'),
                'wire:model.defer' => 'filterComponents.'.$this->getKey().'.value',
            ]);
        ksort($attributes);

        return $attributes;
    }
    
    public function isEmpty(array|string|null $value): bool
    {
        if (is_array($value)) {
            // Case 1: Whole array is empty
            if (empty($value)) {
                return true;
            }

            // Case 2: Array has 'value' key but it is null or empty string
            return array_key_exists('value', $value) && ($value['value'] === null || $value['value'] === '');
        }

        // Case 3: $value is not array
        return $value === null || $value === '';
    }
}

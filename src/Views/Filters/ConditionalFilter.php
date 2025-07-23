<?php

namespace Rappasoft\LaravelLivewireTables\Views\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\Views\Filter;
use Rappasoft\LaravelLivewireTables\Views\Filters\Traits\HasFilterConditions;
use Rappasoft\LaravelLivewireTables\Views\Filters\Traits\HasWireables;
use Rappasoft\LaravelLivewireTables\Views\Filters\Traits\IsStringFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\Traits\HandlesWildcardStrings;

class ConditionalFilter extends Filter
{
    use IsStringFilter;
    use HasWireables;
    use HandlesWildcardStrings;
    use HasFilterConditions;

    public string $wireMethod = 'defer';

    protected string $view = 'livewire-tables::components.tools.filters.conditional-field';

   
    protected string $defaultCondition = 'contains';
    protected string $selectedCondition;

    public function __construct(string $name, ?string $key = null)
    {
        parent::__construct($name, $key);
        $this->selectedCondition = $this->defaultCondition;
        $this->conditions($this->getTextFilterConditions());
    }


    
    public function validate($value)
    {
        if (is_array($value)) {
            $textValue = $value['value'] ?? '';
            
            if ($this->hasConfig('maxlength')) {
                return strlen($textValue) <= $this->getConfig('maxlength') ? $value : false;
            }

            return strlen($textValue) ? $value : false;
        }

        return false;
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
                'type' => 'text',
                'placeholder' => $this->hasConfig('placeholder') ? $this->getConfig('placeholder') : null,
                'maxlength' => $this->hasConfig('maxlength') ? $this->getConfig('maxlength') : null,
                'wire:key' => $this->generateWireKey($this->getGenericDisplayData()['tableName'], 'conditional-text'),
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

<?php

namespace Rappasoft\LaravelLivewireTables\Views;

use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\Views\Filters\Traits\IsFilter;

abstract class Filter
{
    use IsFilter;

    protected string $view = '';

    public function __construct(string $name, ?string $key = null)
    {
        $this->name = $name;

        if ($key) {
            $this->key = $key;
        } else {
            $this->key = Str::snake($name);
        }
        $this->config([]);
    }

    /**
     * @return static
     */
    public static function make(string $name, ?string $key = null): Filter
    {
        return new static($name, $key);
    }

    /**
     * Check if the filter value is empty
     * This is the base implementation - override in specific filter types
     */
    public function isEmpty(array|string|null $value): bool
    {
        if (is_array($value)) {
            return empty($value) || (isset($value['value']) && (is_null($value['value']) || $value['value'] === ''));
        }

        return is_null($value) || $value === '';
    }
}

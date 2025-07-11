<?php

namespace Rappasoft\LaravelLivewireTables\Views\Columns\Traits;

use Illuminate\Support\HtmlString;
use Rappasoft\LaravelLivewireTables\Exceptions\DataTableConfigurationException;
use Rappasoft\LaravelLivewireTables\Views\Column;

trait HasExportable
{
    protected mixed $exportCallback = null;

    protected bool $preventExport = false;

    public function exportCallback(callable $callable): Column
    {
        $this->exportCallback = $callable;

        return $this;
    }
    public function excludeFromExport(): Column
    {
        $this->preventExport = true;

        return $this;
    }

    public function hasexportCallback(): bool
    {
        return $this->exportCallback !== null;
    }

    public function getExportCallback(): ?callable
    {
        return $this->exportCallback;
    }

    public function hasexcludeFromExport(): bool
    {
        return $this->preventExport;
    }
}

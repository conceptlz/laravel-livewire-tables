<?php

namespace Rappasoft\LaravelLivewireTables\Traits;

use Livewire\Attributes\Locked;
use Rappasoft\LaravelLivewireTables\Traits\Configuration\FixedColumnSelectConfiguration;
use Rappasoft\LaravelLivewireTables\Traits\Helpers\FixedColumnSelectHelpers;

trait WithFixedColumnSelect
{
    use FixedColumnSelectConfiguration,
        FixedColumnSelectHelpers;

    #[Locked]
    public bool $fixedColumnSelectStatus = true;

    public array $selectedFixedColumns = [];

    public array $fixableColumns = [];

    public int $maxFixedColumns = 3;

    public array $columnWidths = [];

    public function bootedWithFixedColumnSelect(): void
    {
        $this->callHook('configuringFixedColumnSelect');
        $this->callTraitHook('configuringFixedColumnSelect');

        $this->setupFixedColumnSelect();

        $this->callHook('configuredFixedColumnSelect');
        $this->callTraitHook('configuredFixedColumnSelect');
    }

    public function updatedSelectedFixedColumns(string|array $value): void
    { 
        
        $this->toggleFixedColumn($value);
    }

    public function setColumnWidth(string $columnSlug, int $width): void
    {
        $this->columnWidths[$columnSlug] = $width;
    }

    public function toggleFixedColumn(string|array $columnSlug): void
    {
        if (count($this->selectedFixedColumns) > $this->maxFixedColumns) {
            $this->selectedFixedColumns = array_values(array_diff($this->selectedFixedColumns, [$columnSlug]));
        }        
        // Dispatch event to recalculate column widths
        $this->dispatch('fixed-columns-updated');
    }
}

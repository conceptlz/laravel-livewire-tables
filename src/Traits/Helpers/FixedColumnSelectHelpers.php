<?php

namespace Rappasoft\LaravelLivewireTables\Traits\Helpers;

use Rappasoft\LaravelLivewireTables\Views\Column;

trait FixedColumnSelectHelpers
{
    protected array $defaultFixedColumns = [];
    
    protected bool $fixedColumnsRestoredFromPersistence = false;

    public function getFixedColumnSelectStatus(): bool
    {
        return $this->fixedColumnSelectStatus;
    }

    public function fixedColumnSelectIsEnabled(): bool
    {
        return $this->getFixedColumnSelectStatus() === true;
    }

    public function fixedColumnSelectIsDisabled(): bool
    {
        return $this->getFixedColumnSelectStatus() === false;
    }

    public function setupFixedColumnSelect(): void
    {
        if ($this->fixedColumnSelectIsDisabled()) {
            return;
        }

        // Apply default fixed columns ONLY if no persisted selection exists
        // Don't apply if user explicitly cleared all (empty array from persistence)
        if (!$this->fixedColumnsRestoredFromPersistence && !empty($this->defaultFixedColumns)) {
            $this->selectedFixedColumns = $this->defaultFixedColumns;
        }

        // Get fixable columns from visible columns
        $this->fixableColumns = $this->getColumnsForColumnSelect();
    }

    public function getSelectedFixedColumns(): array
    {
        return $this->selectedFixedColumns;
    }

    public function isColumnFixed(Column $column): bool
    {
        return in_array($column->getSlug(), $this->selectedFixedColumns);
    }

    public function getFixedColumnPosition(Column $column): int
    {
        $position = array_search($column->getSlug(), $this->selectedFixedColumns);
        return $position !== false ? $position + 1 : 0;
    }

    public function getColumnDynamicWidth(Column $column): int
    {
        return $this->columnWidths[$column->getSlug()] ?? 150;
    }

    public function getOrderedColumns(): array
    {
        $columns = $this->selectedVisibleColumns;
        
        if ($this->fixedColumnSelectIsDisabled() || empty($this->selectedFixedColumns)) {
            return $columns;
        }

        // Separate fixed and non-fixed columns
        $fixedColumns = [];
        $normalColumns = [];

        foreach ($columns as $column) {
            if ($this->isColumnFixed($column)) {
                $position = $this->getFixedColumnPosition($column);
                $fixedColumns[$position] = $column;
            } else {
                $normalColumns[] = $column;
            }
        }

        // Sort fixed columns by position and merge with normal columns
        ksort($fixedColumns);
        return array_merge(array_values($fixedColumns), $normalColumns);
    }
}

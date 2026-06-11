<?php

namespace Rappasoft\LaravelLivewireTables\Traits;
use Rappasoft\LaravelLivewireTables\Exports\DatatableExport;
use Rappasoft\LaravelLivewireTables\Models\TablePersistState;

trait WithSavingState
{

    public bool $persist = true;


    public function setPersist(bool $status): self
    {
        $this->persist = $status;

        return $this;
    }
    public function hasPersist(): bool
    {
        return $this->persist;
    }
    public function getPersistSessionKey(): string
    {
        return $this->getTableName().'-persist-key';
    }
    public function setPersistCookie()
    {
        if(!$this->persist ||  $this->getTableName() == 'table')
        {
            return;
        }
        TablePersistState::saveState(
            $this->getPersistSessionKey(),
            $this->getTablePersistStateToArray(),
            365
        );
    }
    private function getPersistCookieData(): void
    {
        if($this->debugIsEnabled())
        {
            \Log::info('persist-key ' . $this->getPersistSessionKey());
        }

        if ($this->persist && $this->getTableName() != 'table' ) {
            $data = TablePersistState::getState($this->getPersistSessionKey());
            
            if($data !== null) {
                if($this->debugIsEnabled())
                {
                    \Log::info('persist-data' , $data);
                }
                $this->restorePersistStateFromArray($data);
            }
        }
    }
    protected function getTablePersistStateToArray(): array
    {
        return [
            'sorts' => $this->sorts,
            'selectedColumns' => $this->selectedColumns,
            'selectedFixedColumns' => $this->selectedFixedColumns ?? [],
            'sortingPillsStatus' => $this->getSortingPillsStatus(),
            'sortingStatus' => $this->getSortingStatus(),
            'paginationStatus' => $this->getPaginationStatus(),
            'perPageVisibilityStatus' => $this->getPerPageVisibilityStatus(),
            'perPageAccepted' => $this->getPerPageAccepted(),
            'perPage' => $this->getPerPage(),
            //'page' => $this->paginators[$this->getComputedPageName()] ?? 1,
            'appliedFilters' => $this->appliedFilters,
            'filtersStatus' => $this->getFiltersStatus(),
           
        ];
    }

    protected function restorePersistStateFromArray(array $tableState): void
    {
        if(isset($tableState['sorts']))
        {
            $this->sorts = $tableState['sorts'];
        }
        if(isset($tableState['selectedColumns']))
        {
            $this->selectedColumns = $tableState['selectedColumns'];
        }
        if(isset($tableState['selectedFixedColumns']))
        {
            $this->selectedFixedColumns = $tableState['selectedFixedColumns'];
            // Mark that fixed columns were restored from persistence
            // This prevents default columns from overriding user's explicit "clear all" choice
            $this->fixedColumnsRestoredFromPersistence = true;
        }
        if(isset($tableState['appliedFilters']) && request()->has($this->getTableName().'-filters') != true)
        {
            $this->appliedFilters = $this->filterComponents = $tableState['appliedFilters'];
        }
        if($this->debugIsEnabled())
        {
            \Log::info('$this->appliedFilters',$this->appliedFilters);
        }
        $this->setSortingPillsStatus($tableState['sortingPillsStatus']);
        $this->setSortingStatus($tableState['sortingStatus']);
        $this->setPaginationStatus($tableState['paginationStatus']);
        $this->setPerPageVisibilityStatus($tableState['perPageVisibilityStatus']);
        $this->setPerPageAccepted($tableState['perPageAccepted']);
        $this->setPerPage($tableState['perPage']);
        //$this->setPage($tableState['page'], $this->getComputedPageName());
      
        $this->setFiltersStatus($tableState['filtersStatus']);
      

    }

    public function clearPersistState(): void
    {
        if ($this->getTableName() != 'table') {
            TablePersistState::clearState($this->getTableName());
        }
    }

    public static function cleanupExpiredStates(): int
    {
        return TablePersistState::cleanupExpired();
    }
}

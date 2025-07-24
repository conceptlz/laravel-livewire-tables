<?php

namespace Rappasoft\LaravelLivewireTables\Traits;
use Rappasoft\LaravelLivewireTables\Exports\DatatableExport;
use Illuminate\Support\Facades\Cookie;

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
        \Log::info('set-cookie');
        // if (request()->cookie($this->getPersistSessionKey()) != null) {
        //     addApilog('cookies-forget','');
        //     Cookie::forget($this->getPersistSessionKey());
        // }
        //$cookie = cookie($this->getPersistSessionKey(), 'dark_mode', 60, '/', '.yourdomain.com', false, false);
        $expires = time() + 60 * 60 * 24 * 365; // one year
        Cookie::queue($this->getPersistSessionKey(), json_encode($this->getTablePersistStateToArray()), $expires);
    }
    private function getPersistCookieData(): void
    {
        \Log::info('cookie ' . $this->getPersistSessionKey());
        if (request()->cookie($this->getPersistSessionKey()) != null && $this->persist && $this->getTableName() != 'table') {
            $data = json_decode(request()->cookie($this->getPersistSessionKey()),true);
            \Log::info('cookie-data' , $data);
            $this->restorePersistStateFromArray($data);
        }

    }
    protected function getTablePersistStateToArray(): array
    {
        return [
            'sorts' => $this->sorts,
            'selectedColumns' => $this->selectedColumns,
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
        if(isset($tableState['appliedFilters']))
        {
            $this->appliedFilters = $this->filterComponents = $tableState['appliedFilters'];
        }
        \Log::info('$this->appliedFilters',$this->appliedFilters);
        $this->setSortingPillsStatus($tableState['sortingPillsStatus']);
        $this->setSortingStatus($tableState['sortingStatus']);
        $this->setPaginationStatus($tableState['paginationStatus']);
        $this->setPerPageVisibilityStatus($tableState['perPageVisibilityStatus']);
        $this->setPerPageAccepted($tableState['perPageAccepted']);
        $this->setPerPage($tableState['perPage']);
        //$this->setPage($tableState['page'], $this->getComputedPageName());
      
        $this->setFiltersStatus($tableState['filtersStatus']);
      

    }

    
}

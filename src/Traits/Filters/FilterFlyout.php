<?php

namespace Rappasoft\LaravelLivewireTables\Traits\Filters;

trait FilterFlyout
{
    /**
     * Whether to use the filter flyout UI
     *
     * @var bool
     */
    public bool $filterFlyoutEnabled = true;

    /**
     * Pending filters waiting to be applied
     *
     * @var array
     */
    public array $selectedFilters = [];

    /**
     * Enable the filter flyout UI
     *
     * @return $this
     */
    public function enableFilterFlyout(): self
    {
        $this->filterFlyoutEnabled = true;

        return $this;
    }

    /**
     * Disable the filter flyout UI
     *
     * @return $this
     */
    public function disableFilterFlyout(): self
    {
        $this->filterFlyoutEnabled = false;

        return $this;
    }

    /**
     * Apply pending filters
     *
     * @param array $pendingFilters
     * @return void
     */
    public function applyPendingFilters(array $pendingFilters): void
    {
        foreach ($pendingFilters as $filterKey => $value) {
            // Only apply if filter exists
            if ($this->hasFilter($filterKey)) {
                $this->setFilter($filterKey, $value);
            }
        }

        // Reset pending filters
        $this->selectedFilters = [];
        
        // Refresh the data
      //  $this->filterTrackingStart();
    }

    /**
     * Get the filter flyout status
     *
     * @return bool
     */
    public function getFilterFlyoutStatus(): bool
    {
        return $this->filterFlyoutEnabled;
    }
}

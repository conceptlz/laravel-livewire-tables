<?php

namespace Rappasoft\LaravelLivewireTables\Traits;

use Rappasoft\LaravelLivewireTables\Traits\Core\{HasCustomAttributes, HasLocalisations};
use Rappasoft\LaravelLivewireTables\Traits\Filters\HandlesConditionalFilters;
use Rappasoft\LaravelLivewireTables\Views\Traits\Core\HasTheme;

trait HasAllTraits
{
    // Note Specific Order Below!
    use WithTableHooks;
    use HasLocalisations,
        WithLoadingPlaceholder,
        HasTheme,
        WithFilters,
        HandlesConditionalFilters;
    use WithQuery,
        ComponentUtilities,
        WithActions,
        WithData,
        WithQueryString,
        WithColumns,
        WithSorting,
        WithSearch,
        WithPagination;
    use WithBulkActions,
        HasCustomAttributes,
        WithCollapsingColumns,
        WithColumnSelect,
        WithFixedColumnSelect,
        WithConfigurableAreas,
        WithCustomisations,
        WithDebugging,
        WithEvents,
        WithFooter,
        WithRefresh,
        WithReordering,
        WithSecondaryHeader,
        WithSessionStorage,
        WithTableAttributes,
        WithTools,
        WithExport,
        WithSavingState;
}

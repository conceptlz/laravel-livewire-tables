<?php

namespace App\Http\Livewire\Tables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\ConditionalFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\NumericConditionalFilter;
use Carbon\Carbon;

class UsersTableComponent extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setTableName('users')
            ->setQueryStringStatus(true)
            ->setColumnSelectStatus(true)
            ->setPerPageAccepted([10, 25, 50, 100])
            ->setDefaultSort('name', 'asc');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),
                
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
                
            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),
                
            Column::make('Role')
                ->label(fn($row) => $row->roles->pluck('name')->implode(', ')),
                
            Column::make('Posts', 'posts_count')
                ->sortable(),
                
            Column::make('Status')
                ->label(fn($row) => $row->status ? 'Active' : 'Inactive'),
                
            Column::make('Created', 'created_at')
                ->sortable()
                ->format(fn($value) => $value ? Carbon::parse($value)->format('Y-m-d') : ''),
                
            Column::make('Last Login', 'last_login_at')
                ->sortable()
                ->format(fn($value) => $value ? Carbon::parse($value)->diffForHumans() : 'Never')
        ];
    }
    
    /**
     * Define the filters for this table
     *
     * @return array
     */
    public function filters(): array
    {
        return [
            // EXAMPLE 1: Basic string filter with default text conditions
            // This uses all the default conditions from getTextFilterConditions() in HasFilterConditions trait
            ConditionalFilter::make('Filter by Name', 'name')
                ->config([
                    'placeholder' => 'Search names...',
                    'maxlength' => 100,
                ]),
                
            // EXAMPLE 2: Custom condition set for string filter
            // You can define exactly which conditions you want to offer
            ConditionalFilter::make('Filter by Email', 'email')
                ->conditions([
                    'contains' => 'Contains',
                    'starts_with' => 'Starts with',
                    'ends_with' => 'Ends with',
                    'is_empty' => 'Is Empty',
                    'is_not_empty' => 'Is Not Empty',
                ])
                ->defaultCondition('contains'),
                
            // EXAMPLE 3: Numeric filter with default number conditions
            // This uses all conditions from getNumericFilterConditions() in HasFilterConditions trait
            NumericConditionalFilter::make('Filter by Posts Count', 'posts_count')
                ->config([
                    'placeholder' => 'Enter post count...',
                    'min' => 0,
                    'step' => 1,
                ]),
                
            // EXAMPLE 4: Date filter with custom callback using helper methods
            // Shows how to implement custom date handling using Carbon and the trait helpers
            ConditionalFilter::make('Filter by Registration Date', 'created_at')
                ->filter(function (Builder $builder, $value) {
                    // Using our helper methods to extract values
                    $dateValue = $this->getFilterValue($value);
                    $condition = $this->getFilterCondition($value, 'on');
                    
                    if (!empty($dateValue)) {
                        // Convert input to proper date format
                        try {
                            $date = Carbon::parse($dateValue)->format('Y-m-d');
                            
                            // Apply appropriate date condition
                            return $this->applyWhere($builder, 'created_at', $condition, $date);
                        } catch (\Exception $e) {
                            // Invalid date, return unmodified builder
                            return $builder;
                        }
                    }
                    
                    return $builder;
                })
                ->conditions([
                    'before' => 'Before',
                    'after' => 'After',
                    'on' => 'On Date'
                ])
                ->defaultCondition('on'),
                
            // EXAMPLE 5: Advanced filter with relationship handling using helper methods
            ConditionalFilter::make('Filter by Role', 'role')
                ->filter(function (Builder $builder, $value) {
                    // Using our helper methods to extract values
                    $roleValue = $this->getFilterValue($value);
                    $condition = $this->getFilterCondition($value, 'has');
                    
                    if (!empty($roleValue)) {
                        // Relationship filtering based on condition
                        if ($condition === 'has') {
                            return $builder->whereHas('roles', function ($query) use ($roleValue) {
                                $query->where('name', 'like', '%' . $roleValue . '%');
                            });
                        } else if ($condition === 'hasnt') {
                            return $builder->whereDoesntHave('roles', function ($query) use ($roleValue) {
                                $query->where('name', 'like', '%' . $roleValue . '%');
                            });
                        }
                    }
                    
                    return $builder;
                })
                ->conditions([
                    'has' => 'Has Role',
                    'hasnt' => 'Does Not Have Role'
                ])
                ->defaultCondition('has'),
                
            // EXAMPLE 6: Complex multi-field search with helper methods
            // Demonstrates combining WHERE and OR WHERE conditions
            ConditionalFilter::make('Search Everywhere', 'search')
                ->filter(function (Builder $builder, $value) {
                    // Extract filter value and condition using helper methods
                    $searchValue = $this->getFilterValue($value);
                    $condition = $this->getFilterCondition($value, 'contains');
                    
                    if (!empty($searchValue)) {
                        // Use a grouped where clause to search multiple fields
                        $builder = $builder->where(function($query) use ($searchValue, $condition) {
                            // Apply WHERE conditions on multiple fields with the same condition type
                            $this->applyWhere($query, 'name', $condition, $searchValue);
                            $this->applyOrWhere($query, 'email', $condition, $searchValue);
                            
                            // Also search in related posts
                            $this->applyWhereCallback($query, function($subQuery) use ($searchValue) {
                                $subQuery->whereHas('posts', function($postQuery) use ($searchValue) {
                                    $postQuery->where('title', 'like', '%'.$searchValue.'%');
                                });
                            }, 'or');
                        });
                    }
                    
                    return $builder;
                })
                ->conditions([
                    'contains' => 'Contains',
                    'starts_with' => 'Starts With',
                    'equals' => 'Equals Exactly'
                ])
                ->defaultCondition('contains'),
                
            // EXAMPLE 7: Boolean filter with custom conditions
            ConditionalFilter::make('User Status', 'status')
                ->filter(function (Builder $builder, $value) {
                    $statusValue = $this->getFilterValue($value);
                    $condition = $this->getFilterCondition($value, 'equals');
                    
                    if ($statusValue !== null) {
                        // Convert text status to boolean value for database
                        $boolValue = $statusValue === 'active';
                        return $builder->where('status', $boolValue);
                    }
                    
                    return $builder;
                })
                ->conditions([
                    'active' => 'Active Users',
                    'inactive' => 'Inactive Users'
                ])
                ->defaultCondition('active'),
        ];
    }
}
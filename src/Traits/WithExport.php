<?php

namespace Rappasoft\LaravelLivewireTables\Traits;
use Rappasoft\LaravelLivewireTables\Exports\DatatableExport;

trait WithExport
{

    public bool $exportable = true;

    public string $export_name;

    public function setExportable(bool $status): self
    {
        $this->exportable = $status;

        return $this;
    }
    public function hasExportable(): bool
    {
        return $this->exportable;
    }

    public function export(string $filename = 'DatatableExport.xlsx')
    {
        $export = new DatatableExport($this->mapCallbacks($this->getRows(true)));
        $filename = $this->export_name ? $this->export_name : $filename;
        $export->setFilename($filename);

        return $export->download();
    }

    public function mapCallbacks($paginatedCollection, $export = false)
    {
        $columns = $this->getColumns();
        $export_rows = [];
        foreach($paginatedCollection as $rowIndex => $row)
        {
            foreach($columns as $colIndex => $column)
            {
                
                if($column->isHidden() || $column->hasexcludeFromExport())
                {
                    continue;
                }
                elseif($this->columnSelectIsEnabled() && ! $this->columnSelectIsEnabledForColumn($column))
                {
                    continue;
                }
                elseif($column->isReorderColumn() && !$this->getCurrentlyReorderingStatus() && $this->getHideReorderColumnUnlessReorderingStatus()){
                    continue;
                }elseif($column->hasexportCallback())
                {
                    $export_rows[$rowIndex][$column->getTitle()] =  call_user_func($column->getExportCallback(), $row, $column);
                }
                else{
                    $export_rows[$rowIndex][$column->getTitle()] = $column->renderContents($row);
                }
            }
        }
        return collect($export_rows);
    }

}

<?php

namespace Rappasoft\LaravelLivewireTables\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class DatatableExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnWidths, WithStyles,WithColumnFormatting
{
    use Exportable;

    public $collection;
    public $fileName = 'DatatableExport.xlsx';
    public $styles = [];
    public $columnWidths = [];
    public $columnFormat = [];

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    public function collection()
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return array_keys((array) $this->collection->first());
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setColumnWidths($columnWidths)
    {
        $this->columnWidths = $columnWidths;

        return $this;
    }

    public function getColumnWidths(): array
    {
        return $this->columnWidths;
    }
    public function setColumnFormat($columnFormat)
    {
        $this->columnFormat = $columnFormat;

        return $this;
    }

    public function getColumnFormat(): array
    {
        return $this->columnFormat;
    }

    public function columnWidths(): array
    {
        return $this->getColumnWidths();
    }

    public function setStyles($styles)
    {
        $this->styles = $styles;

        return $this;
    }

    public function getStyles(): array
    {
        return $this->styles;
    }

    public function styles(Worksheet $sheet)
    {
       
        return $this->getStyles();
    }
    public function columnFormats(): array
    {
        return $this->getColumnFormat();
    }

    public function download()
    {
        return Excel::download($this, $this->getFileName());
    }
}

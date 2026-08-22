<?php

namespace App\Filament\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExcelExporter implements FromView, ShouldAutoSize
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        protected array $data,
        protected string $view,
    ) {}

    public function view(): View
    {
        return view($this->view, $this->data);
    }
}

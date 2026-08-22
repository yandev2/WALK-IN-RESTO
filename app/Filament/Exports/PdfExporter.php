<?php

namespace App\Filament\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfExporter
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        protected array $data,
        protected string $view,
        protected string $pathFile,
        protected string $disk = 'public',
    ) {}

    public function export(): void
    {
        $pdf = Pdf::loadView($this->view, $this->data)
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ])
            ->setPaper('A4', 'landscape');

        Storage::disk($this->disk)->put($this->pathFile, $pdf->output());
    }
}

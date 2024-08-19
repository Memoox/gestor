<?php

namespace App\Exports;

use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportReporte implements FromView, WithEvents,ShouldAutoSize
{

    use Exportable;
    use RegistersEventListeners;

    public $documentos;
    // public $mes;
    // public $fecha_exportacion;


    function __construct($documentos)
    {
        $this->documentos = $documentos;
        // $this->mes = $mes;
        // $this->fecha_exportacion = $fecha_exportacion;
    }

    public function view(): View
    {
        return view('exports.export_reporte', [
            'documentos' => $this->documentos,
            // 'mes' => $this->mes,
            // 'fecha_exportacion' => $this->fecha_exportacion
        ]);
    }

    public static function afterSheet(afterSheet $event)
    {
        // $num_rows = $event->getConcernable()->contador;
        $num_rows=0;

        $title_font_style = [
            'font' => [
                'name' => 'Arial',
                'bold' => true,
            ],
        ];

        $heads_font_style = [
            'font' => [
                'name' => 'Arial',
                'bold' => true,
            ],
        ];

        // // Get Worksheet
        $active_sheet = $event->sheet->getDelegate();

        // // $active_sheet->mergeCells('B2:D2');

        // // Tittle
        // $active_sheet->mergeCells('A1:I1');
        // $active_sheet->getStyle('A1:I1')->applyFromArray($title_font_style);
        // $active_sheet->getStyle('A1:Y1')->getAlignment()->setHorizontal('center');
        // // Tittle
        // $active_sheet->mergeCells('A2:I2');
        // $active_sheet->getStyle('A2:I2')->applyFromArray($title_font_style);
        // $active_sheet->getStyle('A2:I2')->getAlignment()->setHorizontal('center'); 

        // Columns title
        $active_sheet->getStyle('A1:W1')->applyFromArray($heads_font_style);
        // $active_sheet->getStyle('A3:W3')->getAlignment()->setHorizontal('center');
        // Columns title
    }


}

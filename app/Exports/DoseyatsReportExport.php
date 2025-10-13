<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DoseyatsReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $doseyats;

    public function __construct($doseyats)
    {
        $this->doseyats = $doseyats;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->doseyats;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('messages.id'),
            __('messages.name'),
            __('messages.pos'),
            __('messages.category'),
            __('messages.teacher'),
            __('messages.price'),
            __('messages.associated_cards'),
            __('messages.cards_names'),
            __('messages.created_at'),
        ];
    }

    /**
     * @param mixed $doseyat
     * @return array
     */
    public function map($doseyat): array
    {
        return [
            $doseyat->id,
            $doseyat->name,
            $doseyat->pos ? $doseyat->pos->name : '-',
            $doseyat->category ? $doseyat->category->name_ar : '-',
            $doseyat->teacher ? $doseyat->teacher->name : '-',
            number_format($doseyat->price, 2),
            $doseyat->cards->count(),
            $doseyat->cards->count() > 0 ? $doseyat->cards->pluck('name')->implode(', ') : '-',
            $doseyat->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('messages.doseyat_reports');
    }
}
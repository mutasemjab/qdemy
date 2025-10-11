<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CardsReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $cards;

    public function __construct($cards)
    {
        $this->cards = $cards;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->cards;
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
            __('messages.number_of_cards'),
            __('messages.total_card_numbers'),
            __('messages.active_numbers'),
            __('messages.inactive_numbers'),
            __('messages.available_numbers'),
            __('messages.sold_numbers'),
            __('messages.used_numbers'),
            __('messages.unused_numbers'),
            __('messages.doseyats'),
            __('messages.created_at'),
        ];
    }

    /**
     * @param mixed $card
     * @return array
     */
    public function map($card): array
    {
        return [
            $card->id,
            $card->name,
            $card->pos ? $card->pos->name : '-',
            $card->category ? $card->category->name : '-',
            $card->teacher ? $card->teacher->name : '-',
            number_format($card->price, 2),
            $card->number_of_cards,
            $card->cardNumbers->count(),
            $card->cardNumbers->where('activate', 1)->count(),
            $card->cardNumbers->where('activate', 0)->count(),
            $card->cardNumbers->where('sell', 0)->where('activate', 1)->where('status', 0)->count(),
            $card->cardNumbers->where('sell', 1)->count(),
            $card->cardNumbers->where('status', 1)->count(),
            $card->cardNumbers->where('status', 0)->count(),
            $card->doseyats->count() > 0 ? $card->doseyats->pluck('name')->implode(', ') : '-',
            $card->created_at->format('Y-m-d H:i:s'),
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
        return __('messages.card_reports');
    }
}
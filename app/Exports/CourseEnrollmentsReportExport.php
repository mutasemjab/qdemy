<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CourseEnrollmentsReportExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    WithTitle, 
    ShouldAutoSize,
    WithEvents
{
    protected $enrollments;
    protected $rowNumber = 1;

    public function __construct($enrollments)
    {
        $this->enrollments = $enrollments;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->enrollments;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('messages.id'),
            __('messages.student_name'),
            __('messages.email'),
            __('messages.phone'),
            __('messages.course_title'),
            __('messages.teacher'),
            __('messages.subject'),
            __('messages.enrollment_date'),
            __('messages.status'),
        ];
    }

    /**
     * @param mixed $enrollment
     * @return array
     */
    public function map($enrollment): array
    {
        $this->rowNumber++;
        
        return [
            $enrollment->id,
            $enrollment->user->name ?? '',
            $enrollment->user->email ?? '',
            $enrollment->user->phone ?? '',
            app()->getLocale() == 'ar' 
                ? ($enrollment->course->title_ar ?? '') 
                : ($enrollment->course->title_en ?? ''),
            $enrollment->course->teacher->name ?? '',
            app()->getLocale() == 'ar' 
                ? ($enrollment->course->subject->name_ar ?? '') 
                : ($enrollment->course->subject->name_en ?? ''),
            $enrollment->created_at->format('Y-m-d'),
            $enrollment->course->is_active 
                ? __('messages.active') 
                : __('messages.inactive'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '4472C4',
                    ],
                ],
                'font' => [
                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],
                    'bold' => true,
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('messages.course_enrollment_reports');
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Get the highest row and column
                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();
                
                // Apply borders to all cells
                $event->sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
                
                // Center align all data cells
                $event->sheet->getStyle('A2:' . $highestColumn . $highestRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Set row height for header
                $event->sheet->getRowDimension(1)->setRowHeight(25);
                
                // Wrap text for all cells
                $event->sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getAlignment()
                    ->setWrapText(true);
                
                // If Arabic, set RTL
                if (app()->getLocale() == 'ar') {
                    $event->sheet->setRightToLeft(true);
                }
                
                // Add statistics at the bottom
                $this->addStatistics($event, $highestRow);
            },
        ];
    }

    /**
     * Add statistics summary at the bottom of the sheet
     */
    protected function addStatistics($event, $lastRow)
    {
        $startRow = $lastRow + 3;
        
        // Add statistics title
        $event->sheet->setCellValue('A' . $startRow, __('messages.statistics'));
        $event->sheet->getStyle('A' . $startRow)
            ->getFont()
            ->setBold(true)
            ->setSize(14);
        
        $startRow++;
        
        // Calculate statistics
        $totalEnrollments = $this->enrollments->count();
        $uniqueStudents = $this->enrollments->pluck('user_id')->unique()->count();
        $uniqueCourses = $this->enrollments->pluck('course_id')->unique()->count();
        
        // Add statistics data
        $statistics = [
            [__('messages.total_enrollments'), $totalEnrollments],
            [__('messages.unique_students'), $uniqueStudents],
            [__('messages.unique_courses'), $uniqueCourses],
            [__('messages.average_enrollment_per_course'), 
                $uniqueCourses > 0 ? round($totalEnrollments / $uniqueCourses, 2) : 0],
        ];
        
        foreach ($statistics as $index => $stat) {
            $currentRow = $startRow + $index;
            $event->sheet->setCellValue('A' . $currentRow, $stat[0]);
            $event->sheet->setCellValue('B' . $currentRow, $stat[1]);
            
            // Style statistics
            $event->sheet->getStyle('A' . $currentRow . ':B' . $currentRow)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('E7E6E6');
            
            $event->sheet->getStyle('A' . $currentRow)
                ->getFont()
                ->setBold(true);
            
            $event->sheet->getStyle('A' . $currentRow . ':B' . $currentRow)
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
        }
        
        // Add export date
        $exportDateRow = $startRow + count($statistics) + 2;
        $event->sheet->setCellValue('A' . $exportDateRow, 
            __('messages.export_date') . ': ' . now()->format('Y-m-d H:i:s')
        );
        $event->sheet->getStyle('A' . $exportDateRow)
            ->getFont()
            ->setItalic(true)
            ->setSize(10);
    }
}
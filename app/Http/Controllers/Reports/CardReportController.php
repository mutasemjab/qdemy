<?php

namespace  App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardNumber;
use App\Models\POS;
use App\Models\Category;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CardsReportExport;
use Barryvdh\DomPDF\Facade\Pdf;

class CardReportController extends Controller
{
    /**
     * Display card reports with filters
     */
    public function index(Request $request)
    {
        $query = Card::with(['pos', 'category', 'teacher', 'cardNumbers', 'doseyats']);

        // Filter by POS
        if ($request->filled('pos_id')) {
            $query->where('pos_id', $request->pos_id);
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by Teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by Date Range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Filter by Doseyat Status
        if ($request->filled('doseyat_status')) {
            switch ($request->doseyat_status) {
                case 'has_doseyats':
                    $query->has('doseyats');
                    break;
                case 'no_doseyats':
                    $query->doesntHave('doseyats');
                    break;
            }
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $cards = $query->latest()->paginate(20);

        // Calculate statistics based on filters
        $filteredQuery = clone $query;
        $statistics = $this->calculateStatistics($filteredQuery);

        // Get filter options
        $posRecords = POS::orderBy('name')->get();
        $categories = Category::where('parent_id', null)->get();
        $teachers = Teacher::orderBy('name')->get();

        return view('admin.reports.cards.index', compact(
            'cards', 
            'statistics', 
            'posRecords', 
            'categories', 
            'teachers'
        ));
    }

    /**
     * Calculate statistics for cards
     */
    private function calculateStatistics($query)
    {
        // Get card IDs from filtered query
        $cardIds = $query->pluck('id');

        return [
            'total_cards' => $query->count(),
            'total_card_numbers' => CardNumber::whereIn('card_id', $cardIds)->count(),
            'active_card_numbers' => CardNumber::whereIn('card_id', $cardIds)
                ->where('activate', CardNumber::ACTIVATE_ACTIVE)
                ->count(),
            'inactive_card_numbers' => CardNumber::whereIn('card_id', $cardIds)
                ->where('activate', CardNumber::ACTIVATE_INACTIVE)
                ->count(),
            'used_card_numbers' => CardNumber::whereIn('card_id', $cardIds)
                ->where('status', CardNumber::STATUS_USED)
                ->count(),
            'unused_card_numbers' => CardNumber::whereIn('card_id', $cardIds)
                ->where('status', CardNumber::STATUS_NOT_USED)
                ->count(),
            'sold_card_numbers' => CardNumber::whereIn('card_id', $cardIds)
                ->where('sell', CardNumber::SELL_SOLD)
                ->count(),
            'not_sold_card_numbers' => CardNumber::whereIn('card_id', $cardIds)
                ->where('sell', CardNumber::SELL_NOT_SOLD)
                ->count(),
            'available_card_numbers' => CardNumber::whereIn('card_id', $cardIds)
                ->where('sell', CardNumber::SELL_NOT_SOLD)
                ->where('activate', CardNumber::ACTIVATE_ACTIVE)
                ->where('status', CardNumber::STATUS_NOT_USED)
                ->count(),
            'total_revenue' => Card::whereIn('id', $cardIds)->sum('price'),
            'cards_with_doseyats' => Card::whereIn('id', $cardIds)->has('doseyats')->count(),
            'cards_without_doseyats' => Card::whereIn('id', $cardIds)->doesntHave('doseyats')->count(),
        ];
    }

    /**
     * Export card reports to Excel
     */
    public function exportExcel(Request $request)
    {
        $cards = $this->getFilteredCards($request)->get();
        
        return Excel::download(
            new CardsReportExport($cards), 
            'cards-report-' . now()->format('Y-m-d-H-i-s') . '.xlsx'
        );
    }

    

    /**
     * Print card reports
     */
    public function print(Request $request)
    {
        $cards = $this->getFilteredCards($request)->get();
        $statistics = $this->calculateStatistics($this->getFilteredCards($request));
        
        return view('admin.reports.cards.print', compact('cards', 'statistics'));
    }

    /**
     * Get filtered cards query
     */
    private function getFilteredCards(Request $request)
    {
        $query = Card::with(['pos', 'category', 'teacher', 'cardNumbers', 'doseyats']);

        if ($request->filled('pos_id')) {
            $query->where('pos_id', $request->pos_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('doseyat_status')) {
            switch ($request->doseyat_status) {
                case 'has_doseyats':
                    $query->has('doseyats');
                    break;
                case 'no_doseyats':
                    $query->doesntHave('doseyats');
                    break;
            }
        }

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        return $query->latest();
    }

  
}
<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Doseyat;
use App\Models\POS;
use App\Models\Category;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DoseyatsReportExport;

class DoseyatReportController extends Controller
{
    /**
     * Display doseyat reports with filters
     */
    public function index(Request $request)
    {
        $query = Doseyat::with(['pos', 'category', 'teacher', 'cards']);

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

        // Filter by Card Association Status
        if ($request->filled('card_status')) {
            switch ($request->card_status) {
                case 'has_cards':
                    $query->has('cards');
                    break;
                case 'no_cards':
                    $query->doesntHave('cards');
                    break;
            }
        }

        // Filter by Price Range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $doseyats = $query->latest()->paginate(20);

        // Calculate statistics based on filters
        $filteredQuery = clone $query;
        $statistics = $this->calculateStatistics($filteredQuery);

        // Get filter options
        $posRecords = POS::orderBy('name')->get();
        $categories = Category::where('parent_id', null)->get();
        $teachers = Teacher::orderBy('name')->get();

        return view('admin.reports.doseyats.index', compact(
            'doseyats', 
            'statistics', 
            'posRecords', 
            'categories', 
            'teachers'
        ));
    }

    /**
     * Calculate statistics for doseyats
     */
    private function calculateStatistics($query)
    {
        $doseyatIds = $query->pluck('id');
        $doseyats = Doseyat::whereIn('id', $doseyatIds)->with('cards')->get();

        return [
            'total_doseyats' => $query->count(),
            'total_cards_associated' => $doseyats->sum(function($d) { 
                return $d->cards->count(); 
            }),
            'doseyats_with_cards' => Doseyat::whereIn('id', $doseyatIds)->has('cards')->count(),
            'doseyats_without_cards' => Doseyat::whereIn('id', $doseyatIds)->doesntHave('cards')->count(),
            'total_price' => Doseyat::whereIn('id', $doseyatIds)->sum('price'),
            'average_price' => Doseyat::whereIn('id', $doseyatIds)->avg('price'),
            'min_price' => Doseyat::whereIn('id', $doseyatIds)->min('price'),
            'max_price' => Doseyat::whereIn('id', $doseyatIds)->max('price'),
        ];
    }

    /**
     * Export doseyat reports to Excel
     */
    public function exportExcel(Request $request)
    {
        $doseyats = $this->getFilteredDoseyats($request)->get();
        
        return Excel::download(
            new DoseyatsReportExport($doseyats), 
            'doseyats-report-' . now()->format('Y-m-d-H-i-s') . '.xlsx'
        );
    }

    /**
     * Print doseyat reports
     */
    public function print(Request $request)
    {
        $doseyats = $this->getFilteredDoseyats($request)->get();
        $statistics = $this->calculateStatistics($this->getFilteredDoseyats($request));
        
        return view('admin.reports.doseyats.print', compact('doseyats', 'statistics'));
    }

    /**
     * Get filtered doseyats query
     */
    private function getFilteredDoseyats(Request $request)
    {
        $query = Doseyat::with(['pos', 'category', 'teacher', 'cards']);

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

        if ($request->filled('card_status')) {
            switch ($request->card_status) {
                case 'has_cards':
                    $query->has('cards');
                    break;
                case 'no_cards':
                    $query->doesntHave('cards');
                    break;
            }
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        return $query->latest();
    }

    /**
     * Show detailed doseyat report
     */
    public function show(Doseyat $doseyat)
    {
        $doseyat->load(['pos', 'category', 'teacher', 'cards.cardNumbers']);
        
        return view('admin.reports.doseyats.show', compact('doseyat'));
    }
}
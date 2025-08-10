<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\QuestionWebsite;

class QuestionWebsiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:questionWebsite-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:questionWebsite-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:questionWebsite-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:questionWebsite-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = QuestionWebsite::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        $questions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.questionWebsites.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.questionWebsites.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        QuestionWebsite::create($request->all());

        return redirect()->route('questionWebsites.index')
            ->with('success', __('messages.FAQ created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(QuestionWebsite $question)
    {
        return view('admin.questionWebsites.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuestionWebsite $question)
    {
        return view('admin.questionWebsites.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuestionWebsite $question)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $question->update($request->all());

        return redirect()->route('questionWebsites.index')
            ->with('success', __('messages.FAQ updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionWebsite $question)
    {
        $question->delete();

        return redirect()->route('questionWebsites.index')
            ->with('success', __('messages.FAQ deleted successfully'));
    }
}

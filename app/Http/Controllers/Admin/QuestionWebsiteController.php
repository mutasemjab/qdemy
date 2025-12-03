<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\QuestionWebsite;
use Illuminate\Http\Request;

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
                $q->where('question_en', 'like', "%{$search}%")
                  ->orWhere('question_ar', 'like', "%{$search}%")
                  ->orWhere('answer_en', 'like', "%{$search}%")
                  ->orWhere('answer_ar', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
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
            'question_en' => 'required|string|max:255',
            'question_ar' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_ar' => 'required|string',
            'type' => 'required|in:all,register,payment,card,courses,technical,privacy,account',
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
            'question_en' => 'required|string|max:255',
            'question_ar' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_ar' => 'required|string',
            'type' => 'required|in:all,register,payment,card,courses,technical,privacy,account',
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

    /**
     * Get the FAQ type options
     */
    private function getTypeOptions()
    {
        return [
            'all' => __('messages.All Categories'),
            'register' => __('messages.Registration'),
            'payment' => __('messages.Payment'),
            'card' => __('messages.Card'),
            'courses' => __('messages.Courses'),
            'technical' => __('messages.Technical'),
            'privacy' => __('messages.Privacy'),
            'account' => __('messages.Account'),
        ];
    }
}
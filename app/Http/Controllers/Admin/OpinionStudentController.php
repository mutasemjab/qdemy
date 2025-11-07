<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\OpinionStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OpinionStudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:opinion-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:opinion-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:opinion-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:opinion-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = OpinionStudent::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $rating = $request->rating;
            if ($rating == '5') {
                $query->where('number_of_star', '>=', 4.5);
            } elseif ($rating == '4') {
                $query->whereBetween('number_of_star', [3.5, 4.4]);
            } elseif ($rating == '3') {
                $query->whereBetween('number_of_star', [2.5, 3.4]);
            } elseif ($rating == '2') {
                $query->whereBetween('number_of_star', [1.5, 2.4]);
            } elseif ($rating == '1') {
                $query->where('number_of_star', '<', 1.5);
            }
        }

        $opinions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.opinions.index', compact('opinions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.opinions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'number_of_star' => 'required|numeric|min:0|max:5',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        OpinionStudent::create($data);

        return redirect()->route('opinions.index')
            ->with('success', __('messages.Student opinion created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(OpinionStudent $opinion)
    {
        return view('admin.opinions.show', compact('opinion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OpinionStudent $opinion)
    {
        return view('admin.opinions.edit', compact('opinion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OpinionStudent $opinion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'number_of_star' => 'required|numeric|min:0|max:5',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
           if ($opinion->photo) {
                $filePath = base_path('assets/admin/uploads/' . $opinion->photo);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $data['photo'] =uploadImage('assets/admin/uploads', $request->photo);
        } else {
            // Keep the current photo
            unset($data['photo']);
        }

        $opinion->update($data);

        return redirect()->route('opinions.index')
            ->with('success', __('messages.Student opinion updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OpinionStudent $opinion)
    {

        $opinion->delete();

        return redirect()->route('opinions.index')
            ->with('success', __('messages.Student opinion deleted successfully'));
    }
}
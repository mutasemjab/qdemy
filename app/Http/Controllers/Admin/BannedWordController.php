<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannedWord;
use Illuminate\Http\Request;

class BannedWordController extends Controller
{
    public function index()
    {
        $words = BannedWord::orderBy('severity', 'desc')->paginate(50);
        return view('admin.banned-words.index', compact('words'));
    }
    
    public function create()
    {
        return view('admin.banned-words.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'word' => 'required|string|unique:banned_words,word',
            'language' => 'required|in:ar,en,both',
            'type' => 'required',
            'severity' => 'required|integer|min:1|max:10',
        ]);
        
        BannedWord::create($request->all());
        
        return back()->with('success', 'تم إضافة الكلمة بنجاح');
    }
    
    public function destroy(BannedWord $word)
    {
        $word->delete();
        return back()->with('success', 'تم حذف الكلمة');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Illuminate\Routing\Controller as BaseController;

class LetterInController extends BaseController
{
    /**
     * Constructor to apply permission middleware.
     */
    public function __construct()
    {
        $this->middleware('permission:surat.in.view')->only(['index', 'show']);
        $this->middleware('permission:surat.in.create')->only(['create', 'store']);
    }

    public function index()
    {
        $letters = Letter::where('type', 'incoming')
            ->with('creator')
            ->latest()
            ->paginate(10);

        return view('admin.letters.in.index', compact('letters'));
    }

    public function create()
    {
        return view('admin.letters.in.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => 'required|string|max:255',
            'letter_date' => 'required|date',
            'sender' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('letters/incoming', 'public');
        }

        $letter = Letter::create([
            'type' => 'incoming',
            'reference_number' => $validated['reference_number'],
            'letter_date' => $validated['letter_date'],
            'sender' => $validated['sender'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'created_by' => Auth::id(),
            'status' => 'received'
        ]);

        // Log
        \App\Models\LetterActivityLog::create([
            'letter_id' => $letter->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'details' => 'Surat masuk dicatat dari ' . $validated['sender']
        ]);

        return redirect()->route('admin.letters.in.index')->with('success', 'Surat masuk berhasil dicatat');
    }

    public function show(Letter $letter)
    {
        if ($letter->type !== 'incoming') abort(404);
        return view('admin.letters.in.show', compact('letter'));
    }
}

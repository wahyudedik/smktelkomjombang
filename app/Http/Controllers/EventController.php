<?php

namespace App\Http\Controllers;

use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        $query = Events::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $events = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();
        $categories = Events::distinct()->pluck('category')->filter();
        $statuses = ['active', 'inactive', 'archived'];

        return view('events.index', compact('events', 'categories', 'statuses'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $categories = Events::distinct()->pluck('category')->filter();
        return view('events.create', compact('categories'));
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'category'    => 'nullable|string|max:100',
            'status'      => 'required|in:active,inactive,archived',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'date', 'category', 'status']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        Events::create($data);

        Cache::forget('telkom_events');

        return redirect()->route('admin.events.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified event.
     */
    public function show(Events $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing an event.
     */
    public function edit(Events $event)
    {
        $categories = Events::distinct()->pluck('category')->filter();
        return view('events.edit', compact('event', 'categories'));
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Events $event)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'category'    => 'nullable|string|max:100',
            'status'      => 'required|in:active,inactive,archived',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'date', 'category', 'status']);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        Cache::forget('telkom_events');

        return redirect()->route('admin.events.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified event.
     */
    public function destroy(Events $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        Cache::forget('telkom_events');

        return redirect()->route('admin.events.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}

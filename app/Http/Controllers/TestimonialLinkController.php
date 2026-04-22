<?php

namespace App\Http\Controllers;

use App\Models\TestimonialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestimonialLinkController extends Controller
{
    // Admin: List semua testimonial links
    public function index()
    {
        $links = TestimonialLink::latest()->paginate(20);
        return view('admin.testimonial-links.index', compact('links'));
    }

    // Admin: Show create form
    public function create()
    {
        return view('admin.testimonial-links.create');
    }

    // Admin: Store new testimonial link
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_audience' => 'required|array|min:1',
            'target_audience.*' => 'in:Siswa,Guru,Alumni',
            'active_from' => 'required|date|after_or_equal:now',
            'active_until' => 'required|date|after:active_from',
            'max_submissions' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['token'] = TestimonialLink::generateToken();
        $data['created_by'] = auth()->user()->name;

        TestimonialLink::create($data);

        return redirect()->route('admin.testimonial-links.index')
            ->with('success', 'Testimonial link berhasil dibuat!');
    }

    // Admin: Show edit form
    public function edit(TestimonialLink $testimonialLink)
    {
        return view('admin.testimonial-links.edit', compact('testimonialLink'));
    }

    // Admin: Update testimonial link
    public function update(Request $request, TestimonialLink $testimonialLink)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_audience' => 'required|array|min:1',
            'target_audience.*' => 'in:Siswa,Guru,Alumni',
            'active_from' => 'required|date',
            'active_until' => 'required|date|after:active_from',
            'max_submissions' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $testimonialLink->update($request->all());

        return redirect()->route('admin.testimonial-links.index')
            ->with('success', 'Testimonial link berhasil diupdate!');
    }

    // Admin: Toggle active status
    public function toggleActive(TestimonialLink $testimonialLink)
    {
        $testimonialLink->update(['is_active' => !$testimonialLink->is_active]);
        
        $status = $testimonialLink->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Testimonial link berhasil {$status}.");
    }

    // Admin: Delete testimonial link
    public function destroy(TestimonialLink $testimonialLink)
    {
        $testimonialLink->delete();
        return redirect()->back()->with('success', 'Testimonial link berhasil dihapus.');
    }

    // Public: Show testimonial form with token
    public function showPublic(Request $request, $token)
    {
        $link = TestimonialLink::where('token', $token)->firstOrFail();

        // Check if link is active
        if (!$link->isCurrentlyActive()) {
            return view('testimonials.expired', compact('link'));
        }

        // Check if max submissions reached
        if ($link->hasReachedMaxSubmissions()) {
            return view('testimonials.limit-reached', compact('link'));
        }

        return view('testimonials.create', compact('link'));
    }

    // Public: Store testimonial from public link
    public function storePublic(Request $request, $token)
    {
        $link = TestimonialLink::where('token', $token)->firstOrFail();

        // Check if link is active
        if (!$link->isCurrentlyActive()) {
            return redirect()->back()->with('error', 'Link testimonial sudah tidak aktif.');
        }

        // Check if max submissions reached
        if ($link->hasReachedMaxSubmissions()) {
            return redirect()->back()->with('error', 'Kuota testimonial sudah penuh.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'position' => 'required|string|in:Siswa,Guru,Alumni',
            'class' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|string|max:4',
            'testimonial' => 'required|string|min:50|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if user can submit for this position
        if (!$link->canSubmitForPosition($request->position)) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengisi testimonial untuk posisi ini.');
        }

        $data = $request->all();
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = $request->userAgent();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('testimonials', 'public');
        }

        \App\Models\Testimonial::create($data);
        $link->incrementSubmissions();

        return redirect()->back()->with('success', 'Terima kasih! Testimonial Anda telah dikirim dan akan ditinjau oleh admin.');
    }
}

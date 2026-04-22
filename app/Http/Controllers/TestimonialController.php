<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    // Public form untuk submit testimonial (tanpa login)
    public function create()
    {
        return view('testimonials.create');
    }

    // Store testimonial dari public form
    public function store(Request $request)
    {
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

        $data = $request->all();
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = $request->userAgent();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('testimonials', 'public');
        }

        Testimonial::create($data);

        return redirect()->back()->with('success', 'Terima kasih! Testimonial Anda telah dikirim dan akan ditinjau oleh admin.');
    }

    // Admin: List semua testimonial
    public function index(Request $request)
    {
        $query = Testimonial::query();

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'approved':
                    $query->where('is_approved', true);
                    break;
                case 'pending':
                    $query->where('is_approved', false);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
            }
        }

        // Filter by position
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Search by name or testimonial
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('testimonial', 'like', "%{$search}%");
            });
        }

        $testimonials = $query->latest()->paginate(20);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    // Admin: Show testimonial details
    public function show(Testimonial $testimonial)
    {
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'testimonial' => $testimonial
            ]);
        }

        return view('admin.testimonials.show', compact('testimonial'));
    }

    // Admin: Approve testimonial
    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Testimonial telah disetujui.');
    }

    // Admin: Reject testimonial
    public function reject(Testimonial $testimonial)
    {
        $testimonial->update(['is_approved' => false]);
        return redirect()->back()->with('success', 'Testimonial telah ditolak.');
    }

    // Admin: Toggle featured status
    public function toggleFeatured(Testimonial $testimonial)
    {
        $testimonial->update(['is_featured' => !$testimonial->is_featured]);
        return redirect()->back()->with('success', 'Status featured telah diubah.');
    }

    // Admin: Delete testimonial
    public function destroy(Testimonial $testimonial)
    {
        // Delete photo if exists
        if ($testimonial->photo && Storage::disk('public')->exists($testimonial->photo)) {
            Storage::disk('public')->delete($testimonial->photo);
        }

        $testimonial->delete();
        return redirect()->back()->with('success', 'Testimonial telah dihapus.');
    }
}

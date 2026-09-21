<?php

namespace App\Http\Controllers;

use App\Exports\GuestBookExport;
use App\Models\GuestBook;
use App\Services\ContentSanitizer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class GuestBookController extends Controller
{
    /**
     * Constructor — permission middleware.
     */
    public function __construct()
    {
        $this->middleware('permission:buku-tamu.view', ['only' => ['index', 'show', 'export', 'printTicket']]);
        $this->middleware('permission:buku-tamu.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:buku-tamu.update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:buku-tamu.delete', ['only' => ['destroy']]);
        $this->middleware('permission:buku-tamu.checkout', ['only' => ['checkout']]);
    }

    /**
     * Display a listing of the guest book entries.
     */
    public function index(Request $request)
    {
        $query = GuestBook::with('creator');

        // Search by guest_name, ticket_number, organization, phone
        if ($request->filled('search')) {
            $search = trim($request->search);
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('guest_name', 'like', '%' . $search . '%')
                        ->orWhere('ticket_number', 'like', '%' . $search . '%')
                        ->orWhere('organization', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by visit_category
        if ($request->filled('visit_category')) {
            $query->where('visit_category', $request->visit_category);
        }

        // Filter by date range (check_in_at)
        if ($request->filled('date_from')) {
            $query->whereDate('check_in_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('check_in_at', '<=', $request->date_to);
        }

        // Default sort: check_in_at DESC
        $query->orderBy('check_in_at', 'desc');

        $guests = $query->paginate(15)->withQueryString();

        // Stats
        $stats = [
            'total_today'      => GuestBook::today()->count(),
            'active_checkin'   => GuestBook::active()->count(),
            'total_all'        => GuestBook::withTrashed()->count(),
        ];

        $visitCategories = [
            'ppdb'       => 'PPDB',
            'ortu_wali'  => 'Orang Tua / Wali',
            'konsultasi' => 'Konsultasi',
            'dinas'      => 'Dinas',
            'supplier'   => 'Supplier',
            'acara'      => 'Acara',
            'lainnya'    => 'Lainnya',
        ];

        $statuses = [
            'check_in'    => 'Check In',
            'check_out'   => 'Check Out',
            'dibatalkan'  => 'Dibatalkan',
        ];

        return view('guest-book.index', compact('guests', 'stats', 'visitCategories', 'statuses'));
    }

    /**
     * Show the form for creating a new guest book entry (check-in).
     */
    public function create()
    {
        $visitCategories = [
            'ppdb'       => 'PPDB',
            'ortu_wali'  => 'Orang Tua / Wali',
            'konsultasi' => 'Konsultasi',
            'dinas'      => 'Dinas',
            'supplier'   => 'Supplier',
            'acara'      => 'Acara',
            'lainnya'    => 'Lainnya',
        ];

        return view('guest-book.create', compact('visitCategories'));
    }

    /**
     * Store a newly created guest book entry (check-in).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_name'     => 'required|string|max:255',
            'nik'            => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'organization'   => 'required|string|max:255',
            'position'       => 'nullable|string|max:255',
            'visit_category' => 'required|string|in:ppdb,ortu_wali,konsultasi,dinas,supplier,acara,lainnya',
            'visit_purpose'  => 'required|string',
            'visit_target'   => 'nullable|string|max:255',
            'photo'          => 'nullable|image|max:2048',
            'signature'      => 'nullable|string',
            'vehicle_type'   => 'nullable|string|max:50',
            'vehicle_plate'  => 'nullable|string|max:20',
            'notes'          => 'nullable|string',
        ]);

        // Sanitize text fields
        $validated['visit_purpose'] = app(ContentSanitizer::class)->sanitize($validated['visit_purpose'] ?? '');
        $validated['notes'] = app(ContentSanitizer::class)->sanitize($validated['notes'] ?? '');
        $validated['address'] = app(ContentSanitizer::class)->sanitize($validated['address'] ?? '');

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('guest-photos', 'public');
        }

        // Remove non-database keys
        unset($validated['photo'], $validated['signature']);

        // Set creator
        $validated['created_by'] = auth()->id();

        // ticket_number and check_in_at are auto-generated by model boot
        $guest = GuestBook::create($validated);

        // Process signature (base64 image)
        if (!empty($request->signature)) {
            $signatureData = $request->signature;
            $imageData = explode(',', $signatureData)[1] ?? '';
            $imageBinary = base64_decode($imageData);

            if ($imageBinary) {
                $signaturePath = 'guest-books/signatures/' . $guest->ticket_number . '_signature.png';
                Storage::disk('public')->put($signaturePath, $imageBinary);
                $guest->update(['signature_path' => 'storage/' . $signaturePath]);
            }
        }

        return redirect()->route('admin.buku-tamu.index')
            ->with('success', 'Check-in tamu berhasil. Tiket telah diterbitkan.');
    }

    /**
     * Display the specified guest book entry.
     */
    public function show(GuestBook $guest)
    {
        $guest->load('creator');

        return view('guest-book.show', compact('guest'));
    }

    /**
     * Show the form for editing the specified guest book entry.
     */
    public function edit(GuestBook $guest)
    {
        $visitCategories = [
            'ppdb'       => 'PPDB',
            'ortu_wali'  => 'Orang Tua / Wali',
            'konsultasi' => 'Konsultasi',
            'dinas'      => 'Dinas',
            'supplier'   => 'Supplier',
            'acara'      => 'Acara',
            'lainnya'    => 'Lainnya',
        ];

        return view('guest-book.edit', compact('guest', 'visitCategories'));
    }

    /**
     * Update the specified guest book entry.
     */
    public function update(Request $request, GuestBook $guest)
    {
        $validated = $request->validate([
            'guest_name'     => 'required|string|max:255',
            'nik'            => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'organization'   => 'required|string|max:255',
            'position'       => 'nullable|string|max:255',
            'visit_category' => 'required|string|in:ppdb,ortu_wali,konsultasi,dinas,supplier,acara,lainnya',
            'visit_purpose'  => 'required|string',
            'visit_target'   => 'nullable|string|max:255',
            'photo'          => 'nullable|image|max:2048',
            'signature'      => 'nullable|string',
            'vehicle_type'   => 'nullable|string|max:50',
            'vehicle_plate'  => 'nullable|string|max:20',
            'status'         => 'nullable|string|in:check_in,check_out,dibatalkan',
            'notes'          => 'nullable|string',
        ]);

        // Sanitize text fields
        $validated['visit_purpose'] = app(ContentSanitizer::class)->sanitize($validated['visit_purpose'] ?? '');
        $validated['notes'] = app(ContentSanitizer::class)->sanitize($validated['notes'] ?? '');
        $validated['address'] = app(ContentSanitizer::class)->sanitize($validated['address'] ?? '');

        // Handle photo upload (new photo replaces old one)
        if ($request->hasFile('photo')) {
            // Delete old photo from storage
            if ($guest->photo_path) {
                Storage::disk('public')->delete($guest->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('guest-photos', 'public');
        }

        // Remove non-database keys
        unset($validated['photo'], $validated['signature']);

        // ticket_number is NOT editable
        $guest->update($validated);

        // Process signature (base64 image) — new signature replaces old one
        if (!empty($request->signature)) {
            // Delete old signature if exists
            if ($guest->signature_path) {
                $oldSignaturePath = str_replace('storage/', '', $guest->signature_path);
                Storage::disk('public')->delete($oldSignaturePath);
            }

            $signatureData = $request->signature;
            $imageData = explode(',', $signatureData)[1] ?? '';
            $imageBinary = base64_decode($imageData);

            if ($imageBinary) {
                $signaturePath = 'guest-books/signatures/' . $guest->ticket_number . '_signature.png';
                Storage::disk('public')->put($signaturePath, $imageBinary);
                $guest->update(['signature_path' => 'storage/' . $signaturePath]);
            }
        }

        return redirect()->route('admin.buku-tamu.show', $guest)
            ->with('success', 'Data tamu berhasil diperbarui.');
    }

    /**
     * Remove the specified guest book entry (hard delete).
     */
    public function destroy(GuestBook $guest)
    {
        // Delete photo from storage
        if ($guest->photo_path) {
            Storage::disk('public')->delete($guest->photo_path);
        }

        // Hard delete (bypass soft delete)
        $guest->forceDelete();

        return redirect()->route('admin.buku-tamu.index')
            ->with('success', 'Data tamu berhasil dihapus permanen.');
    }

    /**
     * Check-out a guest.
     */
    public function checkout(GuestBook $guest)
    {
        $guest->update([
            'status'       => 'check_out',
            'check_out_at' => now(),
        ]);

        return redirect()->route('admin.buku-tamu.show', $guest)
            ->with('success', 'Check-out tamu berhasil. Waktu check-out: ' . now()->format('d M Y H:i'));
    }

    /**
     * Print visitor ticket (tiket kunjungan).
     */
    public function printTicket(GuestBook $guest)
    {
        $guest->load('creator');

        return view('guest-book.print-ticket', compact('guest'));
    }

    /**
     * Export guest book data (Excel / PDF).
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'excel');

        $query = GuestBook::with('creator');

        // Apply same filters as index()
        if ($request->filled('search')) {
            $search = trim($request->search);
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('guest_name', 'like', '%' . $search . '%')
                        ->orWhere('ticket_number', 'like', '%' . $search . '%')
                        ->orWhere('organization', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('visit_category')) {
            $query->where('visit_category', $request->visit_category);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('check_in_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('check_in_at', '<=', $request->date_to);
        }

        $guests = $query->orderBy('check_in_at', 'desc')->get();

        $visitCategories = [
            'ppdb'       => 'PPDB',
            'ortu_wali'  => 'Orang Tua / Wali',
            'konsultasi' => 'Konsultasi',
            'dinas'      => 'Dinas',
            'supplier'   => 'Supplier',
            'acara'      => 'Acara',
            'lainnya'    => 'Lainnya',
        ];

        $filename = 'buku-tamu-' . now()->format('Y-m-d');

        if ($type === 'pdf') {
            // Build filters summary for PDF header
            $filterParts = [];
            if ($request->filled('search')) {
                $filterParts[] = 'Pencarian: ' . $request->search;
            }
            if ($request->filled('status')) {
                $filterParts[] = 'Status: ' . ($visitCategories[$request->status] ?? $request->status);
            }
            if ($request->filled('visit_category')) {
                $filterParts[] = 'Kategori: ' . ($visitCategories[$request->visit_category] ?? $request->visit_category);
            }
            if ($request->filled('date_from')) {
                $filterParts[] = 'Dari: ' . $request->date_from;
            }
            if ($request->filled('date_to')) {
                $filterParts[] = 'Sampai: ' . $request->date_to;
            }
            $filtersSummary = implode(' | ', $filterParts);

            $pdf = Pdf::loadView('guest-book.pdf', compact('guests', 'visitCategories', 'filtersSummary'));
            $pdf->setPaper('a4', 'landscape');

            return $pdf->download($filename . '.pdf');
        }

        // Default: Excel
        return Excel::download(new GuestBookExport($guests), $filename . '.xlsx');
    }

    // ========================================
    // PUBLIC METHODS (No Auth Required)
    // ========================================

    /**
     * Show public check-in form (no auth required).
     */
    public function publicForm(): \Illuminate\View\View
    {
        return view('guest-book.public-form');
    }

    /**
     * Store guest check-in from public form (self check-in).
     */
    public function publicStore(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'guest_name'      => 'required|string|max:255',
            'nik'             => 'nullable|string|max:20',
            'phone'           => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'organization'    => 'required|string|max:255',
            'position'        => 'nullable|string|max:255',
            'visit_category'  => 'required|string|in:ppdb,ortu_wali,konsultasi,dinas,supplier,acara,lainnya',
            'visit_purpose'   => 'required|string',
            'visit_target'    => 'nullable|string|max:255',
            'photo'           => 'nullable|string|max:10485760',
            'signature'       => 'nullable|string',
            'vehicle_type'    => 'nullable|string|max:50',
            'vehicle_plate'   => 'nullable|string|max:20',
            'notes'           => 'nullable|string',
        ]);

        // Sanitize text fields
        $validated['visit_purpose'] = app(ContentSanitizer::class)->sanitize($validated['visit_purpose'] ?? '');
        $validated['notes'] = app(ContentSanitizer::class)->sanitize($validated['notes'] ?? '');

        // Remove non-database keys
        unset($validated['photo']);
        unset($validated['signature']);

        // created_by = NULL (no auth — self check-in by guest)
        // status = 'check_in' (default from migration)
        // ticket_number & check_in_at auto-generated by model boot
        $guest = GuestBook::create($validated);

        // Process photo (base64 image, 4×6 format)
        if (!empty($request->photo)) {
            $photoData = $request->photo;
            $imageData = explode(',', $photoData)[1] ?? $photoData;
            $imageBinary = base64_decode($imageData);

            if ($imageBinary) {
                $photoPath = 'guest-photos/' . $guest->ticket_number . '_photo.jpg';
                Storage::disk('public')->put($photoPath, $imageBinary);
                $guest->update(['photo_path' => 'storage/' . $photoPath]);
            }
        }

        // Process signature (base64 image)
        if (!empty($request->signature)) {
            $signatureData = $request->signature;
            $imageData = explode(',', $signatureData)[1] ?? '';
            $imageBinary = base64_decode($imageData);

            if ($imageBinary) {
                $signaturePath = 'guest-books/signatures/' . $guest->ticket_number . '_signature.png';
                Storage::disk('public')->put($signaturePath, $imageBinary);
                $guest->update(['signature_path' => 'storage/' . $signaturePath]);
            }
        }

        return redirect()->route('guest-book.thank-you', $guest)
            ->with('success', 'Check-in berhasil! Silakan tunjukkan tiket ini kepada petugas.');
    }

    /**
     * Show thank you page after public check-in.
     */
    public function publicThankYou(GuestBook $guest): \Illuminate\View\View
    {
        return view('guest-book.thank-you', compact('guest'));
    }
}

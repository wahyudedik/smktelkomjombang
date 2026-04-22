<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\LetterFormat;
use App\Models\LetterCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Routing\Controller as BaseController;

class LetterOutController extends BaseController
{
    /**
     * Constructor to apply permission middleware.
     */
    public function __construct()
    {
        // Surat View Permissions
        $this->middleware('permission:surat.out.view')->only(['index', 'show']);

        // Surat Create/Upload Permissions
        $this->middleware('permission:surat.out.create')->only(['create', 'store']);
        $this->middleware('permission:surat.out.upload')->only(['upload', 'processUpload']);

        // Surat Print Permissions
        $this->middleware('permission:surat.out.print')->only(['print']);
    }

    public function index()
    {
        $letters = Letter::where('type', 'outgoing')
            ->with(['creator', 'format'])
            ->latest()
            ->paginate(10);

        return view('admin.letters.out.index', compact('letters'));
    }

    public function create()
    {
        $formats = LetterFormat::where('is_active', true)->get();
        return view('admin.letters.out.create', compact('formats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'letter_format_id' => 'required|exists:letter_formats,id',
            'letter_date' => 'required|date',
            'recipient' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // 0. BLOCKING LOGIC: Check if previous letter of this format/scope has a file uploaded
        $format = LetterFormat::findOrFail($validated['letter_format_id']);
        $user = Auth::user();

        $scopeUnitCode = null;
        if ($format->counter_scope === 'unit') {
            if (!$user->unit_code) {
                return back()->withErrors(['error' => "Format ini memerlukan Unit Code pada user anda."]);
            }
            $scopeUnitCode = $user->unit_code;
        }

        // Find the latest letter for this context to check if it is completed
        // Context matches the counter logic: Same Format + Same Year + Same Scope
        $year = date('Y', strtotime($validated['letter_date']));
        // Note: strict sequential check usually implies simply "the last letter created by this system for this scope"

        $lastLetterQuery = Letter::where('type', 'outgoing')
            ->where('letter_format_id', $format->id)
            ->whereYear('letter_date', $year); // Assuming sequence resets yearly or matches year context

        if ($scopeUnitCode) {
            // If unit scoped, we only care about letters from this unit? 
            // The counter is unit scoped, so the sequence is unit scoped. 
            // We should check the last letter generated for this unit.
            // Since we don't store unit_code on letter directly, we check creator's unit or infer from counter logic.
            // However, simpler is: if counter_scope is unit, we check letters created by users of that unit? 
            // Or more robustly, we rely on the sequence number.
            // Let's assume for now we check the latest letter created by THIS user (or users in same unit) for this format.
            $lastLetterQuery->whereHas('creator', function ($q) use ($scopeUnitCode) {
                $q->where('unit_code', $scopeUnitCode);
            });
        }

        $lastLetter = $lastLetterQuery->latest('sequence_number')->first();

        if ($lastLetter && !$lastLetter->file_path) {
            return back()->withErrors(['error' => "Surat terakhir (No: {$lastLetter->letter_number}) belum di-upload scan-nya. Harap selesaikan surat tersebut sebelum membuat nomor baru."]);
        }

        DB::transaction(function () use ($validated, $format, $user, $year, $scopeUnitCode) {
            // 1. Determine Counter
            $month = date('n', strtotime($validated['letter_date']));

            // Find or Create Counter
            $query = LetterCounter::where('letter_format_id', $format->id)
                ->where('year', $year)
                ->where('scope_unit_code', $scopeUnitCode);

            if ($format->period_mode === 'month') {
                $query->where('month', $month);
            }

            $counter = $query->lockForUpdate()->first();

            if (!$counter) {
                $counter = LetterCounter::create([
                    'letter_format_id' => $format->id,
                    'year' => $year,
                    'month' => $format->period_mode === 'month' ? $month : null,
                    'scope_unit_code' => $scopeUnitCode,
                    'current_value' => 0
                ]);
            }

            $sequence = $counter->current_value + 1;
            $counter->update(['current_value' => $sequence]);

            // 2. Generate Number using Segments
            $number = '';
            $segments = $format->segments()->orderBy('order')->get();

            foreach ($segments as $segment) {
                switch ($segment->type) {
                    case 'sequence':
                        $number .= str_pad($sequence, $segment->padding ?? 1, '0', STR_PAD_LEFT);
                        break;
                    case 'text':
                        $number .= $segment->value;
                        break;
                    case 'unit_code':
                        $number .= $user->unit_code ?? 'GEN';
                        break;
                    case 'month_roman':
                        $number .= $this->intToRoman($month);
                        break;
                    case 'month_number':
                        $number .= str_pad($month, 2, '0', STR_PAD_LEFT);
                        break;
                    case 'year':
                        $number .= $year;
                        break;
                    case 'year_roman':
                        $number .= $this->intToRoman($year);
                        break;
                    case 'day':
                        $number .= date('d', strtotime($validated['letter_date']));
                        break;
                }
            }

            // 3. Create Letter
            $letter = Letter::create([
                'type' => 'outgoing',
                'letter_format_id' => $format->id,
                'letter_number' => $number,
                'sequence_number' => $sequence,
                'letter_date' => $validated['letter_date'],
                'recipient' => $validated['recipient'],
                'subject' => $validated['subject'],
                'description' => $validated['description'],
                'created_by' => $user->id,
                'status' => 'draft' // Changed to draft until uploaded
            ]);

            // Log
            \App\Models\LetterActivityLog::create([
                'letter_id' => $letter->id,
                'user_id' => $user->id,
                'action' => 'created',
                'details' => 'Draft surat keluar dibuat dengan nomor ' . $number
            ]);
        });

        return redirect()->route('admin.letters.out.index')->with('success', 'Draft surat berhasil dibuat. Silakan cetak, scan, lalu upload untuk menyelesaikan.');
    }

    public function show(Letter $letter)
    {
        if ($letter->type !== 'outgoing') abort(404);
        return view('admin.letters.out.show', compact('letter'));
    }

    public function print(Letter $letter)
    {
        if ($letter->type !== 'outgoing') abort(404);

        $pdf = Pdf::loadView('admin.letters.out.pdf', compact('letter'));
        return $pdf->stream('Surat-' . str_replace('/', '-', $letter->letter_number) . '.pdf');
    }

    // Show Upload Form
    public function upload(Letter $letter)
    {
        if ($letter->type !== 'outgoing') abort(404);
        // Ensure user has permission or is creator? For now let admin/creator do it.
        return view('admin.letters.out.upload', compact('letter'));
    }

    // Process Upload
    public function processUpload(Request $request, Letter $letter)
    {
        if ($letter->type !== 'outgoing') abort(404);

        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($letter->file_path) {
                Storage::disk('public')->delete($letter->file_path);
            }

            $path = $request->file('file')->store('letters/outgoing', 'public');

            $letter->update([
                'file_path' => $path,
                'status' => 'sent' // Mark as complete/sent
            ]);

            // Log
            \App\Models\LetterActivityLog::create([
                'letter_id' => $letter->id,
                'user_id' => Auth::id(),
                'action' => 'uploaded',
                'details' => 'File scan diupload. Status diubah menjadi Sent.'
            ]);

            return redirect()->route('admin.letters.out.index')->with('success', 'File scan berhasil diupload.');
        }

        return back()->withErrors(['file' => 'Gagal mengupload file.']);
    }

    private function intToRoman($number)
    {
        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}

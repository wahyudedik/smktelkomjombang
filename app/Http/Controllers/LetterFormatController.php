<?php

namespace App\Http\Controllers;

use App\Models\LetterFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Routing\Controller as BaseController;

class LetterFormatController extends BaseController
{
    /**
     * Constructor to apply permission middleware.
     */
    public function __construct()
    {
        $this->middleware('permission:surat.settings');
    }

    public function index()
    {
        $formats = LetterFormat::with('segments')->get();
        return view('admin.letters.formats.index', compact('formats'));
    }

    public function create()
    {
        return view('admin.letters.formats.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:out,in',
            'period_mode' => 'required|in:year,month,all',
            'counter_scope' => 'required|in:global,unit',
            'segments' => 'required|array',
            'segments.*.kind' => 'required|string|in:sequence,text,unit_code,day,month_roman,month_number,year,year_roman',
            'segments.*.value' => 'nullable|string',
            'segments.*.padding' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            // Create Format
            $format = LetterFormat::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'period_mode' => $validated['period_mode'],
                'counter_scope' => $validated['counter_scope'],
                'is_active' => true,
                'format_template' => $this->generateTemplatePreview($validated['segments'])
            ]);

            // Create Segments
            $order = 1;
            foreach ($validated['segments'] as $segment) {
                $format->segments()->create([
                    'type' => $segment['kind'],
                    'value' => $segment['value'] ?? null,
                    'padding' => $segment['padding'] ?? null,
                    'order' => $order++
                ]);
            }
        });

        return redirect()->route('admin.letters.formats.index')->with('success', 'Format surat berhasil dibuat');
    }

    public function edit(LetterFormat $format)
    {
        $format->load('segments');
        return view('admin.letters.formats.edit', ['letterFormat' => $format]);
    }

    public function update(Request $request, LetterFormat $format)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:out,in',
            'period_mode' => 'required|in:year,month,all',
            'counter_scope' => 'required|in:global,unit',
            'is_active' => 'boolean',
            'segments' => 'required|array',
            'segments.*.kind' => 'required|string|in:sequence,text,unit_code,day,month_roman,month_number,year,year_roman',
            'segments.*.value' => 'nullable|string',
            'segments.*.padding' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($validated, $format, $request) {
            // Update Format
            $format->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'period_mode' => $validated['period_mode'],
                'counter_scope' => $validated['counter_scope'],
                'is_active' => $request->has('is_active'),
                'format_template' => $this->generateTemplatePreview($validated['segments'])
            ]);

            // Replace Segments
            $format->segments()->delete();
            $order = 1;
            foreach ($validated['segments'] as $segment) {
                $format->segments()->create([
                    'type' => $segment['kind'],
                    'value' => $segment['value'] ?? null,
                    'padding' => $segment['padding'] ?? null,
                    'order' => $order++
                ]);
            }
        });

        return redirect()->route('admin.letters.formats.index')->with('success', 'Format surat berhasil diperbarui');
    }

    public function destroy(LetterFormat $format)
    {
        $format->delete();
        return redirect()->route('admin.letters.formats.index')->with('success', 'Format surat berhasil dihapus');
    }

    private function generateTemplatePreview($segments)
    {
        $preview = '';
        foreach ($segments as $segment) {
            switch ($segment['kind']) {
                case 'sequence':
                    $preview .= '{seq}';
                    break;
                case 'text':
                    $preview .= $segment['value'];
                    break;
                case 'unit_code':
                    $preview .= '{unit}';
                    break;
                case 'day':
                    $preview .= '{day}';
                    break;
                case 'month_roman':
                    $preview .= '{romawi}';
                    break;
                case 'month_number':
                    $preview .= '{bln}';
                    break;
                case 'year':
                    $preview .= '{thn}';
                    break;
                case 'year_roman':
                    $preview .= '{THN}';
                    break;
            }
        }
        return $preview;
    }
}

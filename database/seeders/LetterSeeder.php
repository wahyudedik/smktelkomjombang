<?php

namespace Database\Seeders;

use App\Models\Letter;
use App\Models\LetterCounter;
use App\Models\LetterFormat;
use App\Models\LetterFormatSegment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure we have a user with unit_code for testing
        $user = User::first();
        if ($user) {
            $user->unit_code = 'TU'; // Tata Usaha
            $user->save();
        } else {
            $user = User::factory()->create([
                'name' => 'Admin Tata Usaha',
                'email' => 'tu@sekolah.com',
                'unit_code' => 'TU',
            ]);
        }

        // 2. Create Letter Formats

        // Format 1: SK (Surat Keputusan) - Global Counter
        DB::transaction(function () {
            $skFormat = LetterFormat::firstOrCreate(
                ['name' => 'Surat Keputusan (SK)'],
                [
                    'description' => 'Format untuk Surat Keputusan Kepala Sekolah',
                    'type' => 'out',
                    'period_mode' => 'year',
                    'counter_scope' => 'global',
                    'format_template' => 'No: {seq}/SK/SMK-IG/{rom_month}/{year}',
                    'is_active' => true,
                ]
            );

            // Only create segments if they don't exist
            if ($skFormat->segments()->count() == 0) {
                $segments = [
                    ['type' => 'text', 'value' => 'No: ', 'order' => 1],
                    ['type' => 'sequence', 'padding' => 3, 'order' => 2],
                    ['type' => 'text', 'value' => '/SK/SMK-IG/', 'order' => 3],
                    ['type' => 'month_roman', 'order' => 4],
                    ['type' => 'text', 'value' => '/', 'order' => 5],
                    ['type' => 'year', 'order' => 6],
                ];

                foreach ($segments as $segment) {
                    $skFormat->segments()->create($segment);
                }

                // Seed some outgoing letters for SK only if new format
                $this->seedOutgoingLetters($skFormat, 5);
            }
        });

        // Format 2: Surat Tugas (ST) - Unit Scoped
        DB::transaction(function () use ($user) {
            $stFormat = LetterFormat::firstOrCreate(
                ['name' => 'Surat Tugas (ST)'],
                [
                    'description' => 'Format untuk Surat Tugas (Per Unit)',
                    'type' => 'out',
                    'period_mode' => 'year',
                    'counter_scope' => 'unit',
                    'format_template' => '{seq}/ST/{unit_code}/SMK-IG/{year}',
                    'is_active' => true,
                ]
            );

            if ($stFormat->segments()->count() == 0) {
                $segments = [
                    ['type' => 'sequence', 'padding' => 3, 'order' => 1],
                    ['type' => 'text', 'value' => '/ST/', 'order' => 2],
                    ['type' => 'unit_code', 'order' => 3],
                    ['type' => 'text', 'value' => '/SMK-IG/', 'order' => 4],
                    ['type' => 'year', 'order' => 5],
                ];

                foreach ($segments as $segment) {
                    $stFormat->segments()->create($segment);
                }

                $this->seedOutgoingLetters($stFormat, 3, $user);
            }
        });

        // Format 3: Surat Undangan - Month Reset
        DB::transaction(function () {
            $undFormat = LetterFormat::firstOrCreate(
                ['name' => 'Surat Undangan'],
                [
                    'description' => 'Format untuk Surat Undangan (Reset per Bulan)',
                    'type' => 'out',
                    'period_mode' => 'month',
                    'counter_scope' => 'global',
                    'format_template' => '{seq}/UND/{month_number}/{year}',
                    'is_active' => true,
                ]
            );

            if ($undFormat->segments()->count() == 0) {
                $segments = [
                    ['type' => 'sequence', 'padding' => 3, 'order' => 1],
                    ['type' => 'text', 'value' => '/UND/', 'order' => 2],
                    ['type' => 'month_number', 'order' => 3],
                    ['type' => 'text', 'value' => '/', 'order' => 4],
                    ['type' => 'year', 'order' => 5],
                ];

                foreach ($segments as $segment) {
                    $undFormat->segments()->create($segment);
                }

                $this->seedOutgoingLetters($undFormat, 4);
            }
        });

        // 3. Create Incoming Letters (Surat Masuk)
        if (Letter::where('type', 'incoming')->count() == 0) {
            $this->seedIncomingLetters(10);
        }
    }

    private function seedOutgoingLetters($format, $count, $user = null)
    {
        $faker = \Faker\Factory::create('id_ID');
        $user = $user ?? User::first();

        $year = date('Y');
        $month = date('n');

        // Initialize or get counter
        $scopeUnit = $format->counter_scope == 'unit' ? $user->unit_code : null;
        $counter = LetterCounter::firstOrCreate(
            [
                'letter_format_id' => $format->id,
                'scope_unit_code' => $scopeUnit,
                'year' => $year,
                'month' => $format->period_mode == 'month' ? $month : null,
            ],
            ['current_value' => 0]
        );

        for ($i = 0; $i < $count; $i++) {
            $counter->increment('current_value');
            $sequence = $counter->current_value;

            // Generate Number based on segments
            $number = $this->generateNumber($format, $sequence, $user);

            Letter::create([
                'type' => 'outgoing',
                'letter_format_id' => $format->id,
                'letter_number' => $number,
                'sequence_number' => $sequence,
                'letter_date' => now(),
                'recipient' => $faker->name,
                'subject' => $faker->sentence(4),
                'description' => $faker->paragraph,
                'status' => 'sent',
                'created_by' => $user->id,
                'file_path' => 'letters/example.pdf',
            ]);
        }
    }

    private function generateNumber($format, $sequence, $user)
    {
        $number = '';
        foreach ($format->segments()->orderBy('order')->get() as $segment) {
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
                    $number .= $this->intToRoman(date('n'));
                    break;
                case 'month_number':
                    $number .= date('m');
                    break;
                case 'year':
                    $number .= date('Y');
                    break;
                case 'year_roman':
                    $number .= $this->intToRoman(date('Y'));
                    break;
                case 'day':
                    $number .= date('d');
                    break;
            }
        }
        return $number;
    }

    private function seedIncomingLetters($count)
    {
        $faker = \Faker\Factory::create('id_ID');
        $user = User::first();

        for ($i = 0; $i < $count; $i++) {
            Letter::create([
                'type' => 'incoming',
                'reference_number' => $faker->bothify('REF/###/???/2024'),
                'letter_date' => $faker->dateTimeThisYear,
                'sender' => $faker->company,
                'subject' => $faker->sentence(5),
                'description' => $faker->paragraph,
                'status' => $faker->randomElement(['received', 'dispositioned', 'archived']),
                'created_by' => $user->id,
                'file_path' => 'letters/incoming_example.pdf',
            ]);
        }
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

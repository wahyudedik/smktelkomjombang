<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DataManagementController extends Controller
{
    /**
     * Display the data management dashboard.
     */
    public function index()
    {
        // Get counts for statistics
        $kelasCount = DB::table('kelas')->count();
        $jurusanCount = DB::table('jurusan')->count();
        $ekstrakurikulerCount = DB::table('ekstrakurikuler')->count();
        $mataPelajaranCount = DB::table('mata_pelajaran')->count();

        // Get data for tables
        $kelas = DB::table('kelas')->orderBy('nama')->get();
        $jurusan = DB::table('jurusan')->orderBy('nama')->get();
        $ekstrakurikuler = DB::table('ekstrakurikuler')->orderBy('nama')->get();
        $mataPelajaran = DB::table('mata_pelajaran')->orderBy('nama')->get();

        return view('settings.data-management', compact(
            'kelasCount',
            'jurusanCount',
            'ekstrakurikulerCount',
            'mataPelajaranCount',
            'kelas',
            'jurusan',
            'ekstrakurikuler',
            'mataPelajaran'
        ));
    }

    /**
     * Store a new kelas.
     */
    public function storeKelas(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255|unique:kelas,nama',
                'deskripsi' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $id = DB::table('kelas')->insertGetId([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil ditambahkan.',
                'data' => [
                    'id' => $id,
                    'nama' => $request->nama,
                    'deskripsi' => $request->deskripsi
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in storeKelas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a kelas.
     */
    public function updateKelas(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:kelas,nama,' . $id,
            'deskripsi' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::table('kelas')->where('id', $id)->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil diperbarui.',
            'data' => [
                'id' => $id,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi
            ]
        ]);
    }

    /**
     * Delete a kelas.
     */
    public function deleteKelas($id)
    {
        // Check if kelas is being used by students
        $siswaCount = DB::table('siswas')->where('kelas', DB::table('kelas')->where('id', $id)->value('nama'))->count();

        if ($siswaCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Tidak dapat menghapus kelas karena masih digunakan oleh {$siswaCount} siswa."
            ], 400);
        }

        DB::table('kelas')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dihapus.'
        ]);
    }

    /**
     * Store a new jurusan.
     */
    public function storeJurusan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:jurusan,nama',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $id = DB::table('jurusan')->insertGetId([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jurusan berhasil ditambahkan.',
            'data' => [
                'id' => $id,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi
            ]
        ]);
    }

    /**
     * Update a jurusan.
     */
    public function updateJurusan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:jurusan,nama,' . $id,
            'deskripsi' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::table('jurusan')->where('id', $id)->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jurusan berhasil diperbarui.',
            'data' => [
                'id' => $id,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi
            ]
        ]);
    }

    /**
     * Delete a jurusan.
     */
    public function deleteJurusan($id)
    {
        // Check if jurusan is being used by students
        $siswaCount = DB::table('siswas')->where('jurusan', DB::table('jurusan')->where('id', $id)->value('nama'))->count();

        if ($siswaCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Tidak dapat menghapus jurusan karena masih digunakan oleh {$siswaCount} siswa."
            ], 400);
        }

        DB::table('jurusan')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jurusan berhasil dihapus.'
        ]);
    }

    /**
     * Store a new ekstrakurikuler.
     */
    public function storeEkstrakurikuler(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:ekstrakurikuler,nama',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $id = DB::table('ekstrakurikuler')->insertGetId([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ekstrakurikuler berhasil ditambahkan.',
            'data' => [
                'id' => $id,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi
            ]
        ]);
    }

    /**
     * Update an ekstrakurikuler.
     */
    public function updateEkstrakurikuler(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:ekstrakurikuler,nama,' . $id,
            'deskripsi' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::table('ekstrakurikuler')->where('id', $id)->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ekstrakurikuler berhasil diperbarui.',
            'data' => [
                'id' => $id,
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi
            ]
        ]);
    }

    /**
     * Delete an ekstrakurikuler.
     */
    public function deleteEkstrakurikuler($id)
    {
        // Check if ekstrakurikuler is being used by students
        $siswaCount = DB::table('siswas')->whereJsonContains('ekstrakurikuler', DB::table('ekstrakurikuler')->where('id', $id)->value('nama'))->count();

        if ($siswaCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Tidak dapat menghapus ekstrakurikuler karena masih digunakan oleh {$siswaCount} siswa."
            ], 400);
        }

        DB::table('ekstrakurikuler')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ekstrakurikuler berhasil dihapus.'
        ]);
    }

    /**
     * Store a new mata pelajaran.
     */
    public function storeMataPelajaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:mata_pelajaran,nama',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $id = DB::table('mata_pelajaran')->insertGetId([
            'nama' => $request->nama,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil ditambahkan.',
            'data' => [
                'id' => $id,
                'nama' => $request->nama
            ]
        ]);
    }

    /**
     * Update a mata pelajaran.
     */
    public function updateMataPelajaran(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:mata_pelajaran,nama,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::table('mata_pelajaran')->where('id', $id)->update([
            'nama' => $request->nama,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil diperbarui.',
            'data' => [
                'id' => $id,
                'nama' => $request->nama
            ]
        ]);
    }

    /**
     * Delete a mata pelajaran.
     */
    public function deleteMataPelajaran($id)
    {
        // Check if mata pelajaran is being used by teachers
        $guruCount = DB::table('gurus')->whereJsonContains('mata_pelajaran', DB::table('mata_pelajaran')->where('id', $id)->value('nama'))->count();

        if ($guruCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Tidak dapat menghapus mata pelajaran karena masih digunakan oleh {$guruCount} guru."
            ], 400);
        }

        DB::table('mata_pelajaran')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil dihapus.'
        ]);
    }
}

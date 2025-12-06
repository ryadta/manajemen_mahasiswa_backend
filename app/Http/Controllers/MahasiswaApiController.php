<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Exception;

class MahasiswaApiController extends Controller
{
    // =============================
    // GET: Menampilkan semua data mahasiswa
    // =============================
    public function apiIndex()
    {
        try {
            $mahasiswas = Mahasiswa::all();

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil ditemukan',
                'data' => $mahasiswas
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    // =============================
    // GET: Menampilkan 1 data mahasiswa
    // =============================
    public function apiShow($nim)
    {
        try {
            $mahasiswa = Mahasiswa::find($nim);

            if (!$mahasiswa) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil ditemukan',
                'data' => $mahasiswa
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =============================
    // POST: Tambah data mahasiswa
    // =============================
    public function apiStore(Request $request)
    {
        try {
            $request->validate([
                'nim' => 'required|numeric|digits_between:8,10|unique:mahasiswas,nim',
                'nama' => 'required|string|max:100',
                'jurusan' => 'required|string|max:100',
                'angkatan' => 'required|integer|min:2000|max:' . date('Y'),
                'no_hp' => 'required|regex:/^08[0-9]{8,12}$/',
                'alamat' => 'required|string|max:255'
            ]);

            $mahasiswa = Mahasiswa::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil ditambahkan',
                'data' => $mahasiswa
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data ke database',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menambahkan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =============================
    // PUT: Update data mahasiswa
    // =============================
    public function apiUpdate(Request $request, $nim)
    {
        try {
            $mahasiswa = Mahasiswa::find($nim);

            if (!$mahasiswa) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'nama' => 'sometimes|required|string|max:100',
                'jurusan' => 'sometimes|required|string|max:100',
                'angkatan' => 'sometimes|required|integer|min:2000|max:' . date('Y'),
                'no_hp' => 'sometimes|required|regex:/^08[0-9]{8,12}$/',
                'alamat' => 'sometimes|required|string|max:255'
            ]);

            $mahasiswa->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil diperbarui',
                'data' => $mahasiswa
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data mahasiswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =============================
    // DELETE: Hapus data mahasiswa
    // =============================
    public function apiDelete($nim)
    {
        try {
            $mahasiswa = Mahasiswa::find($nim);

            if (!$mahasiswa) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $mahasiswa->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

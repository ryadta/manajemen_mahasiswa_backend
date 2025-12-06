<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class MahasiswaController extends Controller
{
    // =================== UNTUK WEB VIEW ===================

    // Menampilkan semua data mahasiswa
    public function index()
    {
        $mahasiswas = Mahasiswa::all();
        return view('mahasiswa.index', compact('mahasiswas'));
    }

    // Form tambah mahasiswa
    public function create()
    {
        return view('mahasiswa.create');
    }

    // Menyimpan data mahasiswa baru
    public function store(Request $request)
    {
        // Validasi dengan pesan custom
        $request->validate([
            'nim' => 'required|unique:mahasiswas',
            'nama' => 'required',
            'jurusan' => 'required',
            'angkatan' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required'
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah digunakan! Silakan gunakan NIM lain.',
            'nama.required' => 'Nama wajib diisi.',
            'jurusan.required' => 'Jurusan wajib diisi.',
            'angkatan.required' => 'Angkatan wajib diisi.',
            'no_hp.required' => 'No HP wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.'
        ]);

        try {
            Mahasiswa::create($request->all());
            return redirect()->route('mahasiswa.index')->with('success', 'Data berhasil ditambahkan!');
        } catch (QueryException $e) {
            // Menangani duplicate entry NIM
            if ($e->getCode() == '23000') {
                return redirect()->back()->withInput()->with('error', 'NIM sudah digunakan!');
            }
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan pada server.');
        }
    }

    // Menampilkan detail mahasiswa
    public function show($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    // Form edit mahasiswa
    public function edit($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    // Update data mahasiswa
    public function update(Request $request, $nim)
    {
        // Cari mahasiswa berdasarkan NIM lama
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();

        // Validasi update dengan pengecualian NIM lama
        $request->validate([
            'nim' => 'required|unique:mahasiswas,nim,' . $nim . ',nim',
            'nama' => 'required',
            'jurusan' => 'required',
            'angkatan' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required'
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah digunakan! Silakan gunakan NIM lain.',
            'nama.required' => 'Nama wajib diisi.',
            'jurusan.required' => 'Jurusan wajib diisi.',
            'angkatan.required' => 'Angkatan wajib diisi.',
            'no_hp.required' => 'No HP wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.'
        ]);

        $mahasiswa->update($request->all());

        return redirect()->route('mahasiswa.index')->with('success', 'Data berhasil diperbarui!');
    }

    // Hapus mahasiswa
    public function destroy($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->firstOrFail();
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Data berhasil dihapus!');
    }
}

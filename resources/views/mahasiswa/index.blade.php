@extends('layouts.admin')

{{-- Set judul halaman (akan muncul di tab browser) --}}
@section('title', 'Data Mahasiswa')

{{-- Konten utama halaman --}}
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Data Mahasiswa</h1>
    </div>

    {{-- Pesan Sukses --}}
    @if(session('success'))
        <div class="alert alert-success" data-dismiss-after="3000">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Data --}}
    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Angkatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No HP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($mahasiswas as $m)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $m->nim }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $m->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $m->jurusan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $m->angkatan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $m->no_hp }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $m->alamat }}">{{ $m->alamat }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('mahasiswa.edit', $m->nim) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        <form action="{{ route('mahasiswa.destroy', $m->nim) }}" method="POST" class="inline" data-confirm-delete>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                        Tidak ada data mahasiswa.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
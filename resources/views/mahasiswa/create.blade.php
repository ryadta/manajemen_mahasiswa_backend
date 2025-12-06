@extends('layouts.admin')

@section('title', 'Tambah Mahasiswa')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Tambah Mahasiswa</h1>

    {{-- Menampilkan error validasi (dari Backend, jika Anda menambahkannya nanti) --}}
    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <strong class="font-bold">Oops! Terjadi kesalahan:</strong>
            <ul class="list-disc pl-5 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('mahasiswa.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- NIM --}}
                <div>
                    <label for="nim" class="form-label">NIM</label>
                    <input type="text" name="nim" id="nim" class="form-input" value="{{ old('nim') }}"
                           inputmode="numeric" 
                           pattern="[0-9]{10}"  
                           title="NIM hanya boleh berisi 10 digit angka." 
                           required>
                    @error('nim') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Nama --}}
                <div>
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-input" value="{{ old('nama') }}" required>
                    @error('nama') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Jurusan --}}
                <div>
                    <label for="jurusan" class="form-label">Jurusan</label>
                    <input type="text" name="jurusan" id="jurusan" class="form-input" value="{{ old('jurusan') }}" required>
                    @error('jurusan') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Angkatan --}}
                <div>
                    <label for="angkatan" class="form-label">Angkatan</label>
                    <input type="text" name="angkatan" id="angkatan" class="form-input" value="{{ old('angkatan') }}"
                           inputmode="numeric" 
                           pattern="[0-9]{4}" 
                           title="Angkatan harus 4 digit angka (cth: 2021)." 
                           maxlength="4" 
                           required>
                    @error('angkatan') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- No HP --}}
                <div class="md:col-span-2">
                    <label for="no_hp" class="form-label">No HP</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-input" value="{{ old('no_hp') }}"
                           inputmode="tel" 
                           pattern="[0-9]{10,15}" 
                           title="No. HP harus 10-12 digit angka." 
                           maxlength="15" 
                           required>
                    @error('no_hp') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" class="form-input">{{ old('alamat') }}</textarea>
                    @error('alamat') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
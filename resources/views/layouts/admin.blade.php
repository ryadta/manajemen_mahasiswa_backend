<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200">
        <aside 
            class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 text-white transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0"
            :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
            x-cloak
        >
            <div class="p-4">
                <a href="{{ route('mahasiswa.index') }}" class="text-2xl font-bold text-white">Admin Panel</a>
            </div>
            <nav class="mt-4">
                {{-- Link ke List Data (Index) --}}
                <a href="{{ route('mahasiswa.index') }}" class="sidebar-link {{ (request()->routeIs('mahasiswa.index') || request()->routeIs('mahasiswa.edit')) ? 'active' : '' }}">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <span>Data Mahasiswa</span>
                </a>

                {{-- Link ke Tambah Data (Create) --}}
                <a href="{{ route('mahasiswa.create') }}" class="sidebar-link {{ request()->routeIs('mahasiswa.create') ? 'active' : '' }}">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Tambah Mahasiswa</span>
                </a>
                
                <a href="#" class="sidebar-link">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    <span>Menu Lain</span>
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between p-4 bg-white border-b border-gray-200">
                <div>
                    <button @click.stop="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none md:hidden">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
                <div>
                    <span class="text-gray-700">Selamat Datang, Admin!</span>
                    </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <div class="container mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black opacity-50 transition-opacity md:hidden" x-cloak></div>
    </div>

</body>
</html>
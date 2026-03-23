<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | Auxinor Chemicals</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Lora:ital,wght@0,400;0,600;1,400&family=Syne:wght@400;600;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    
    <style>
        /* Admin specific overrides - Light Theme */
        body { background-color: #f4f7f6; color: var(--ink); }
        .admin-sidebar { background-color: #ffffff; border-right: 1px solid #e2e8f0; }
        .admin-content { background-color: #f4f7f6; }
        .admin-card { background-color: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-radius: 4px; }
        .admin-input { background-color: #ffffff; border: 1px solid #cbd5e1; color: var(--ink); border-radius: 4px; }
        .admin-input:focus { border-color: var(--teal); outline: none; box-shadow: 0 0 0 1px var(--teal); }
    </style>
</head>
<body class="font-sans antialiased text-sm">
    <div x-data="{ sidebarOpen: true }" class="min-h-screen flex">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="admin-sidebar flex-shrink-0 transition-all duration-300 flex flex-col items-stretch sticky top-0 h-screen overflow-y-auto">
            <div class="h-16 flex items-center justify-between px-4 border-b border-gray-100 shrink-0 cursor-pointer hover:bg-gray-50 transition-colors" @click="sidebarOpen = !sidebarOpen">
                <span x-show="sidebarOpen" class="font-display font-bold text-lg text-ink tracking-wider">AUXINOR <span class="text-teal text-xs align-top">SYS</span></span>
                <span x-show="!sidebarOpen" class="font-display font-bold text-lg text-teal mx-auto">A</span>
                <svg x-show="sidebarOpen" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </div>
            
            <nav class="flex-1 py-6 px-3 space-y-1">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard'],
                        ['route' => 'admin.editor.index', 'icon' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z', 'label' => 'Visual Editor'],
                        ['route' => 'admin.enquiries.index', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Enquiries', 'badge' => \App\Models\Enquiry::where('status', 'new')->count()],
                        ['route' => 'admin.products.index', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'label' => 'Products'],
                        ['route' => 'admin.categories.index', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'label' => 'Categories'],
                        ['route' => 'admin.industries.index', 'icon' => 'M19 21V5a2 2 0 012-2H3a2 2 0 00-2 2v16m20 0h2m-2 0h-5.414l-2-2H13m0 0l2 2H3a2 2 0 01-2-2v-5m18 0l-2-2L5 20', 'label' => 'Industries'],
                        ['route' => 'admin.posts.index', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', 'label' => 'Insights (Blog)'],
                        ['route' => 'admin.settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Settings'],
                    ];
                @endphp
                
                @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}" class="flex items-center px-3 py-2.5 rounded hover:bg-teal/5 transition-colors {{ request()->routeIs($item['route'].'*') ? 'bg-teal/10 text-teal font-semibold' : 'text-gray-500 hover:text-ink' }} group">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg>
                    <span x-show="sidebarOpen" class="flex-grow text-[13px] tracking-wide">{{ $item['label'] }}</span>
                    @if(isset($item['badge']) && $item['badge'] > 0)
                        <span x-show="sidebarOpen" class="bg-teal text-ink font-bold text-[10px] px-2 py-0.5 rounded-full">{{ $item['badge'] }}</span>
                        <!-- Notification dot when collapsed -->
                        <span x-show="!sidebarOpen" class="absolute right-2 w-2 h-2 bg-teal rounded-full"></span>
                    @endif
                </a>
                @endforeach
            </nav>
            
            <div class="p-4 border-t border-gray-100 shrink-0">
                <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
                    @csrf
                    <a href="#" onclick="document.getElementById('logout-form').submit();" class="flex items-center px-3 py-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded transition-colors group">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span x-show="sidebarOpen" class="text-[13px]">Logout</span>
                    </a>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="h-16 flex items-center justify-between px-6 border-b border-gray-200 bg-white sticky top-0 z-10 shadow-sm">
                <div class="font-serif italic text-gray-500 text-[13px]">
                    {{ \Carbon\Carbon::now()->format('l, F j, Y') }}
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" target="_blank" class="font-mono text-[10px] uppercase tracking-wider text-teal hover:text-teal-light transition-colors flex items-center">
                        View Site <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                    
                    <div class="w-px h-4 bg-gray-200"></div>
                    
                    <div class="font-display font-medium text-[13px] text-gray-800">
                        {{ Auth::user()->name ?? 'Admin' }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6 md:p-8 overflow-y-auto flex-1 h-[calc(100vh-64px)] scroll-smooth">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 bg-teal/20 border border-teal text-teal-2 px-4 py-3 rounded relative flex justify-between items-center" role="alert">
                        <span class="block sm:inline font-mono text-[11px] uppercase tracking-wider">{{ session('success') }}</span>
                        <button @click="show = false" class="text-teal-2 hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 bg-red-900/40 border border-red-500 text-red-200 px-4 py-3 rounded relative flex justify-between items-center" role="alert">
                        <span class="block sm:inline font-mono text-[11px] uppercase tracking-wider">{{ session('error') }}</span>
                        <button @click="show = false" class="text-red-200 hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif
                
                <!-- Breadcrumbs/Title -->
                <div class="mb-8 flex justify-between items-end">
                    <div>
                        <h1 class="font-display font-bold text-3xl tracking-tight text-ink mb-1">@yield('header')</h1>
                        <p class="font-serif italic text-gray-500 text-[13px]">@yield('subheader')</p>
                    </div>
                    <div>
                        @yield('actions')
                    </div>
                </div>

                <!-- Main Slot -->
                @yield('content')
            </div>
            
            <footer class="p-4 border-t border-gray-200 text-center font-mono text-[10px] text-gray-400 uppercase tracking-widest bg-[#f4f7f6]">
                Admin System v1.0 • Auxinor Chemicals
            </footer>
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>

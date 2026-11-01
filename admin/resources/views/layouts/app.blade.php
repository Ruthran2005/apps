<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#0f172a">
    <title>@yield('title', 'Admin Portal') — SchoolNexus</title>
    <!-- Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Outfit"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                            950: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        * { -webkit-tap-highlight-color: transparent; }
        body { -webkit-font-smoothing: antialiased; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.35); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(100, 116, 139, 0.55); }
        .mobile-bottom-nav > a,
        .mobile-bottom-nav > button {
            flex: 1 1 0%;
            min-width: 0;
            min-height: 44px;
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }
        .mobile-bottom-nav span { white-space: nowrap; }
        @media (max-width: 639px) {
            main .overflow-x-auto > table { min-width: 36rem; }
            main { -webkit-overflow-scrolling: touch; scroll-padding-bottom: 7rem; }
            main form .grid[class*="grid-cols-2"],
            main form .grid[class*="grid-cols-3"],
            main form .grid[class*="grid-cols-4"],
            main form .grid[class*="grid-cols-5"],
            main form .grid[class*="grid-cols-6"] { grid-template-columns: minmax(0, 1fr); }
            main :where(input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="file"]), select, textarea) {
                max-width: 100%;
                font-size: 16px;
            }
            main textarea { min-height: 6rem; }
            main button,
            main input[type="submit"] { min-height: 2.5rem; }
        }
    </style>
</head>
<body class="h-full font-sans text-slate-800 antialiased selection:bg-indigo-500 selection:text-white" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false" :class="sidebarOpen ? 'overflow-hidden lg:overflow-auto' : ''">
    @php
        $activeAdmin = $admin ?? auth()->user() ?? \App\Models\User::where('role', 'admin')->first();
        $adminPerms = $activeAdmin->permissions ?? null;
        $canAccess = function($tab) use ($adminPerms) {
            if (empty($adminPerms)) return true;
            return in_array($tab, $adminPerms);
        };
    @endphp

    <div class="min-h-dvh flex flex-col lg:flex-row">
        <!-- Mobile Sidebar Backdrop with Blur -->
        <div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 bg-slate-950/75 backdrop-blur-md lg:hidden"></div>

        <!-- Enhanced Sidebar -->
        <aside id="portal-navigation" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] bg-slate-950 text-white flex flex-col transition-transform duration-300 ease-out lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen lg:shrink-0 border-r border-slate-800/80 shadow-[10px_0_35px_rgba(0,0,0,0.35)] overflow-hidden">
            
            <!-- Subtle gradient glow in sidebar background -->
            <div class="absolute -top-20 -left-20 w-60 h-60 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-20 -right-20 w-60 h-60 bg-violet-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Brand / Logo -->
            <div class="h-16 sm:h-20 shrink-0 flex items-center justify-between px-6 border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-md relative z-10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3.5 group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-violet-400 flex items-center justify-center shadow-lg shadow-indigo-500/40 ring-1 ring-white/20 group-hover:scale-105 group-hover:shadow-indigo-500/60 transition-all duration-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-display text-lg font-bold tracking-tight text-white flex items-center gap-1.5">
                            SchoolNexus
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-ping"></span>
                        </span>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-300 bg-indigo-500/10 px-2 py-0.5 rounded-md border border-indigo-500/20">Admin Hub</span>
                        </div>
                    </div>
                </a>
                <button @click="sidebarOpen = false" aria-label="Close navigation menu" class="lg:hidden text-slate-400 hover:text-white p-2 rounded-xl hover:bg-slate-800/80 active:scale-95 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Navigation Links (Structured ERP Suite) -->
            <nav class="flex-1 px-3.5 py-4 space-y-1 overflow-y-auto relative z-10 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                
                <!-- Category 1: Academics & Classroom -->
                <div class="px-3 pt-1 pb-2 text-[10px] font-extrabold uppercase tracking-widest text-slate-500 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    <span>Academics & Classroom</span>
                </div>
                
                <!-- 1. Dashboard -->
                @if($canAccess('dashboard'))
                <a href="{{ route('admin.dashboard') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <span>Dashboard</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 2. Students -->
                @if($canAccess('students'))
                <a href="{{ route('admin.students') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.students*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.students*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <span>Students</span>
                    @if(request()->routeIs('admin.students*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 3. Teachers -->
                @if($canAccess('teachers'))
                <a href="{{ route('admin.teachers') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.teachers*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.teachers*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <span>Teachers</span>
                    @if(request()->routeIs('admin.teachers*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 4. Classes -->
                @if($canAccess('classes'))
                <a href="{{ route('admin.classes') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.classes*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.classes*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <span>Classes & Sections</span>
                    @if(request()->routeIs('admin.classes*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 5. Subjects & Syllabus -->
                @if($canAccess('subjects'))
                <a href="{{ route('admin.subjects') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.subjects*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.subjects*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span>Subjects & Syllabus</span>
                    @if(request()->routeIs('admin.subjects*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 6. Timetable -->
                @if($canAccess('timetable'))
                <a href="{{ route('admin.timetable') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.timetable*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.timetable*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span>Timetable</span>
                    @if(request()->routeIs('admin.timetable*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 7. Attendance -->
                @if($canAccess('attendance'))
                <a href="{{ route('admin.attendance') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.attendance*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.attendance*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <span>Attendance</span>
                    @if(request()->routeIs('admin.attendance*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 8. Exams & Marks -->
                @if($canAccess('exams'))
                <a href="{{ route('admin.exams') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.exams*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.exams*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span>Exams & Marks</span>
                    @if(request()->routeIs('admin.exams*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 9. Homework (view only) -->
                @if($canAccess('homework'))
                <a href="{{ route('admin.homework') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.homework*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.homework*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span>Homework</span>
                    @if(request()->routeIs('admin.homework*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- Category 2: Administration, Finance & Services -->
                <div class="px-3 pt-4 pb-2 text-[10px] font-extrabold uppercase tracking-widest text-slate-500 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Finance & Administration</span>
                </div>

                <!-- 10. Fee Management -->
                @if($canAccess('fees'))
                <a href="{{ route('admin.fees') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.fees*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.fees*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span>Fee Management</span>
                    @if(request()->routeIs('admin.fees*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 11. Staff Payroll -->
                @if($canAccess('payroll'))
                <a href="{{ route('admin.payroll') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.payroll*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.payroll*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span>Staff Payroll</span>
                    @if(request()->routeIs('admin.payroll*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 12. Leave Management -->
                @if($canAccess('leaves'))
                <a href="{{ route('admin.leaves') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.leaves*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.leaves*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span>Leave Management</span>
                    @if(request()->routeIs('admin.leaves*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 13. Library Management -->
                @if($canAccess('library'))
                <a href="{{ route('admin.library') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.library*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.library*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                    </div>
                    <span>Library Management</span>
                    @if(request()->routeIs('admin.library*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 14. Transport -->
                @if($canAccess('transport'))
                <a href="{{ route('admin.transport') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.transport*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.transport*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.242-4.242 3 3 0 00-4.242 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span>Transport</span>
                    @if(request()->routeIs('admin.transport*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 15. Events & Calendar -->
                @if($canAccess('events'))
                <a href="{{ route('admin.events') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.events*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.events*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span>Events & Calendar</span>
                    @if(request()->routeIs('admin.events*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 16. Admissions & Enquiries -->
                @if($canAccess('admissions'))
                <a href="{{ route('admin.admissions') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.admissions*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.admissions*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <span>Admissions & Enquiries</span>
                    @if(request()->routeIs('admin.admissions*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 17. ID Cards & Certificates -->
                @if($canAccess('certificates'))
                <a href="{{ route('admin.certificates') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.certificates*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.certificates*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    </div>
                    <span>ID Cards & Certificates</span>
                    @if(request()->routeIs('admin.certificates*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 18. Notifications -->
                @if($canAccess('notifications'))
                <a href="{{ route('admin.notifications') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.notifications*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.notifications*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <span>Notifications</span>
                    @if(request()->routeIs('admin.notifications*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 19. Reports & Analytics -->
                @if($canAccess('reports'))
                <a href="{{ route('admin.reports') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.reports*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.reports*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <span>Reports & Analytics</span>
                    @if(request()->routeIs('admin.reports*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- Category 3: System, Governance & Security -->
                <div class="px-3 pt-4 pb-2 text-[10px] font-extrabold uppercase tracking-widest text-slate-500 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-violet-500"></span>
                    <span>Governance & Security</span>
                </div>

                <!-- 20. Announcements -->
                @if($canAccess('announcements'))
                <a href="{{ route('admin.announcements') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.announcements*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.announcements*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <span>Announcements</span>
                    @if(request()->routeIs('admin.announcements*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 21. Roles & Permissions -->
                @if($canAccess('roles'))
                <a href="{{ route('admin.roles') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.roles*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.roles*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <span>Roles & Permissions</span>
                    @if(request()->routeIs('admin.roles*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 22. Manage Admins -->
                @if($canAccess('manage-admins'))
                <a href="{{ route('admin.manage-admins') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.manage-admins*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.manage-admins*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span>Manage Admins</span>
                    @if(request()->routeIs('admin.manage-admins*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 23. Backup & Activity Logs -->
                @if($canAccess('audit-logs'))
                <a href="{{ route('admin.audit-logs') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.audit-logs*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.audit-logs*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span>Backup & Logs</span>
                    @if(request()->routeIs('admin.audit-logs*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 24. Settings -->
                @if($canAccess('settings'))
                <a href="{{ route('admin.settings') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.settings*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.settings*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <span>Settings</span>
                    @if(request()->routeIs('admin.settings*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif

                <!-- 25. Change Password -->
                @if($canAccess('change-password'))
                <a href="{{ route('admin.change-password') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('admin.change-password*') ? 'bg-gradient-to-r from-indigo-600 via-indigo-600 to-violet-600 text-white font-semibold shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('admin.change-password*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                    <span>Change Password</span>
                    @if(request()->routeIs('admin.change-password*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
                @endif
            </nav>

            <!-- User Profile & Sign Out Footer -->
            <div class="shrink-0 p-3.5 border-t border-slate-800/80 bg-slate-950/90 backdrop-blur-md relative z-10">
                <div class="p-3 rounded-2xl bg-slate-900/80 border border-slate-800/80 flex items-center justify-between shadow-inner">
                    <div class="flex items-center space-x-3 min-w-0">
                        <div class="relative shrink-0">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 text-white font-bold text-xs flex items-center justify-center shadow-md shadow-indigo-500/20">
                                {{ substr($activeAdmin->name ?? 'A', 0, 2) }}
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-400 ring-2 ring-slate-950"></span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-white truncate">{{ $activeAdmin->name ?? 'Admin' }}</p>
                            <span class="inline-block text-[10px] font-semibold text-indigo-400 truncate">{{ empty($activeAdmin->permissions) ? 'Super Admin' : 'Admin Role' }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-950/40 border border-transparent hover:border-rose-900/40 transition active:scale-95" title="Sign Out">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
            <!-- Ambient Background Lights for Ultra-Modern SaaS Feel -->
            <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-gradient-to-bl from-indigo-500/10 via-violet-500/5 to-transparent rounded-full blur-3xl pointer-events-none -z-10"></div>
            <div class="fixed bottom-0 left-60 w-[500px] h-[500px] bg-gradient-to-tr from-indigo-600/5 via-sky-500/5 to-transparent rounded-full blur-3xl pointer-events-none -z-10"></div>

            <!-- Glassmorphic Sticky Top Navbar -->
            <header class="h-16 sm:h-20 shrink-0 backdrop-blur-2xl bg-white/85 border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 lg:px-10 sticky top-0 z-30 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)]">
                <div class="flex items-center space-x-3.5 min-w-0">
                    <button @click="sidebarOpen = true" aria-label="Open navigation menu" :aria-expanded="sidebarOpen.toString()" aria-controls="portal-navigation" class="lg:hidden text-slate-700 hover:text-slate-950 p-2 -ml-1 rounded-2xl hover:bg-slate-100 active:scale-95 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="min-w-0">
                        <div class="hidden sm:flex items-center gap-1.5 text-[11px] font-semibold text-slate-400 mb-0.5">
                            <span class="hover:text-slate-600 cursor-pointer">SchoolNexus</span>
                            <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            <span class="text-indigo-600 font-bold">Admin Console</span>
                        </div>
                        <h1 class="font-display text-lg sm:text-2xl font-extrabold tracking-tight text-slate-900 leading-tight truncate">@yield('header_title', 'Institutional Overview')</h1>
                    </div>
                </div>

                <!-- Right Action Bar -->
                <div class="flex items-center space-x-3 shrink-0">
                    <!-- Live Status Badge -->
                    <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/60 text-emerald-700 text-xs font-bold shadow-xs">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span>System Live</span>
                    </div>

                    <!-- Notification Bell Dropdown -->
                    <div class="relative" x-data="{
                        notifOpen: false,
                        items: [
                            { id: 'adm_notif_1', title: 'Campus Notice Broadcasted', desc: 'Annual sports day circular posted to all campus members.', time: 'Just now', icon: '📢', link: '{{ route('admin.announcements') }}', color: 'indigo' },
                            { id: 'adm_notif_2', title: 'Daily Attendance Synchronized', desc: 'Classroom attendance registers updated by faculty.', time: '2 hours ago', icon: '📋', link: '{{ route('admin.attendance') }}', color: 'emerald' },
                            { id: 'adm_notif_3', title: 'New Scholar Admissions Active', desc: 'Directory synced with unified SQLite database.', time: 'Today', icon: '🎓', link: '{{ route('admin.students') }}', color: 'violet' }
                        ],
                        readIds: JSON.parse(localStorage.getItem('schoolnexus_read_notifs_admin') || '[]'),
                        get unreadList() {
                            return this.items.filter(i => !this.readIds.includes(i.id));
                        },
                        get unreadCount() {
                            return this.unreadList.length;
                        },
                        markAsRead(id, navigateUrl = null) {
                            if (!this.readIds.includes(id)) {
                                this.readIds.push(id);
                                localStorage.setItem('schoolnexus_read_notifs_admin', JSON.stringify(this.readIds));
                            }
                            if (navigateUrl) {
                                this.notifOpen = false;
                                window.location.href = navigateUrl;
                            }
                        },
                        markAllRead() {
                            this.readIds = this.items.map(i => i.id);
                            localStorage.setItem('schoolnexus_read_notifs_admin', JSON.stringify(this.readIds));
                            setTimeout(() => { this.notifOpen = false; }, 400);
                        }
                    }">
                        <button @click="notifOpen = !notifOpen" 
                                class="relative p-2 rounded-2xl bg-white/90 hover:bg-slate-100 border border-slate-200/80 text-slate-600 hover:text-slate-900 transition active:scale-95 shadow-xs" 
                                title="System Notifications">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span x-show="unreadCount > 0" class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500 text-white text-[9px] font-extrabold items-center justify-center shadow-xs" x-text="unreadCount"></span>
                            </span>
                        </button>

                        <!-- Notification Dropdown Panel -->
                        <div x-cloak x-show="notifOpen" @click.away="notifOpen = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             class="absolute right-0 mt-3 w-80 sm:w-96 rounded-3xl bg-white/95 backdrop-blur-2xl border border-slate-200/80 shadow-2xl shadow-slate-900/10 p-4 z-50">
                            
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-display font-bold text-slate-900 text-sm">Admin Notifications</h4>
                                    <span x-show="unreadCount > 0" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200" x-text="unreadCount + ' New'"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button x-show="unreadCount > 0" @click="markAllRead()" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 transition">
                                        Mark all read
                                    </button>
                                    <button @click="notifOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg" title="Close">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- List of unread notifications -->
                            <div class="space-y-2.5 max-h-72 overflow-y-auto pr-1">
                                <template x-for="item in unreadList" :key="item.id">
                                    <div class="p-3 rounded-2xl bg-indigo-50/50 hover:bg-indigo-50/80 border border-indigo-100/80 transition flex items-start gap-3 group relative cursor-pointer"
                                         @click="markAsRead(item.id, item.link)">
                                        <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-sm text-xs" x-text="item.icon">
                                        </div>
                                        <div class="min-w-0 flex-1 pr-6">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-indigo-600 transition truncate" x-text="item.title"></p>
                                            <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-2" x-text="item.desc"></p>
                                            <span class="text-[10px] font-mono text-slate-400 mt-1 block" x-text="item.time"></span>
                                        </div>
                                        <button type="button" @click.stop="markAsRead(item.id)" 
                                                class="absolute top-2.5 right-2.5 w-6 h-6 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition"
                                                title="Close and mark as read">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>

                                <!-- Empty State when all notifications are read / closed -->
                                <div x-show="unreadCount === 0" class="py-8 text-center">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-2 text-xl shadow-inner">
                                        ✓
                                    </div>
                                    <h5 class="text-xs font-bold text-slate-800">All Caught Up!</h5>
                                    <p class="text-[11px] text-slate-400 mt-0.5">All notifications have been read and closed.</p>
                                </div>
                            </div>

                            <div class="pt-3 mt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                                <a href="{{ route('admin.announcements') }}" @click="notifOpen = false" class="font-bold text-indigo-600 hover:text-indigo-800 transition">
                                    Open Announcements &rarr;
                                </a>
                                <button type="button" @click="readIds = []; localStorage.removeItem('schoolnexus_read_notifs_admin');" class="text-[10px] text-slate-400 hover:text-slate-600 underline">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center text-xs font-semibold text-slate-600 bg-white/90 px-3.5 py-2 rounded-2xl border border-slate-200/80 shadow-xs">
                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ now()->format('D, M d, Y') }}</span>
                    </div>

                    <!-- Sign Out Button in Header -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 px-3.5 py-2 rounded-2xl bg-rose-50/80 hover:bg-rose-100 text-rose-700 border border-rose-200/80 text-xs font-bold transition active:scale-95 shadow-xs group" title="Sign Out of Admin Portal">
                            <svg class="w-4 h-4 text-rose-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>

                    <!-- Mobile Profile Circle -->
                    <div class="w-9 h-9 lg:hidden rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white font-bold text-xs flex items-center justify-center shadow-md shadow-indigo-500/20">
                        {{ substr($activeAdmin->name ?? 'A', 0, 2) }}
                    </div>
                </div>
            </header>

            <!-- Main Content Container with bottom padding for mobile app bar -->
            <main class="flex-1 min-h-0 overflow-y-auto px-4 py-6 sm:p-6 lg:p-10 pb-28 lg:pb-12">
                <div class="max-w-7xl mx-auto w-full min-w-0">
                <!-- Alerts / Flash Messages -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms class="mb-5 bg-emerald-50/90 border border-emerald-200/80 p-4 rounded-2xl shadow-sm flex items-center justify-between backdrop-blur-sm animate-fade-in">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/20 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-xs sm:text-sm font-semibold text-emerald-900">{{ session('success') }}</span>
                        </div>
                        <button type="button" @click="show = false" class="p-1 rounded-xl text-emerald-600 hover:text-emerald-900 hover:bg-emerald-100 transition" title="Dismiss notification">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="mb-5 bg-rose-50/90 border border-rose-200/80 p-4 rounded-2xl shadow-sm backdrop-blur-sm">
                        <div class="flex items-center space-x-2 text-rose-900 font-bold text-xs sm:text-sm mb-1.5">
                            <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Please check the submission errors:</span>
                        </div>
                        <ul class="list-disc pl-5 text-xs text-rose-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
                </div>
            </main>

            <!-- Native-App-Style Fixed Bottom Navigation Bar for Mobile Phones (lg:hidden) -->
            <nav class="mobile-bottom-nav lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-xl border-t border-slate-200/80 px-2 py-2 flex items-center justify-around shadow-[0_-4px_25px_rgba(0,0,0,0.06)] pb-[max(0.5rem,env(safe-area-inset-bottom))]">
                <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-[10px]">Dashboard</span>
                </a>

                <a href="{{ route('admin.students') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 {{ request()->routeIs('admin.students*') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="text-[10px]">Students</span>
                </a>

                <a href="{{ route('admin.attendance') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 {{ request()->routeIs('admin.attendance*') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span class="text-[10px]">Attendance</span>
                </a>

                <a href="{{ route('admin.homework') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 {{ request()->routeIs('admin.homework*') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="text-[10px]">Homework</span>
                </a>

                <button @click="sidebarOpen = true" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 text-slate-400 hover:text-slate-700">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <span class="text-[10px]">Menu</span>
                </button>
            </nav>
        </div>
    </div>
</body>
</html>

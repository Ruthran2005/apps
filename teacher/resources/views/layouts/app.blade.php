<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#022c22">
    <title>@yield('title', 'Teacher Portal') — SchoolNexus</title>
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
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            900: '#064e3b',
                            950: '#022c22',
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
    </style>
</head>
<body class="h-full font-sans text-slate-800 antialiased selection:bg-emerald-500 selection:text-white" x-data="{ sidebarOpen: false }">
    <div class="min-h-full flex flex-col lg:flex-row">
        <!-- Mobile Sidebar Backdrop with Blur -->
        <div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 bg-slate-950/75 backdrop-blur-md lg:hidden"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] bg-gradient-to-b from-slate-950 via-emerald-950 to-slate-950 text-white flex flex-col transition-transform duration-300 ease-out lg:translate-x-0 lg:static lg:inset-0 border-r border-emerald-900/60 shadow-[10px_0_30px_rgba(0,0,0,0.3)]">
            
            <!-- Brand -->
            <div class="h-16 sm:h-20 flex items-center justify-between px-6 border-b border-emerald-900/60">
                <a href="{{ route('teacher.dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-500/40 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-display text-lg font-bold tracking-tight text-white flex items-center gap-1.5">
                            SchoolNexus
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        </span>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-300 bg-emerald-500/10 px-2 py-0.5 rounded-md border border-emerald-500/20">Faculty Portal</span>
                        </div>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-emerald-400 hover:text-white p-2 rounded-xl active:bg-slate-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <span class="px-3 text-[10px] font-bold uppercase tracking-widest text-emerald-400/60 block mb-2">Teacher Workspace</span>

                <!-- 1. Dashboard -->
                <a href="{{ route('teacher.dashboard') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 {{ request()->routeIs('teacher.dashboard') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-emerald-200 hover:bg-emerald-900/60 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('teacher.dashboard') ? 'text-white' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>

                <!-- 2. My Classes -->
                <a href="{{ route('teacher.classes') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 {{ request()->routeIs('teacher.classes*') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-emerald-200 hover:bg-emerald-900/60 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('teacher.classes*') ? 'text-white' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    My Classes
                </a>

                <!-- 3. Timetable -->
                <a href="{{ route('teacher.timetable') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 {{ request()->routeIs('teacher.timetable*') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-emerald-200 hover:bg-emerald-900/60 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('teacher.timetable*') ? 'text-white' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Timetable
                </a>

                <!-- 4. Attendance -->
                <a href="{{ route('teacher.attendance') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 {{ request()->routeIs('teacher.attendance*') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-emerald-200 hover:bg-emerald-900/60 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('teacher.attendance*') ? 'text-white' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Attendance
                </a>

                <!-- 5. Homework -->
                <a href="{{ route('teacher.homework') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 {{ request()->routeIs('teacher.homework*') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-emerald-200 hover:bg-emerald-900/60 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('teacher.homework*') ? 'text-white' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Homework
                </a>

                <!-- 6. Announcements -->
                <a href="{{ route('teacher.announcements') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 {{ request()->routeIs('teacher.announcements*') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-emerald-200 hover:bg-emerald-900/60 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('teacher.announcements*') ? 'text-white' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    Announcements
                </a>

                <!-- 7. Change Password -->
                <a href="{{ route('teacher.change-password') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 {{ request()->routeIs('teacher.change-password*') ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-emerald-200 hover:bg-emerald-900/60 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('teacher.change-password*') ? 'text-white' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    Change Password
                </a>
            </nav>

            <!-- User Footer with Profile Switcher -->
            <div class="p-4 border-t border-emerald-900/80 bg-slate-950/40" x-data="{ switcher: false }">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-500 text-white font-bold flex items-center justify-center shadow-md">
                                {{ substr($teacher->user->name ?? 'T', 0, 2) }}
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-slate-950"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-white truncate">{{ $teacher->user->name ?? 'Teacher' }}</p>
                            <p class="text-[11px] text-emerald-400 font-mono">{{ $teacher->employee_id ?? 'TCH' }}</p>
                        </div>
                    </div>
                    <button @click="switcher = !switcher" title="Switch Teacher" class="text-emerald-400 hover:text-white p-1 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                    </button>
                </div>

                <!-- Teacher Switch Popover -->
                <div x-cloak x-show="switcher" @click.away="switcher = false" class="mt-3 p-2 bg-slate-900 rounded-2xl border border-emerald-900 space-y-1 text-xs shadow-xl">
                    <span class="text-[10px] uppercase font-bold text-emerald-400 block px-2 py-1">Switch Faculty Context:</span>
                    <a href="{{ route('teacher.switch', 1) }}" class="block px-2.5 py-1.5 rounded-xl hover:bg-emerald-950 text-emerald-200">Dr. Sarah Jenkins (Physics/STEM)</a>
                    <a href="{{ route('teacher.switch', 2) }}" class="block px-2.5 py-1.5 rounded-xl hover:bg-emerald-950 text-emerald-200">Prof. Marcus Vance (Math/CS)</a>
                    <a href="{{ route('teacher.switch', 3) }}" class="block px-2.5 py-1.5 rounded-xl hover:bg-emerald-950 text-emerald-200">Ms. Elena Rostova (English)</a>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Glassmorphic Top Navbar -->
            <header class="h-16 sm:h-20 backdrop-blur-xl bg-white/85 border-b border-slate-200/70 flex items-center justify-between px-4 sm:px-6 lg:px-10 sticky top-0 z-30 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.03)]">
                <div class="flex items-center space-x-3">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-700 hover:text-slate-950 p-2 -ml-1 rounded-2xl hover:bg-slate-100 active:scale-95 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div>
                        <h1 class="font-display text-lg sm:text-xl font-bold tracking-tight text-slate-900 leading-tight">@yield('header_title', 'Faculty Workspace')</h1>
                        <p class="text-xs text-slate-500 hidden sm:flex items-center gap-1.5 mt-0.5">
                            <span>Faculty:</span>
                            <span class="font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/60">{{ $teacher->user->name ?? 'Dr. Sarah Jenkins' }}</span>
                            <span>&bull; {{ $teacher->specialization ?? 'Faculty' }}</span>
                        </p>
                    </div>
                </div>

                <!-- Right Action Bar -->
                <div class="flex items-center space-x-3">
                    <div class="hidden sm:flex items-center text-xs font-semibold text-slate-500 bg-slate-100/80 px-3.5 py-2 rounded-2xl border border-slate-200/60 shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ now()->format('D, M d') }}</span>
                    </div>

                    <!-- Mobile Profile Circle -->
                    <div class="w-9 h-9 lg:hidden rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md shadow-emerald-500/20">
                        {{ substr($teacher->user->name ?? 'T', 0, 2) }}
                    </div>
                </div>
            </header>

            <!-- Main Content Container with bottom padding for mobile app bar -->
            <main class="flex-1 overflow-y-auto px-4 py-5 sm:p-6 lg:p-10 pb-28 lg:pb-10">
                @if(session('success'))
                    <div class="mb-5 bg-emerald-50/90 border border-emerald-200/80 p-4 rounded-2xl shadow-sm flex items-center space-x-3 backdrop-blur-sm">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/20 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-emerald-900">{{ session('success') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Native-App-Style Fixed Bottom Navigation Bar for Mobile Phones (lg:hidden) -->
            <nav class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-xl border-t border-slate-200/80 px-2 py-2 flex items-center justify-around shadow-[0_-4px_25px_rgba(0,0,0,0.06)] pb-[max(0.5rem,env(safe-area-inset-bottom))]">
                <a href="{{ route('teacher.dashboard') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 {{ request()->routeIs('teacher.dashboard') ? 'text-emerald-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-[10px]">Dashboard</span>
                </a>

                <a href="{{ route('teacher.classes') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 {{ request()->routeIs('teacher.classes*') ? 'text-emerald-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span class="text-[10px]">Classes</span>
                </a>

                <a href="{{ route('teacher.attendance') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 {{ request()->routeIs('teacher.attendance*') ? 'text-emerald-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span class="text-[10px]">Attendance</span>
                </a>

                <a href="{{ route('teacher.homework') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 {{ request()->routeIs('teacher.homework*') ? 'text-emerald-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
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

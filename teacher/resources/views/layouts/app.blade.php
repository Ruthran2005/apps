<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
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
<body class="h-full font-sans text-slate-800 antialiased selection:bg-emerald-500 selection:text-white" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false" :class="sidebarOpen ? 'overflow-hidden lg:overflow-auto' : ''">
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
            <div class="absolute -top-20 -left-20 w-60 h-60 bg-emerald-600/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-20 -right-20 w-60 h-60 bg-teal-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Brand -->
            <div class="h-16 sm:h-20 shrink-0 flex items-center justify-between px-6 border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-md relative z-10">
                <a href="{{ route('teacher.dashboard') }}" class="flex items-center space-x-3.5 group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-500/40 ring-1 ring-white/20 group-hover:scale-105 group-hover:shadow-emerald-500/60 transition-all duration-200">
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
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-300 bg-emerald-500/10 px-2 py-0.5 rounded-md border border-emerald-500/20">Faculty Workspace</span>
                        </div>
                    </div>
                </a>
                <button @click="sidebarOpen = false" aria-label="Close navigation menu" class="lg:hidden text-slate-400 hover:text-white p-2 rounded-xl hover:bg-slate-800/80 active:scale-95 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Navigation Links (Categorized Enterprise Suite) -->
            <nav class="flex-1 px-3.5 py-4 space-y-1 overflow-y-auto relative z-10 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                
                <!-- Category 1: Teaching & Classroom -->
                <div class="px-3 pt-1 pb-1.5 text-[10px] font-extrabold uppercase tracking-widest text-slate-500 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Classroom & Teaching</span>
                </div>

                <!-- 1. Dashboard -->
                <a href="{{ route('teacher.dashboard') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.dashboard') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.dashboard') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <span>Dashboard</span>
                    @if(request()->routeIs('teacher.dashboard'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 2. My Classes -->
                <a href="{{ route('teacher.classes') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.classes*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.classes*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <span>My Classes</span>
                    @if(request()->routeIs('teacher.classes*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 3. Timetable -->
                <a href="{{ route('teacher.timetable') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.timetable*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.timetable*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span>Timetable</span>
                    @if(request()->routeIs('teacher.timetable*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 4. Attendance -->
                <a href="{{ route('teacher.attendance') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.attendance*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.attendance*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <span>Attendance</span>
                    @if(request()->routeIs('teacher.attendance*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 5. Homework & Assignments -->
                <a href="{{ route('teacher.homework') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.homework*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.homework*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span>Homework</span>
                    @if(request()->routeIs('teacher.homework*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 6. Quizzes & Tests -->
                <a href="{{ route('teacher.quizzes') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.quizzes*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.quizzes*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <span>Quizzes & Tests</span>
                    @if(request()->routeIs('teacher.quizzes*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 7. Study Materials -->
                <a href="{{ route('teacher.study-materials') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.study-materials*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.study-materials*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    </div>
                    <span>Study Materials</span>
                    @if(request()->routeIs('teacher.study-materials*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 8. Class Diary -->
                <a href="{{ route('teacher.class-diary') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.class-diary*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.class-diary*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span>Class Diary</span>
                    @if(request()->routeIs('teacher.class-diary*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- Category 2: Assessment & Students -->
                <div class="px-3 pt-4 pb-1.5 text-[10px] font-extrabold uppercase tracking-widest text-slate-500 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                    <span>Assessment & Students</span>
                </div>

                <!-- 9. Exam Marks & Remarks -->
                <a href="{{ route('teacher.grades') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.grades*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.grades*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <span>Exam Marks</span>
                    @if(request()->routeIs('teacher.grades*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 10. Student Performance Analytics -->
                <a href="{{ route('teacher.analytics') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.analytics*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.analytics*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <span>Performance Analytics</span>
                    @if(request()->routeIs('teacher.analytics*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 11. Parent Messages -->
                <a href="{{ route('teacher.communications') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.communications*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.communications*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <span>Parent Messages</span>
                    @if(request()->routeIs('teacher.communications*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- Category 3: Duties & Administration -->
                <div class="px-3 pt-4 pb-1.5 text-[10px] font-extrabold uppercase tracking-widest text-slate-500 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                    <span>Duties & Administration</span>
                </div>

                <!-- 12. Apply for Leave -->
                <a href="{{ route('teacher.leaves') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.leaves*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.leaves*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span>Apply for Leave</span>
                    @if(request()->routeIs('teacher.leaves*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 13. Duty Roster -->
                <a href="{{ route('teacher.duty-roster') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.duty-roster*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.duty-roster*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <span>Substitute & Duty Roster</span>
                    @if(request()->routeIs('teacher.duty-roster*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 14. Exam Duty & Invigilation -->
                <a href="{{ route('teacher.invigilation') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.invigilation*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.invigilation*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <span>Exam Invigilation</span>
                    @if(request()->routeIs('teacher.invigilation*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 15. Salary Slips -->
                <a href="{{ route('teacher.salary-slips') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.salary-slips*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.salary-slips*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span>Salary Slips</span>
                    @if(request()->routeIs('teacher.salary-slips*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 16. Academic Calendar & Holidays -->
                <a href="{{ route('teacher.calendar') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.calendar*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.calendar*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span>Events & Holidays</span>
                    @if(request()->routeIs('teacher.calendar*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 17. Announcements -->
                <a href="{{ route('teacher.announcements') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.announcements*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.announcements*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <span>Announcements</span>
                    @if(request()->routeIs('teacher.announcements*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- Category 4: Account & Security -->
                <div class="px-3 pt-4 pb-1.5 text-[10px] font-extrabold uppercase tracking-widest text-slate-500 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span>
                    <span>Account & Security</span>
                </div>

                <!-- 18. Profile -->
                <a href="{{ route('teacher.profile') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.profile*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.profile*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span>My Profile</span>
                    @if(request()->routeIs('teacher.profile*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 19. Change Password -->
                <a href="{{ route('teacher.change-password') }}" 
                   class="relative flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('teacher.change-password*') ? 'bg-gradient-to-r from-emerald-600 via-emerald-600 to-teal-600 text-white font-semibold shadow-lg shadow-emerald-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center mr-2.5 transition-colors {{ request()->routeIs('teacher.change-password*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                    <span>Change Password</span>
                    @if(request()->routeIs('teacher.change-password*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
            </nav>

            <!-- User Profile & Sign Out Footer -->
            <div class="shrink-0 p-3.5 border-t border-slate-800/80 bg-slate-950/90 backdrop-blur-md relative z-10">
                <div class="p-3 rounded-2xl bg-slate-900/80 border border-slate-800/80 flex items-center justify-between shadow-inner">
                    <a href="{{ route('teacher.profile') }}" class="flex items-center space-x-3 min-w-0 group hover:opacity-90 transition">
                        <div class="relative shrink-0">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                                {{ substr($teacher->user->name ?? 'T', 0, 2) }}
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-400 ring-2 ring-slate-950"></span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-white truncate group-hover:text-emerald-300 transition-colors">{{ $teacher->user->name ?? 'Teacher' }}</p>
                            <span class="inline-block text-[10px] font-semibold text-emerald-400 font-mono truncate">{{ $teacher->employee_id ?? 'Faculty' }}</span>
                        </div>
                    </a>
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
            <!-- Ambient Background Lights for Modern Academic Workspace Feel -->
            <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-gradient-to-bl from-emerald-500/10 via-teal-500/5 to-transparent rounded-full blur-3xl pointer-events-none -z-10"></div>
            <div class="fixed bottom-0 left-60 w-[500px] h-[500px] bg-gradient-to-tr from-teal-600/5 via-emerald-500/5 to-transparent rounded-full blur-3xl pointer-events-none -z-10"></div>

            <!-- Glassmorphic Top Navbar -->
            <header class="h-16 sm:h-20 shrink-0 backdrop-blur-2xl bg-white/85 border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 lg:px-10 sticky top-0 z-30 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)]">
                <div class="flex items-center space-x-3.5 min-w-0">
                    <button @click="sidebarOpen = true" aria-label="Open navigation menu" :aria-expanded="sidebarOpen.toString()" aria-controls="portal-navigation" class="lg:hidden text-slate-700 hover:text-slate-950 p-2 -ml-1 rounded-2xl hover:bg-slate-100 active:scale-95 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="min-w-0">
                        <div class="hidden sm:flex items-center gap-1.5 text-[11px] font-semibold text-slate-400 mb-0.5">
                            <span class="hover:text-slate-600 cursor-pointer">SchoolNexus</span>
                            <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            <span class="text-emerald-600 font-bold">Faculty Portal</span>
                        </div>
                        <h1 class="font-display text-lg sm:text-2xl font-extrabold tracking-tight text-slate-900 leading-tight truncate">@yield('header_title', 'Faculty Workspace')</h1>
                    </div>
                </div>

                <!-- Right Action Bar -->
                <div class="flex items-center space-x-3 shrink-0">
                    <!-- Faculty Specialization Pill -->
                    <a href="{{ route('teacher.profile') }}" class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/60 text-emerald-800 text-xs font-bold shadow-xs transition" title="View Faculty Profile">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>{{ $teacher->specialization ?? 'Faculty' }}</span>
                    </a>

                    <!-- Notification Bell Dropdown -->
                    <div class="relative" x-data="{
                        notifOpen: false,
                        items: [
                            { id: 'tch_notif_1', title: 'Submissions Awaiting Evaluation', desc: 'Students have turned in answers for coursework.', time: 'Requires review', icon: '📝', link: '{{ route('teacher.homework') }}', color: 'emerald' },
                            { id: 'tch_notif_2', title: 'New Campus Announcement', desc: 'Official circular issued by administration.', time: 'Recent', icon: '📢', link: '{{ route('teacher.announcements') }}', color: 'teal' },
                            { id: 'tch_notif_3', title: 'Today\'s Teaching Schedule', desc: 'View class periods and assigned lecture rooms.', time: 'Active today', icon: '📅', link: '{{ route('teacher.timetable') }}', color: 'slate' }
                        ],
                        readIds: JSON.parse(localStorage.getItem('schoolnexus_read_notifs_teacher') || '[]'),
                        get unreadList() {
                            return this.items.filter(i => !this.readIds.includes(i.id));
                        },
                        get unreadCount() {
                            return this.unreadList.length;
                        },
                        markAsRead(id, navigateUrl = null) {
                            if (!this.readIds.includes(id)) {
                                this.readIds.push(id);
                                localStorage.setItem('schoolnexus_read_notifs_teacher', JSON.stringify(this.readIds));
                            }
                            if (navigateUrl) {
                                this.notifOpen = false;
                                window.location.href = navigateUrl;
                            }
                        },
                        markAllRead() {
                            this.readIds = this.items.map(i => i.id);
                            localStorage.setItem('schoolnexus_read_notifs_teacher', JSON.stringify(this.readIds));
                            setTimeout(() => { this.notifOpen = false; }, 400);
                        }
                    }">
                        <button @click="notifOpen = !notifOpen" 
                                class="relative p-2 rounded-2xl bg-white/90 hover:bg-emerald-50 border border-slate-200/80 text-slate-600 hover:text-emerald-700 transition active:scale-95 shadow-xs" 
                                title="Faculty Notifications">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span x-show="unreadCount > 0" class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-600 text-white text-[9px] font-extrabold items-center justify-center shadow-xs" x-text="unreadCount"></span>
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
                             class="absolute right-0 mt-3 w-80 sm:w-96 rounded-3xl bg-white/95 backdrop-blur-2xl border border-slate-200/80 shadow-2xl shadow-emerald-950/10 p-4 z-50">
                            
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-display font-bold text-slate-900 text-sm">Faculty Notifications</h4>
                                    <span x-show="unreadCount > 0" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" x-text="unreadCount + ' New'"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button x-show="unreadCount > 0" @click="markAllRead()" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-800 transition">
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
                                    <div class="p-3 rounded-2xl bg-emerald-50/50 hover:bg-emerald-50/80 border border-emerald-100/80 transition flex items-start gap-3 group relative cursor-pointer"
                                         @click="markAsRead(item.id, item.link)">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm text-xs" x-text="item.icon">
                                        </div>
                                        <div class="min-w-0 flex-1 pr-6">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-emerald-700 transition truncate" x-text="item.title"></p>
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
                                    <p class="text-[11px] text-slate-400 mt-0.5">All faculty notifications have been read and closed.</p>
                                </div>
                            </div>

                            <div class="pt-3 mt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                                <a href="{{ route('teacher.dashboard') }}" @click="notifOpen = false" class="font-bold text-emerald-600 hover:text-emerald-800 transition">
                                    View Dashboard Alerts &rarr;
                                </a>
                                <button type="button" @click="readIds = []; localStorage.removeItem('schoolnexus_read_notifs_teacher');" class="text-[10px] text-slate-400 hover:text-slate-600 underline">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center text-xs font-semibold text-slate-600 bg-white/90 px-3.5 py-2 rounded-2xl border border-slate-200/80 shadow-xs">
                        <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ now()->format('D, M d, Y') }}</span>
                    </div>

                    <!-- Sign Out Button in Header -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 px-3.5 py-2 rounded-2xl bg-rose-50/80 hover:bg-rose-100 text-rose-700 border border-rose-200/80 text-xs font-bold transition active:scale-95 shadow-xs group" title="Sign Out of Faculty Portal">
                            <svg class="w-4 h-4 text-rose-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>

                    <!-- Mobile Profile Circle -->
                    <a href="{{ route('teacher.profile') }}" class="w-9 h-9 lg:hidden rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white font-bold text-xs flex items-center justify-center shadow-md shadow-emerald-500/20 active:scale-95 transition" title="My Profile">
                        {{ substr($teacher->user->name ?? 'T', 0, 2) }}
                    </a>
                </div>
            </header>

            <!-- Main Content Container with bottom padding for mobile app bar -->
            <main class="flex-1 min-h-0 overflow-y-auto px-4 py-6 sm:p-6 lg:p-10 pb-28 lg:pb-12">
                <div class="max-w-7xl mx-auto w-full min-w-0">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms class="mb-5 bg-emerald-50/90 border border-emerald-200/80 p-4 rounded-2xl shadow-sm flex items-center justify-between backdrop-blur-sm">
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

                @yield('content')
                </div>
            </main>

            <!-- Native-App-Style Fixed Bottom Navigation Bar for Mobile Phones (lg:hidden) -->
            <nav class="mobile-bottom-nav lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-xl border-t border-slate-200/80 px-2 py-2 flex items-center justify-around shadow-[0_-4px_25px_rgba(0,0,0,0.06)] pb-[max(0.5rem,env(safe-area-inset-bottom))]">
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

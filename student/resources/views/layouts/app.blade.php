<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#2e1065">
    <title>@yield('title', 'Student Portal') — SchoolNexus</title>
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
                        student: {
                            50: '#faf5ff',
                            100: '#f3e8ff',
                            400: '#c084fc',
                            500: '#a855f7',
                            600: '#9333ea',
                            700: '#7e22ce',
                            900: '#581c87',
                            950: '#2e1065',
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
<body class="h-full font-sans text-slate-800 antialiased selection:bg-purple-500 selection:text-white" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false" :class="sidebarOpen ? 'overflow-hidden lg:overflow-auto' : ''">
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
            <div class="absolute -top-20 -left-20 w-60 h-60 bg-purple-600/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-20 -right-20 w-60 h-60 bg-fuchsia-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Brand -->
            <div class="h-16 sm:h-20 shrink-0 flex items-center justify-between px-6 border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-md relative z-10">
                <a href="{{ route('student.dashboard') }}" class="flex items-center space-x-3.5 group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-purple-500 via-violet-500 to-fuchsia-400 flex items-center justify-center shadow-lg shadow-purple-500/40 ring-1 ring-white/20 group-hover:scale-105 group-hover:shadow-purple-500/60 transition-all duration-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-display text-lg font-bold tracking-tight text-white flex items-center gap-1.5">
                            SchoolNexus
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-ping"></span>
                        </span>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-purple-300 bg-purple-500/10 px-2 py-0.5 rounded-md border border-purple-500/20">Student Portal</span>
                        </div>
                    </div>
                </a>
                <button @click="sidebarOpen = false" aria-label="Close navigation menu" class="lg:hidden text-slate-400 hover:text-white p-2 rounded-xl hover:bg-slate-800/80 active:scale-95 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Navigation Links (7 Exact Tabs with Tactile Accent Tiles) -->
            <nav class="flex-1 px-3.5 py-4 space-y-1 overflow-y-auto relative z-10 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                <div class="px-3 pt-1 pb-2 text-[10px] font-extrabold uppercase tracking-widest text-slate-500 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                    <span>Student Workspace</span>
                </div>

                <!-- 1. Dashboard -->
                <a href="{{ route('student.dashboard') }}" 
                   class="relative flex items-center px-3 py-2.5 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('student.dashboard') ? 'bg-gradient-to-r from-purple-600 via-purple-600 to-indigo-600 text-white font-semibold shadow-lg shadow-purple-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('student.dashboard') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <span>Dashboard</span>
                    @if(request()->routeIs('student.dashboard'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 2. Timetable -->
                <a href="{{ route('student.timetable') }}" 
                   class="relative flex items-center px-3 py-2.5 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('student.timetable*') ? 'bg-gradient-to-r from-purple-600 via-purple-600 to-indigo-600 text-white font-semibold shadow-lg shadow-purple-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('student.timetable*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span>Timetable</span>
                    @if(request()->routeIs('student.timetable*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 3. Attendance -->
                <a href="{{ route('student.attendance') }}" 
                   class="relative flex items-center px-3 py-2.5 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('student.attendance*') ? 'bg-gradient-to-r from-purple-600 via-purple-600 to-indigo-600 text-white font-semibold shadow-lg shadow-purple-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('student.attendance*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <span>Attendance</span>
                    @if(request()->routeIs('student.attendance*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 4. Homework -->
                <a href="{{ route('student.homework') }}" 
                   class="relative flex items-center px-3 py-2.5 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('student.homework*') ? 'bg-gradient-to-r from-purple-600 via-purple-600 to-indigo-600 text-white font-semibold shadow-lg shadow-purple-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('student.homework*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span>Homework</span>
                    @if(request()->routeIs('student.homework*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 5. Announcements -->
                <a href="{{ route('student.announcements') }}" 
                   class="relative flex items-center px-3 py-2.5 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('student.announcements*') ? 'bg-gradient-to-r from-purple-600 via-purple-600 to-indigo-600 text-white font-semibold shadow-lg shadow-purple-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('student.announcements*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <span>Announcements</span>
                    @if(request()->routeIs('student.announcements*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <div class="px-3 pt-5 pb-2 text-[10px] font-extrabold uppercase tracking-widest text-slate-500 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-fuchsia-400"></span>
                    <span>Account & Profile</span>
                </div>

                <!-- 6. Profile -->
                <a href="{{ route('student.profile') }}" 
                   class="relative flex items-center px-3 py-2.5 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('student.profile*') ? 'bg-gradient-to-r from-purple-600 via-purple-600 to-indigo-600 text-white font-semibold shadow-lg shadow-purple-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('student.profile*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span>Profile</span>
                    @if(request()->routeIs('student.profile*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>

                <!-- 7. Change Password -->
                <a href="{{ route('student.change-password') }}" 
                   class="relative flex items-center px-3 py-2.5 text-sm font-medium rounded-2xl transition-all duration-200 group {{ request()->routeIs('student.change-password*') ? 'bg-gradient-to-r from-purple-600 via-purple-600 to-indigo-600 text-white font-semibold shadow-lg shadow-purple-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60 hover:translate-x-1' }}">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center mr-3 transition-colors {{ request()->routeIs('student.change-password*') ? 'bg-white/20 text-white shadow-inner' : 'bg-slate-800/60 text-slate-400 group-hover:bg-slate-800 group-hover:text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                    <span>Change Password</span>
                    @if(request()->routeIs('student.change-password*'))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
            </nav>

            <!-- User Profile & Sign Out Footer -->
            <div class="shrink-0 p-3.5 border-t border-slate-800/80 bg-slate-950/90 backdrop-blur-md relative z-10">
                <div class="p-3 rounded-2xl bg-slate-900/80 border border-slate-800/80 flex items-center justify-between shadow-inner">
                    <div class="flex items-center space-x-3 min-w-0">
                        <div class="relative shrink-0">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-purple-500 to-fuchsia-500 text-white font-bold text-xs flex items-center justify-center shadow-md shadow-purple-500/20">
                                {{ substr($student->user->name ?? 'S', 0, 2) }}
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-400 ring-2 ring-slate-950"></span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-white truncate">{{ $student->user->name ?? 'Student' }}</p>
                            <span class="inline-block text-[10px] font-semibold text-purple-400 font-mono truncate">{{ $student->admission_number ?? 'Student' }}</span>
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
            <!-- Ambient Background Lights for Sleek Student Campus Feel -->
            <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-gradient-to-bl from-purple-500/10 via-fuchsia-500/5 to-transparent rounded-full blur-3xl pointer-events-none -z-10"></div>
            <div class="fixed bottom-0 left-60 w-[500px] h-[500px] bg-gradient-to-tr from-indigo-600/5 via-purple-500/5 to-transparent rounded-full blur-3xl pointer-events-none -z-10"></div>

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
                            <span class="text-purple-600 font-bold">Student App</span>
                        </div>
                        <h1 class="font-display text-lg sm:text-2xl font-extrabold tracking-tight text-slate-900 leading-tight truncate">@yield('header_title', 'Student Dashboard')</h1>
                    </div>
                </div>

                <!-- Right Action Bar -->
                <div class="flex items-center space-x-3 shrink-0">
                    <!-- Grade & Section Badge -->
                    <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-purple-50 border border-purple-200/60 text-purple-700 text-xs font-bold shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                        <span>{{ $student->schoolClass->name ?? 'Grade' }} - {{ $student->section->name ?? 'A' }}</span>
                    </div>

                    <!-- Notification Bell Dropdown -->
                    <div class="relative" x-data="{
                        notifOpen: false,
                        items: [
                            { id: 'std_notif_1', title: 'Active Homework Assigned', desc: 'Check coursework deadlines and upload your answers.', time: 'Due this week', icon: '📚', link: '{{ route('student.homework') }}', color: 'purple' },
                            { id: 'std_notif_2', title: 'Official Campus Bulletin', desc: 'New announcement published by school administration.', time: 'Recent notice', icon: '📢', link: '{{ route('student.announcements') }}', color: 'rose' },
                            { id: 'std_notif_3', title: 'Attendance Status Verified', desc: 'Daily attendance record updated in real-time.', time: 'Today', icon: '✅', link: '{{ route('student.attendance') }}', color: 'emerald' }
                        ],
                        readIds: JSON.parse(localStorage.getItem('schoolnexus_read_notifs_student') || '[]'),
                        get unreadList() {
                            return this.items.filter(i => !this.readIds.includes(i.id));
                        },
                        get unreadCount() {
                            return this.unreadList.length;
                        },
                        markAsRead(id, navigateUrl = null) {
                            if (!this.readIds.includes(id)) {
                                this.readIds.push(id);
                                localStorage.setItem('schoolnexus_read_notifs_student', JSON.stringify(this.readIds));
                            }
                            if (navigateUrl) {
                                this.notifOpen = false;
                                window.location.href = navigateUrl;
                            }
                        },
                        markAllRead() {
                            this.readIds = this.items.map(i => i.id);
                            localStorage.setItem('schoolnexus_read_notifs_student', JSON.stringify(this.readIds));
                            setTimeout(() => { this.notifOpen = false; }, 400);
                        }
                    }">
                        <button @click="notifOpen = !notifOpen" 
                                class="relative p-2 rounded-2xl bg-white/90 hover:bg-purple-50 border border-slate-200/80 text-slate-600 hover:text-purple-700 transition active:scale-95 shadow-xs" 
                                title="Student Notifications">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span x-show="unreadCount > 0" class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-purple-600 text-white text-[9px] font-extrabold items-center justify-center shadow-xs" x-text="unreadCount"></span>
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
                             class="absolute right-0 mt-3 w-80 sm:w-96 rounded-3xl bg-white/95 backdrop-blur-2xl border border-slate-200/80 shadow-2xl shadow-purple-950/10 p-4 z-50">
                            
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-display font-bold text-slate-900 text-sm">Student Notifications</h4>
                                    <span x-show="unreadCount > 0" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200" x-text="unreadCount + ' New'"></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button x-show="unreadCount > 0" @click="markAllRead()" class="text-[11px] font-bold text-purple-600 hover:text-purple-800 transition">
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
                                    <div class="p-3 rounded-2xl bg-purple-50/50 hover:bg-purple-50/80 border border-purple-100/80 transition flex items-start gap-3 group relative cursor-pointer"
                                         @click="markAsRead(item.id, item.link)">
                                        <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center shrink-0 shadow-sm text-xs" x-text="item.icon">
                                        </div>
                                        <div class="min-w-0 flex-1 pr-6">
                                            <p class="text-xs font-bold text-slate-900 group-hover:text-purple-700 transition truncate" x-text="item.title"></p>
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
                                    <p class="text-[11px] text-slate-400 mt-0.5">All student notifications have been read and closed.</p>
                                </div>
                            </div>

                            <div class="pt-3 mt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                                <a href="{{ route('student.dashboard') }}" @click="notifOpen = false" class="font-bold text-purple-600 hover:text-purple-800 transition">
                                    View Student Dashboard &rarr;
                                </a>
                                <button type="button" @click="readIds = []; localStorage.removeItem('schoolnexus_read_notifs_student');" class="text-[10px] text-slate-400 hover:text-slate-600 underline">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center text-xs font-semibold text-slate-600 bg-white/90 px-3.5 py-2 rounded-2xl border border-slate-200/80 shadow-xs">
                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ now()->format('D, M d, Y') }}</span>
                    </div>

                    <!-- Sign Out Button in Header -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 px-3.5 py-2 rounded-2xl bg-rose-50/80 hover:bg-rose-100 text-rose-700 border border-rose-200/80 text-xs font-bold transition active:scale-95 shadow-xs group" title="Sign Out of Student Portal">
                            <svg class="w-4 h-4 text-rose-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>

                    <!-- Mobile Profile Circle -->
                    <a href="{{ route('student.profile') }}" class="w-9 h-9 lg:hidden rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-500 text-white font-bold text-xs flex items-center justify-center shadow-md shadow-purple-500/20">
                        {{ substr($student->user->name ?? 'S', 0, 2) }}
                    </a>
                </div>
            </header>

            <!-- Main Content Container with bottom padding for mobile app bar -->
            <main class="flex-1 min-h-0 overflow-y-auto px-4 py-6 sm:p-6 lg:p-10 pb-28 lg:pb-12">
                <div class="max-w-7xl mx-auto w-full min-w-0">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms class="mb-5 bg-purple-50/90 border border-purple-200/80 p-4 rounded-2xl shadow-sm flex items-center justify-between backdrop-blur-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center shadow-md shadow-purple-600/20 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-xs sm:text-sm font-semibold text-purple-900">{{ session('success') }}</span>
                        </div>
                        <button type="button" @click="show = false" class="p-1 rounded-xl text-purple-600 hover:text-purple-900 hover:bg-purple-100 transition" title="Dismiss notification">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="mb-5 bg-rose-50/90 border border-rose-200/80 p-4 rounded-2xl shadow-sm backdrop-blur-sm">
                        <div class="flex items-center space-x-2 text-rose-900 font-bold text-xs sm:text-sm mb-1.5">
                            <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Please review the errors below:</span>
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
                <a href="{{ route('student.dashboard') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 {{ request()->routeIs('student.dashboard') ? 'text-purple-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-[10px]">Home</span>
                </a>

                <a href="{{ route('student.timetable') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 {{ request()->routeIs('student.timetable*') ? 'text-purple-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="text-[10px]">Timetable</span>
                </a>

                <a href="{{ route('student.attendance') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 {{ request()->routeIs('student.attendance*') ? 'text-purple-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span class="text-[10px]">Attendance</span>
                </a>

                <a href="{{ route('student.homework') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 {{ request()->routeIs('student.homework*') ? 'text-purple-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="text-[10px]">Homework</span>
                </a>

                <a href="{{ route('student.profile') }}" class="flex flex-col items-center justify-center py-1 px-2.5 rounded-2xl transition active:scale-95 {{ request()->routeIs('student.profile*') ? 'text-purple-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21a8 8 0 00-16 0m8-10a4 4 0 100-8 4 4 0 000 8z"></path></svg>
                    <span class="text-[10px]">Profile</span>
                </a>
            </nav>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal Login — SchoolNexus Institutional Suite</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|outfit:500,600,700,800" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .grid-bg {
            background-image: radial-gradient(rgba(99, 102, 241, 0.15) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="min-h-full font-sans antialiased bg-slate-950 text-slate-100 selection:bg-indigo-500 selection:text-white flex flex-col justify-between relative overflow-x-hidden grid-bg"
      x-data="{ email: '', password: '', showPass: false, loading: false }">

    <!-- Ambient glowing gradients -->
    <div class="fixed top-0 left-1/4 w-[500px] h-[500px] bg-indigo-600/15 rounded-full blur-[120px] pointer-events-none -z-10 animate-pulse"></div>
    <div class="fixed bottom-0 right-1/4 w-[500px] h-[500px] bg-violet-600/15 rounded-full blur-[120px] pointer-events-none -z-10 animate-pulse" style="animation-delay: 2s;"></div>

    <!-- Top Minimal Bar -->
    <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between z-20">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-violet-400 flex items-center justify-center shadow-lg shadow-indigo-500/30 ring-1 ring-white/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <span class="font-display font-bold text-lg tracking-tight text-white flex items-center gap-1.5">
                    SchoolNexus
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-ping"></span>
                </span>
                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-300">Enterprise Administration</span>
            </div>
        </div>
    </header>

    <!-- Main Content Area: Split Modern Architecture -->
    <main class="flex-1 flex items-center justify-center px-4 py-8 z-10">
        <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

            <!-- Left Hero Info Panel (Visible on lg screens) -->
            <div class="hidden lg:flex lg:col-span-6 flex-col justify-center space-y-6 pr-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-semibold w-fit">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span>High Security Institutional Access</span>
                </div>

                <div>
                    <h1 class="font-display text-4xl xl:text-5xl font-extrabold text-white tracking-tight leading-tight">
                        Orchestrate Campus Intelligence.
                    </h1>
                    <p class="text-slate-400 text-sm sm:text-base mt-3 leading-relaxed">
                        Securely manage students, faculty, academic timetables, attendance analytics, and role-based permissions in real time.
                    </p>
                </div>

                <!-- Feature badges -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 backdrop-blur-sm">
                        <div class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h4 class="text-xs font-bold text-white">Full Personnel Roster</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Manage students, faculty, and administrative tiers</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 backdrop-blur-sm">
                        <div class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h4 class="text-xs font-bold text-white">Live Attendance Export</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">CSV reports filtered by standard and date range</p>
                    </div>
                </div>
            </div>

            <!-- Right Login Form Card -->
            <div class="w-full lg:col-span-6 max-w-md mx-auto">
                <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-7 sm:p-9 backdrop-blur-2xl shadow-2xl shadow-black/80 relative overflow-hidden">
                    <!-- Subtle top highlight line -->
                    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-transparent via-indigo-500 to-transparent"></div>

                    <!-- Header inside card on mobile -->
                    <div class="mb-6">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-300 text-xs font-bold uppercase tracking-wider mb-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                            Admin Sign-In
                        </div>
                        <h2 class="font-display text-2xl font-bold text-white">Welcome Back</h2>
                        <p class="text-xs text-slate-400 mt-1">Authenticate to access institutional controls</p>
                    </div>

                    <!-- Flash Message -->
                    @if(session('success'))
                        <div class="mb-5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-2xl text-xs sm:text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 bg-rose-500/10 border border-rose-500/30 text-rose-300 px-4 py-3 rounded-2xl text-xs sm:text-sm font-medium">
                            <div class="flex items-center gap-2 mb-1 font-bold text-rose-200">
                                <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Sign In Denied</span>
                            </div>
                            <ul class="list-disc pl-5 space-y-0.5 text-xs">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" autocomplete="off" class="space-y-4" @submit="loading = true"
                          x-init="$nextTick(() => { email = ''; password = ''; if ($refs.emailInput) $refs.emailInput.value = ''; if ($refs.passInput) $refs.passInput.value = ''; })">
                        @csrf

                        <!-- Chrome Autofill Suppressors -->
                        <input type="text" class="hidden" style="display:none" autocomplete="username" tabindex="-1" aria-hidden="true">
                        <input type="password" class="hidden" style="display:none" autocomplete="new-password" tabindex="-1" aria-hidden="true">

                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Administrative Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path></svg>
                                </div>
                                <input type="email" id="email" name="email" x-ref="emailInput" x-model="email" required autofocus autocomplete="off" value=""
                                       class="w-full pl-10 pr-4 py-3 bg-slate-950/80 border border-slate-800 text-white rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition placeholder-slate-600 shadow-inner"
                                       placeholder="">
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input :type="showPass ? 'text' : 'password'" id="password" name="password" x-ref="passInput" x-model="password" required autocomplete="new-password" value=""
                                       class="w-full pl-10 pr-10 py-3 bg-slate-950/80 border border-slate-800 text-white rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition placeholder-slate-600 shadow-inner"
                                       placeholder="">
                                <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 transition">
                                    <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center space-x-2 text-xs text-slate-400 cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded-lg bg-slate-950 border-slate-700 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900">
                                <span>Remember session</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" :disabled="loading"
                                class="w-full mt-2 py-3.5 px-4 bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold rounded-2xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 active:scale-[0.99] transition duration-150 flex items-center justify-center space-x-2 disabled:opacity-50">
                            <span x-show="!loading">Sign In to Admin Hub</span>
                            <span x-show="loading" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span>Authenticating...</span>
                            </span>
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Footer -->
    <footer class="w-full max-w-7xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-600 border-t border-slate-900 z-10 gap-2">
        <span>&copy; {{ date('Y') }} SchoolNexus Institutional Ecosystem. All rights reserved.</span>
        <div class="flex items-center space-x-4">
            <span class="hover:text-slate-400 transition cursor-pointer">Security Protocol</span>
            <span class="hover:text-slate-400 transition cursor-pointer">System Status</span>
        </div>
    </footer>
</body>
</html>

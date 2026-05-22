<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Custom Form Builder - Admin Mesh</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between hidden md:flex border-r border-slate-800">
            <div>
                <div class="h-16 flex items-center px-6 bg-slate-950 font-bold text-lg gap-2 text-indigo-400">
                    <i class="fa-solid fa-cubes-nested"></i>
                    <span>FormCraft OS</span>
                </div>
                <nav class="p-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all">
                        <i class="fa-solid fa-chart-pie w-5"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.forms.create') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-all">
                        <i class="fa-solid fa-wand-magic-sparkles w-5"></i> Form Builder
                    </a>
                </nav>
            </div>
            <div class="mt-auto p-4 border-t border-slate-800 flex items-center justify-between bg-slate-950/40">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center font-bold text-xs text-white">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Node Operator</p>
                        <p class="text-sm font-semibold text-slate-200">{{ Auth::user()->name }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="inline">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-400 transition-colors p-1.5 rounded-lg hover:bg-slate-800/60" title="Terminate Session">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-y-auto">
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-40">
                <h2 class="text-xl font-bold text-slate-800">Control Panel Engine</h2>
                <div class="flex items-center gap-4">
                    <span class="text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full font-semibold flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-ping"></span> Live Mesh
                    </span>
                </div>
            </header>

            <main class="p-8 flex-1">
                @yield('content')
            </main>
        </div>

    </div>

</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FormCraft OS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen flex flex-col justify-center items-center p-4">

    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-600 text-white font-bold text-xl shadow-lg shadow-indigo-200 mb-3">
            FC
        </div>
        <h1 class="text-xl font-bold text-slate-900">FormCraft OS</h1>
        <p class="text-sm text-slate-500 mt-1">Control Panel Engine Authentication</p>
    </div>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-200/80 p-8 relative overflow-hidden">
        
        <h2 class="text-lg font-semibold text-slate-800 mb-6">Account Verification</h2>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all text-sm">
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">Security Access Key</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-indigo-600 hover:underline font-medium">Forgot?</a>
                    @endif
                </div>
                <input type="password" name="password" required
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all text-sm">
            </div>

            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                <label for="remember_me" class="ml-2 text-sm text-slate-500 select-none">Keep session alive</label>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-medium py-2.5 rounded-xl transition-colors text-sm shadow-sm">
                Authenticate Operator
            </button>
        </form>
         <!-- register ka column comment out kardia hai mene ta k isuue na aye -->
        <!-- <div class="mt-6 pt-5 border-t border-slate-100 text-center">
            <p class="text-sm text-slate-500">
                New operator node? 
                <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-semibold ml-1">Create an account</a>
            </p>
        </div> -->
    </div>

    <div class="mt-6 w-full max-w-md bg-amber-50/60 border border-amber-200/70 rounded-2xl p-4 text-center">
        <p class="text-xs font-semibold text-amber-800 uppercase tracking-wider mb-1">💼 Recruiter / Guest Access Mode</p>
        <p class="text-xs text-amber-700/90">
            Email: <span class="font-mono bg-amber-100/80 px-1.5 py-0.5 rounded font-bold">admin@formcraft.com</span> &nbsp;|&nbsp; 
            Password: <span class="font-mono bg-amber-100/80 px-1.5 py-0.5 rounded font-bold">password</span>
        </p>
    </div>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submissions - {{ $form->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#f8fafc] min-h-screen">

    <nav class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <div class="bg-indigo-600 text-white font-bold px-3 py-1.5 rounded-xl text-sm shadow-sm shadow-indigo-200">FC</div>
            <span class="font-bold text-slate-800 text-lg tracking-tight">FormCraft OS</span>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl text-sm transition-colors flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Control Panel
        </a>
    </nav>

    <div class="p-6 max-w-7xl mx-auto mt-6">
        <div class="mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Form Submissions</h1>
                <p class="text-sm text-slate-500 mt-1">Responses for: <span class="font-semibold text-indigo-600">{{ $form->title }}</span></p>
            </div>
            <div class="flex flex-wrap gap-3">
                <form method="GET" action="{{ route('admin.forms.submissions', $form->id) }}" class="flex gap-2">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search submissions..." class="px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500">
                    <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-medium">Search</button>
                </form>
                <a href="{{ route('admin.forms.submissions.export', $form->id) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export CSV
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-sm">
                            <th class="p-4 pl-6 w-24">Entry ID</th>
                            @foreach($form->fields->take(3) as $field)
                                <th class="p-4 uppercase tracking-wider text-xs font-bold text-slate-500">{{ $field->label }}</th>
                            @endforeach
                            <th class="p-4">Submission Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 text-sm">
                        @forelse($submissions as $submission)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="p-4 pl-6 font-semibold text-slate-900">#{{ $submission->id }}</td>
                                
                                @foreach($form->fields->take(3) as $field)
                                    @php 
                                        $valueObj = $submission->values->where('form_field_id', $field->id)->first();
                                        $val = $valueObj ? $valueObj->value : '-';
                                    @endphp
                                    <td class="p-4 truncate max-w-xs">
                                        @if(str_contains($val, 'submissions/attachments/') || str_contains($val, 'attachments/'))
                                            <a href="{{ asset('storage/' . $val) }}" target="_blank" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium hover:underline bg-indigo-50 px-2.5 py-1 rounded-lg text-xs transition-all">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                View Uploaded File
                                            </a>
                                        @else
                                            <span class="text-slate-600 font-medium">{{ $val }}</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="p-4 text-slate-400 font-light">{{ $submission->created_at->format('M d, Y @ H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $form->fields->take(3)->count() + 2 }}" class="p-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        <p class="text-slate-400 font-medium text-base">No responses logged yet for this dynamic architecture.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($submissions->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>
@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center text-xl">
                <i class="fa-solid fa-file-lines"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Deployed Forms</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalForms }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center text-xl">
                <i class="fa-solid fa-database"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Total Data Submissions</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalSubmissions }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Active Architecture</h3>
            <a href="{{ route('admin.forms.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all shadow-md shadow-indigo-200">
                <i class="fa-solid fa-layer-group mr-2"></i> Build New Form
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 font-medium">Form Title</th>
                        <th class="px-6 py-4 font-medium">Public Endpoint</th>
                        <th class="px-6 py-4 font-medium">Entries</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($forms as $form)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $form->title }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('forms.render', $form->slug) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-mono text-xs bg-indigo-50 px-2 py-1 rounded">/forms/{{ $form->slug }} <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i></a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-slate-900 text-white px-2.5 py-1 rounded-md text-xs font-bold">{{ $form->submissions_count }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-emerald-700 bg-emerald-100 border border-emerald-200 px-2.5 py-1 rounded-full text-xs font-semibold"><i class="fa-solid fa-circle text-[8px] mr-1"></i> Online</span>
                        </td>
                        <td class="px-6 py-4 text-right flex items-center justify-end gap-3">
                            <a href="{{ route('admin.forms.submissions', $form->id) }}" class="text-slate-400 hover:text-indigo-600 transition-colors" title="View Data">
                                <i class="fa-solid fa-chart-bar text-lg"></i>
                            </a>

                            <a href="{{ route('admin.forms.edit', $form->id) }}" class="text-slate-400 hover:text-blue-500 transition-colors" title="Edit Form">
                                <i class="fa-solid fa-pen-to-square text-lg"></i>
                            </a>

                            <form action="{{ route('admin.forms.duplicate', $form->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-slate-400 hover:text-violet-600 transition-colors" title="Duplicate Form">
                                    <i class="fa-solid fa-copy text-lg"></i>
                                </button>
                            </form>

                            <form action="{{ route('admin.forms.delete', $form->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this form?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors" title="Delete Schema">
                                    <i class="fa-solid fa-trash-can text-lg"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-folder-open text-4xl text-slate-300 mb-3"></i>
                                <p>No schemas deployed yet. Initialize your first form.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('content')
<div x-data='formBuilder(@json($form))' class="max-w-5xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Edit Architecture</h2>
            <p class="text-sm text-slate-500 mt-1">Updating: <span class="font-bold text-indigo-600" x-text="formData.title"></span></p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.dashboard') }}" class="bg-white border border-slate-300 text-slate-700 px-5 py-2.5 rounded-lg font-medium hover:bg-slate-50 transition-all">Cancel</a>
            
            <button @click="updateForm" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-indigo-700 transition-all flex items-center gap-2 shadow-lg shadow-indigo-200" :disabled="isSaving">
                <i class="fa-solid fa-arrows-rotate" x-show="!isSaving"></i>
                <i class="fa-solid fa-spinner fa-spin" x-show="isSaving"></i>
                <span x-text="isSaving ? 'Updating...' : 'Update Schema'"></span>
            </button>
        </div>
    </div>

    <div x-show="message" class="p-4 rounded-lg text-sm font-medium" :class="isError ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'" style="display: none;">
        <span x-text="message"></span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-700 border-b pb-2">1. Form Identity</h3>
                <div>
                    <label for="form-edit-title" class="block text-xs font-bold text-slate-500 mb-1">FORM TITLE *</label>
                    <input id="form-edit-title" type="text" x-model="formData.title" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label for="form-edit-description" class="block text-xs font-bold text-slate-500 mb-1">DESCRIPTION</label>
                    <textarea id="form-edit-description" x-model="formData.description" rows="2" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm min-h-[300px]">
                <h3 class="font-bold text-slate-700 border-b pb-2 mb-4">2. Schema Builder</h3>
                <template x-for="(field, index) in formData.fields" :key="index">
                    <div class="flex items-start gap-4 p-4 border border-slate-100 bg-slate-50 rounded-lg mb-3 relative group">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label :for="'field-label-' + index" class="block text-[10px] font-bold text-slate-400 mb-1">FIELD LABEL</label>
                                <input :id="'field-label-' + index" type="text" x-model="field.label" class="w-full text-sm border border-slate-200 rounded px-3 py-1.5 outline-none">
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <label :for="'field-type-' + index" class="block text-[10px] font-bold text-slate-400 mb-1">INPUT TYPE</label>
                                    <select :id="'field-type-' + index" x-model="field.type" class="w-full text-sm border border-slate-200 rounded px-3 py-1.5 outline-none bg-white">
                                        <option value="text">Short Text</option>
                                        <option value="textarea">Long Text / Paragraph</option>
                                        <option value="email">Email Address</option>
                                        <option value="number">Number</option>
                                        <option value="file">File Upload</option>
                                    </select>
                                </div>
                                <div class="mt-5">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" :checked="field.is_required == 1 || field.is_required == true" @change="field.is_required = $event.target.checked" class="w-4 h-4 text-indigo-600 rounded">
                                        <span class="text-xs font-bold text-slate-600">Required</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button @click="removeField(index)" class="text-rose-400 hover:text-rose-600 bg-white shadow-sm p-2 rounded absolute -right-2 -top-2 hidden group-hover:block transition-all">
                            <i class="fa-solid fa-trash-can text-sm"></i>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <div class="space-y-4 sticky top-24">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-700 border-b pb-2 mb-4 text-sm">Toolbox Nodes</h3>
                <div class="grid grid-cols-1 gap-2">
                    <button @click="addField('text')" class="text-left px-4 py-2 border rounded-lg hover:border-indigo-500 hover:bg-indigo-50 text-sm text-slate-600"><i class="fa-solid fa-font text-indigo-400 w-4"></i> Short Text</button>
                    <button @click="addField('email')" class="text-left px-4 py-2 border rounded-lg hover:border-indigo-500 hover:bg-indigo-50 text-sm text-slate-600"><i class="fa-solid fa-envelope text-indigo-400 w-4"></i> Email Address</button>
                    <button @click="addField('number')" class="text-left px-4 py-2 border rounded-lg hover:border-indigo-500 hover:bg-indigo-50 text-sm text-slate-600"><i class="fa-solid fa-hashtag text-indigo-400 w-4"></i> Numbers</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const formBuilderConfig = {
        updateUrl: @json(route('admin.forms.update', $form->id)),
        dashboardUrl: @json(route('admin.dashboard')),
    };

    function formBuilder(initialData) {
        return {
            formData: initialData,
            isSaving: false,
            message: '',
            isError: false,

            addField(type) {
                const labels = {'text': 'Short Answer', 'email': 'Email', 'number': 'Number'};
                this.formData.fields.push({ type: type, label: labels[type], is_required: false });
            },

            removeField(index) {
                this.formData.fields.splice(index, 1);
            },

            async updateForm() {
                this.isSaving = true;
                try {
                    // PUT Request bhej rahe hain update karne ke liye
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const response = await fetch(formBuilderConfig.updateUrl, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify(this.formData)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        this.showAlert('Form updated successfully!');
                        setTimeout(() => { window.location.href = formBuilderConfig.dashboardUrl; }, 1500);
                    } else {
                        this.showAlert(result.message, true);
                    }
                } catch (error) {
                    this.showAlert('Failed to update form.', true);
                } finally {
                    this.isSaving = false;
                }
            },

            showAlert(msg, error = false) {
                this.message = msg;
                this.isError = error;
                setTimeout(() => { this.message = ''; }, 3000);
            }
        }
    }
</script>
@endsection
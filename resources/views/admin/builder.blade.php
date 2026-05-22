@extends('layouts.admin')

@section('content')
<div x-data="formBuilder()" class="max-w-5xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-slate-800">Dynamic UI Builder</h2>
        <button @click="saveForm" class="bg-slate-900 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-slate-800 transition-all flex items-center gap-2 shadow-lg shadow-slate-300" :disabled="isSaving">
            <i class="fa-solid fa-cloud-arrow-up" x-show="!isSaving"></i>
            <i class="fa-solid fa-spinner fa-spin" x-show="isSaving"></i>
            <span x-text="isSaving ? 'Deploying...' : 'Save & Deploy Form'"></span>
        </button>
    </div>

    <div x-show="message" class="p-4 rounded-lg text-sm font-medium" :class="isError ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'" style="display: none;">
        <span x-text="message"></span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-700 border-b pb-2">1. Form Identity</h3>
                <div>
                    <label for="form-builder-title" class="block text-xs font-bold text-slate-500 mb-1">FORM TITLE *</label>
                    <input id="form-builder-title" type="text" x-model="formData.title" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" placeholder="e.g. Employee Registration">
                </div>
                <div>
                    <label for="form-builder-description" class="block text-xs font-bold text-slate-500 mb-1">DESCRIPTION</label>
                    <textarea id="form-builder-description" x-model="formData.description" rows="2" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Provide instructions for users..."></textarea>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm min-h-[300px]">
                <h3 class="font-bold text-slate-700 border-b pb-2 mb-4">2. Schema Builder</h3>
                
                <template x-for="(field, index) in formData.fields" :key="index">
                    <div class="flex items-start gap-4 p-4 border border-slate-100 bg-slate-50 rounded-lg mb-3 relative group">
                        <div class="mt-2 text-slate-300 cursor-move"><i class="fa-solid fa-grip-vertical"></i></div>
                        
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label :for="'field-label-' + index" class="block text-[10px] font-bold text-slate-400 mb-1">FIELD LABEL</label>
                                <input :id="'field-label-' + index" type="text" x-model="field.label" class="w-full text-sm border border-slate-200 rounded px-3 py-1.5 focus:border-indigo-500 outline-none">
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
                                <div class="mt-5 flex items-center">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" x-model="field.is_required" class="w-4 h-4 text-indigo-600 rounded">
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

                <div x-show="formData.fields.length === 0" class="text-center py-8 text-slate-400 text-sm">
                    No fields added yet. Add from the toolbox.
                </div>
            </div>
        </div>

        <div class="space-y-4 sticky top-24">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-700 border-b pb-2 mb-4 text-sm">Toolbox Nodes</h3>
                <div class="grid grid-cols-1 gap-2">
                    <button @click="addField('text')" class="flex items-center gap-3 w-full text-left px-4 py-2 border border-slate-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-all text-sm text-slate-600">
                        <i class="fa-solid fa-font text-indigo-400 w-4"></i> Short Text
                    </button>
                    <button @click="addField('email')" class="flex items-center gap-3 w-full text-left px-4 py-2 border border-slate-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-all text-sm text-slate-600">
                        <i class="fa-solid fa-envelope text-indigo-400 w-4"></i> Email Address
                    </button>
                    <button @click="addField('number')" class="flex items-center gap-3 w-full text-left px-4 py-2 border border-slate-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-all text-sm text-slate-600">
                        <i class="fa-solid fa-hashtag text-indigo-400 w-4"></i> Numbers
                    </button>
                    <button @click="addField('file')" class="flex items-center gap-3 w-full text-left px-4 py-2 border border-slate-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-all text-sm text-slate-600">
                        <i class="fa-solid fa-cloud-arrow-up text-indigo-400 w-4"></i> File Upload
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const formBuilderConfig = {
        storeUrl: @json(route('admin.forms.store')),
        dashboardUrl: @json(route('admin.dashboard')),
    };

    function formBuilder() {
        return {
            formData: {
                title: '',
                description: '',
                fields: []
            },
            isSaving: false,
            message: '',
            isError: false,

            addField(type) {
                const labels = {
                    'text': 'Short Answer',
                    'email': 'Email Address',
                    'number': 'Number Value',
                    'file': 'Document Upload'
                };
                this.formData.fields.push({
                    type: type,
                    label: labels[type],
                    is_required: false
                });
            },

            removeField(index) {
                this.formData.fields.splice(index, 1);
            },

            async saveForm() {
                if (!this.formData.title) {
                    this.showAlert('Form title is required!', true);
                    return;
                }
                if (this.formData.fields.length === 0) {
                    this.showAlert('Add at least one field to the schema.', true);
                    return;
                }

                this.isSaving = true;
                
                try {
                    // Direct API Post Request
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const response = await fetch(formBuilderConfig.storeUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify(this.formData)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        this.showAlert('Form deployed successfully!');
                        setTimeout(() => { window.location.href = formBuilderConfig.dashboardUrl; }, 1500);
                    } else {
                        const errMsg = result.errors
                            ? Object.values(result.errors).flat().join(' ')
                            : (result.message || 'Validation error occurred.');
                        this.showAlert(errMsg, true);
                    }
                } catch (error) {
                    this.showAlert('Server connection failed.', true);
                } finally {
                    this.isSaving = false;
                }
            },

            showAlert(msg, error = false) {
                this.message = msg;
                this.isError = error;
                setTimeout(() => { this.message = ''; }, 4000);
            }
        }
    }
</script>
@endsection
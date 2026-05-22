<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->title ?? 'FormCraft Form' }}</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#f8fafc] min-h-screen p-4 md:p-8">

    <div x-data="formRenderer({{ $form->toJson() }})" class="max-w-2xl mx-auto mt-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-10" x-cloak>
        
        <div class="mb-8 border-b border-slate-100 pb-6">
            <h1 class="text-3xl font-bold text-slate-900"><span x-text="form.title">{{ $form->title }}</span></h1>
            <p class="text-slate-500 mt-2 text-sm"><span x-text="form.description">{{ $form->description }}</span></p>
        </div>
        
        <form @submit.prevent="submitForm($event)" class="space-y-6">
            
            <template x-for="field in form.fields" :key="field.id">
                <div>
                    <label :for="'public-field-' + field.id" class="block text-sm font-semibold text-slate-700 mb-2">
                        <span x-text="field.label"></span>
                        <span x-show="field.is_required" class="text-red-500">*</span>
                    </label>

                    <template x-if="field.type === 'textarea'">
                        <textarea :id="'public-field-' + field.id" :name="field.name" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all text-sm" :required="field.is_required"></textarea>
                    </template>
                    <template x-if="field.type === 'file'">
                        <input :id="'public-field-' + field.id" type="file" :name="field.name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-indigo-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700" :required="field.is_required">
                    </template>
                    <template x-if="field.type !== 'textarea' && field.type !== 'file'">
                        <input :id="'public-field-' + field.id"
                               :type="field.type === 'number' ? 'number' : (field.type === 'email' ? 'email' : 'text')"
                               :name="field.name"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white transition-all text-sm"
                               :required="field.is_required">
                    </template>
                </div>
            </template>

            <button type="submit" 
                    class="mt-8 w-full bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-medium py-3 rounded-xl transition-colors shadow-sm disabled:opacity-70 flex justify-center items-center" 
                    :disabled="submitting">
                <span x-show="!submitting">Submit Response</span>
                <span x-show="submitting" class="flex items-center" style="display: none;">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Processing...
                </span>
            </button>
        </form>

        <div x-show="successMessage" class="mt-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center text-emerald-700 text-sm" style="display: none;">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span x-text="successMessage"></span>
        </div>

        <div x-show="errorMessage" class="mt-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center text-red-700 text-sm" style="display: none;">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span x-text="errorMessage"></span>
        </div>
    </div>

    <script>
        function formRenderer(initialForm) {
            return {
                form: initialForm,    
                submitting: false,
                successMessage: '',
                errorMessage: '',

                submitForm(event) {
                    this.submitting = true;
                    this.successMessage = '';
                    this.errorMessage = '';

                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    // JSON ki jagah FormData use kar rahe hain taake Files upload ho sakein!
                    const formData = new FormData(event.target);

                    fetch(`/forms/${this.form.slug}`, {
                        method: 'POST',
                        headers: { 
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                            // Yahan se Content-Type hata diya hai kyunke browser khud Multipart boundary set karega
                        },
                        body: formData
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok) {
                            const firstError = data.errors ? Object.values(data.errors).flat()[0] : null;
                            throw new Error(firstError || data.message || 'Validation failed. Please check your inputs.');
                        }
                        return data;
                    })
                    .then(data => {
                        this.successMessage = 'Your response has been saved successfully!';
                        event.target.reset(); // Success ke baad form khali kar dega
                    })
                    .catch(err => {
                        this.errorMessage = err.message;
                    })
                    .finally(() => {
                        this.submitting = false;
                    });
                }
            }
        }
    </script>
</body>
</html>
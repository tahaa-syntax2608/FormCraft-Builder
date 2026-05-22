<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicFormController extends Controller
{
    // Fetch form details dynamically via public unique URL slug
    public function showForm(string $slug)
    {
        $form = Form::where('slug', $slug)->where('is_active', true)->with('fields')->first();

        if (!$form) {
            return response()->json(['message' => 'Target form schema missing or offline.'], 404);
        }

        return response()->json($form, 200);
    }

    // Process user submitted data against the schema
    public function submitForm(Request $request, string $slug)
    {
        $form = Form::where('slug', $slug)->where('is_active', true)->with('fields')->first();

        if (!$form) {
            return response()->json(['message' => 'Form routing invalid.'], 404);
        }

        // --- DYNAMIC VALIDATION COMPILER ---
        // Fields array se custom validation rules execute time pe ready karna
        $dynamicRules = [];
        foreach ($form->fields as $field) {
            $rules = [];
            if ($field->is_required) {
                $rules[] = 'required';
            } else {
                $rules[] = 'nullable';
            }

            if ($field->type === 'email') $rules[] = 'email';
            if ($field->type === 'number') $rules[] = 'numeric';
            if ($field->type === 'file') $rules[] = 'file|max:2048'; // 2MB Max limit

            $dynamicRules[$field->name] = implode('|', $rules);
        }

        $validator = Validator::make($request->all(), $dynamicRules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // --- DATA PERSISTENCE LAYER ---
        // 1. Create parent entry record
        $submission = FormSubmission::create([
            'form_id' => $form->id,
            'ip_address' => $request->ip()
        ]);

        // 2. Loop through dynamic entries and save inside EAV structure
        foreach ($form->fields as $field) {
            $value = $request->input($field->name);

            // Handle file attachments if present
            if ($field->type === 'file' && $request->hasFile($field->name)) {
                $value = $request->file($field->name)->store('submissions/attachments', 'public');
            }

            $submission->values()->create([
                'form_field_id' => $field->id,
                'value' => is_array($value) ? json_encode($value) : $value
            ]);
        }

        return response()->json([
            'message' => 'Submission data successfully mapped and saved.',
            'submission_id' => $submission->id
        ], 201);
    }
}
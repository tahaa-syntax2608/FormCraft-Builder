<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::withCount('submissions')->latest()->get();

        return response()->json($forms, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fields' => 'required|array|min:1',
            'fields.*.type' => 'required|string|in:text,email,number,select,file,textarea',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.is_required' => 'boolean',
            'fields.*.options' => 'nullable|array',
        ]);

        $slug = Str::slug($request->title).'-'.Str::random(5);

        $form = Form::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => $slug,
        ]);

        foreach ($request->fields as $index => $fieldData) {
            $form->fields()->create($this->fieldAttributes($fieldData, $index));
        }

        return response()->json([
            'message' => 'Form created successfully.',
            'form' => $form->load('fields'),
        ], 201);
    }

    public function show(string $id)
    {
        $form = Form::with(['fields', 'submissions.values.field'])->find($id);

        if (! $form) {
            return response()->json(['message' => 'Form not found.'], 404);
        }

        return response()->json($form, 200);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fields' => 'required|array|min:1',
            'fields.*.type' => 'required|string|in:text,email,number,select,file,textarea',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.is_required' => 'boolean',
            'fields.*.options' => 'nullable|array',
        ]);

        $form = Form::findOrFail($id);

        $form->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        $form->fields()->delete();

        foreach ($request->fields as $index => $fieldData) {
            $form->fields()->create($this->fieldAttributes($fieldData, $index));
        }

        return response()->json([
            'message' => 'Form updated successfully.',
            'form' => $form->load('fields'),
        ], 200);
    }

    public function destroy(string $id)
    {
        $form = Form::find($id);

        if (! $form) {
            return response()->json(['message' => 'Form not found.'], 404);
        }

        $form->delete();

        return response()->json(['message' => 'Form deleted successfully.'], 200);
    }

    public function duplicate(string $id)
    {
        $form = Form::with('fields')->findOrFail($id);

        $copy = Form::create([
            'title' => $form->title.' (Copy)',
            'description' => $form->description,
            'slug' => Str::slug($form->title).'-'.Str::random(5),
            'is_active' => $form->is_active,
        ]);

        foreach ($form->fields as $field) {
            $copy->fields()->create($field->only([
                'type', 'label', 'name', 'is_required', 'validation_rules', 'options', 'order_index',
            ]));
        }

        return response()->json([
            'message' => 'Form duplicated successfully.',
            'form' => $copy->load('fields'),
        ], 201);
    }

    private function fieldAttributes(array $fieldData, int $index): array
    {
        $label = $fieldData['label'];
        $baseName = Str::snake($label);

        return [
            'type' => $fieldData['type'],
            'label' => $label,
            'name' => $fieldData['name'] ?? ($baseName.'_'.$index),
            'is_required' => $fieldData['is_required'] ?? false,
            'options' => $fieldData['options'] ?? null,
            'order_index' => $index,
        ];
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminWebController extends Controller
{
    public function dashboard()
    {
        $forms = Form::withCount('submissions')->latest()->get();
        $totalSubmissions = FormSubmission::count();
        $totalForms = $forms->count();

        return view('admin.dashboard', compact('forms', 'totalSubmissions', 'totalForms'));
    }

    public function createBuilder()
    {
        return view('admin.builder');
    }

    public function editForm(int $id)
    {
        $form = Form::with('fields')->findOrFail($id);

        return view('admin.edit', compact('form'));
    }

    public function deleteForm(int $id)
    {
        $form = Form::findOrFail($id);
        $form->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Form deleted successfully.');
    }

    public function duplicateForm(int $id)
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

        return redirect()->route('admin.forms.edit', $copy->id)
            ->with('success', 'Form duplicated. You can edit the copy now.');
    }

    public function viewSubmissions(Request $request, int $id)
    {
        $form = Form::with('fields')->findOrFail($id);

        $query = FormSubmission::where('form_id', $id)
            ->with('values.field')
            ->latest();

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%'.$search.'%')
                    ->orWhereHas('values', function ($vq) use ($search) {
                        $vq->where('value', 'like', '%'.$search.'%');
                    });
            });
        }

        $submissions = $query->paginate(10)->withQueryString();

        return view('admin.submissions', compact('form', 'submissions'));
    }

    public function exportSubmissions(int $id): StreamedResponse
    {
        $form = Form::with('fields')->findOrFail($id);
        $submissions = FormSubmission::where('form_id', $id)
            ->with('values')
            ->latest()
            ->get();

        $filename = Str::slug($form->title).'-submissions-'.now()->format('Y-m-d').'.csv';

        return Response::streamDownload(function () use ($form, $submissions) {
            $handle = fopen('php://output', 'w');
            $headers = ['Submission ID', 'Submitted At'];
            foreach ($form->fields as $field) {
                $headers[] = $field->label;
            }
            fputcsv($handle, $headers);

            foreach ($submissions as $submission) {
                $row = [
                    $submission->id,
                    $submission->created_at->toDateTimeString(),
                ];
                foreach ($form->fields as $field) {
                    $value = $submission->values->firstWhere('form_field_id', $field->id);
                    $row[] = $value?->value ?? '';
                }
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}

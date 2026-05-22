<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubmissionController extends Controller
{
    public function index(Request $request, string $id)
    {
        $form = Form::findOrFail($id);

        $query = FormSubmission::where('form_id', $form->id)
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

        return response()->json($query->paginate(15));
    }

    public function export(string $id): StreamedResponse
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
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}

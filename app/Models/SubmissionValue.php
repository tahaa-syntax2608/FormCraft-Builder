<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionValue extends Model
{
    use HasFactory;

    protected $fillable = ['form_submission_id', 'form_field_id', 'value'];

    // Relation: Value belongs to a specific submission attempt
    public function submission()
    {
        return $this->belongsTo(FormSubmission::class, 'form_submission_id');
    }

    // Relation: Value belongs to a specific field box
    public function field()
    {
        return $this->belongsTo(FormField::class, 'form_field_id');
    }
}
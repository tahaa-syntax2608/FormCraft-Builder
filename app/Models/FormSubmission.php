<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    use HasFactory;

    protected $fillable = ['form_id', 'ip_address'];

    // Relation: Submission belongs to a specific Form
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    // Relation: Ek submission record ke andar bohot saari field answers/values hoti hain
    public function values()
    {
        return $this->hasMany(SubmissionValue::class);
    }
}
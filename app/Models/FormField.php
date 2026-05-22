<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id', 'type', 'label', 'name', 'is_required', 
        'validation_rules', 'options', 'order_index'
    ];

    // Array casting taake JSON data directly backend pe associative array ban jaye
    protected $casts = [
        'validation_rules' => 'array',
        'options' => 'array',
        'is_required' => 'boolean'
    ];

    // Relation: Field belongs to a Form
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
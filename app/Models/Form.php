<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'slug', 'is_active'];

    // Relation: Ek Form mein bohot saari Fields hoti hain (Ordered by drag-drop index)
    public function fields()
    {
        return $this->hasMany(FormField::class)->orderBy('order_index', 'asc');
    }

    // Relation: Ek Form ki bohot saari User Submissions ho sakti hain
    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
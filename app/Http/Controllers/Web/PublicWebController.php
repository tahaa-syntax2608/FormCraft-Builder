<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\PublicFormController as ApiPublicFormController;
use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;

class PublicWebController extends Controller
{
    public function renderPublicForm(string $slug)
    {
        $form = Form::where('slug', $slug)->where('is_active', true)->with('fields')->firstOrFail();

        return view('public.render', compact('form'));
    }

    public function submitForm(Request $request, string $slug)
    {
        return app(ApiPublicFormController::class)->submitForm($request, $slug);
    }
}

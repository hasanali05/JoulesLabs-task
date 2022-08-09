<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormInputRequest;
use App\Http\Requests\FormSubmitRequest;
use Illuminate\Http\Request;
use App\Models\Form;
use App\Repository\FormInterface;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{
    protected $form_service;
    public function __construct(FormInterface $form_service)
    {
        $this->form_service = $form_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Form::with('fields', 'fields.rules')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormInputRequest $request)
    {
        $this->form_service->store($request);
        
        return response()->json(['success' => true, 'message' => 'Successfully created.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  Form $form
     * @return \Illuminate\Http\Response
     */
    public function show(Form $form)
    {
        // check form submittable or not.
        if(!$form->is_published) {
            return response()->json(['success' => false, 'message' => 'Form is not published yet.'], 403);
        }
        if(!$form->is_public && !auth()->user()) {
            return response()->json(['success' => false, 'message' => 'Must login to action.'], 401);
        }

        $form->load('fields:id,form_id,type,name,options');
        return $form->only('id', 'slug', 'title', 'description', 'fields');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Form $form
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Form $form)
    {
        $this->form_service->setModel($form)->update($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Form $form
     * @return \Illuminate\Http\Response
     */
    public function destroy(Form $form)
    {
        $this->form_service->setModel($form)->delete();
    }

    public function statusChange(Form $form, Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|in:is_published,is_public',
            'value' => 'required|boolean',
        ]);
        
        $form[$validated['key']] = $validated['value'];

        if ($form->isDirty()) {
            $form->save();
        }

        return response()->json(['message' => 'successfully updated.'], 200);
    }
    
    public function submit(Form $form, FormSubmitRequest $request)
    {
        // check form submittable or not.
        if(!$form->is_published) {
            return response()->json(['success' => false, 'message' => 'Form is not published yet.'], 403);
        }
        if(!$form->is_public && !auth()->user()) {
            return response()->json(['success' => false, 'message' => 'Must login to action.'], 401);
        }

        // validate request
        // store in csv.

        $csv_fields = request()->ip().','.auth()->id() ?? '';
        
        foreach ($request['fields'] as $field) {
            $csv_fields .= ','.$field['submit_value'];
        }

        $file_name = $form->file;
        
        Storage::disk('public')->append($file_name, $csv_fields);
        
        return response()->json(['message' => 'Form submitted successfully.'], 200);
    }
}

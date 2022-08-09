<?php

namespace App\Repository;
use App\Models\Form;
use App\Models\Rule;
use App\Models\Field;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FormService implements FormInterface{
    protected Form $model;
    public function store($data = []){
        $form = new Form();
        $form->title = $data['title'];
        $form->description = $data['description'];
        $form->is_published = $data['is_published'];
        $form->is_public = $data['is_public'];
        $form->save();
        $this->setModel($form)
            ->attachFields($data['fields'])
            ->makeCsv();
        return true;
    }

    public function update(Form $form, $data = []){
        $this->setModel($form);
        $form->name = $data['name'];
        $form->description = $data['description'];
        $form->is_published = $data['is_published'];
        $form->is_public = $data['is_public'];
        $form->save();

        $form->attachFields($data['fields']);
        return true;
    }

    public function delete(){
        // delete file.
        
        $this->model->load('fields');
        foreach($this->model->fields as $field) {
            $field->rules()->delete();
        }
        $this->model->fields()->delete();

        if(Storage::disk('public')->exists($this->model->file)) {
            Storage::disk('public')->delete($this->model->file);
        }

        $this->model->delete();
        return true;
    }

    public function setModel(Form $form)
    {
        $this->model = $form;
        return $this;
    }

    public function attachFields($fields = [])
    {
        foreach ($fields as $field_data) {
            $field = new Field($field_data);
            $form_field = $this->model->fields()->save($field);
            foreach ($field_data['rules'] as $rule_data) {
                $form_field->rules()->save(new Rule($rule_data));
            }
        }
        return $this;
    }

    public function makeCsv(){
        $this->model->load('fields');
        $csv_fields = 'ip,user_id';
        
        foreach ($this->model->fields as $field) {
            $csv_fields .= ','.$field->name;
        }
        $file_name = '/forms/'.$this->model->slug . '.csv';
        
        $this->model->file = $file_name;
        $this->model->save();
        
        Storage::disk('public')->put($file_name, $csv_fields);
    }
}
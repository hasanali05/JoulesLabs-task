<?php

namespace App\Repository;

use App\Models\Form;

interface FormInterface{
    public function store($data);
    public function update(Form $form,$data);
    public function delete();
    public function setModel(Form $form);
}
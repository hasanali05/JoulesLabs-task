<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['form_id', 'type', 'name', 'options'];
    
    public function rules()
    {
        return $this->hasMany(Rule::class);
    }
}
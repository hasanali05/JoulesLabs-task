<?php

namespace App\Models;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Form extends Model
{
    use Sluggable;
    
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' =>  ['title', 'id', 'is_published', 'is_public']
            ]
        ];
    }

    protected $fillable = ['title', 'description', 'file', 'is_published', 'is_public'];
    protected $appends = ['file_link'];

    public function fileLink(): Attribute
    {
        return new Attribute(
            get: fn () => request()->root().Storage::url('/'.$this->file),
        );
    }

    protected static function boot() {
        parent::boot();
    
    //     static::
    //         deleting(function ($file) {
    //             // delete file
    //             if(!Storage::exists($file->file)) {
    //                 // throw new FileNotFoundException();
    //                 return;
    //             }
    //             Storage::delete($file->file);
    // handle it from repository
    //         });
    }
    
    public function fields()
    {
        return $this->hasMany(Field::class);
    }
}
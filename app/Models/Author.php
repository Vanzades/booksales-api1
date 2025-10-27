<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Author extends Model
{
    protected $fillable = ['name', 'photo', 'bio'];
    protected $appends = ['photo_url'];

    public function books() {
        return $this->hasMany(Book::class);
    }

    protected function photoUrl(): Attribute {
        return Attribute::get(fn () => $this->photo ? Storage::url($this->photo) : null);
    }
}

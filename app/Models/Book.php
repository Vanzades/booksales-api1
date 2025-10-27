<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    protected $fillable = [
        'title','description','price','stock','cover_photo','genre_id','author_id'
    ];
    protected $appends = ['cover_url'];

    public function genre() { return $this->belongsTo(Genre::class); }
    public function author() { return $this->belongsTo(Author::class); }

    protected function coverUrl(): Attribute {
        return Attribute::get(fn () => $this->cover_photo ? Storage::url($this->cover_photo) : null);
    }
}

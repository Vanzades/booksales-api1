<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $fillable = ['author_id','title','isbn','stock','price','published_year'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'year',
    ];
    public $timestamps = false;

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class)->select(['title', 'content', 'publish_year']);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}

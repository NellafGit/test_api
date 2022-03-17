<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'publish_year',
    ];
    public $timestamps = false;

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class)->select(['name', 'surname', 'year']);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}

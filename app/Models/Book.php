<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Book
 * @package App\Models
 * @property string title
 * @property string summary
 * @property string publisher

 */
class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'summary', 'publisher'];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_authors', 'book_id', 'author_id');
    }
}

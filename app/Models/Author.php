<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Book
 * @package App\Models
 * @property string author_name
 */
class Author extends Model
{
    use HasFactory;
    protected $fillable = ['author_name'];
    protected $hidden = ['pivot'];
    public $timestamps = false;
}

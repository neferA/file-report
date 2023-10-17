<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewedBlog extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_blog_id',
        // Otros campos necesarios para la boleta renovada
        // ...
    ];

    public function parentBlog()
    {
        return $this->belongsTo(Blog::class, 'parent_blog_id');
    }
}


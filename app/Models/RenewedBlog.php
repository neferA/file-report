<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewedBlog extends Model
{
    use HasFactory;
    protected $fillable = [

        'parent_blog_id',
        'renewed_blog_id',
        'original_blog_id',
    ];
    
    public function parentBlog()
    {
        return $this->belongsTo(Blog::class, 'parent_blog_id');
    }
    public function originalBlog()
    {
        return $this->belongsTo(Blog::class, 'original_blog_id');
    }
    
    
}


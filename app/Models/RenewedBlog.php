<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenewedBlog extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_blog_id',
        'renewed_blog_id'
    ];
    protected static function boot()
    {
        parent::boot();

        // Evento creating para copiar renewed_blog_id en la tabla Blogs
        static::creating(function ($renewedBlog) {
            // Obtén el modelo Blog correspondiente y establece renewed_blog_id
            $blog = Blog::find($renewedBlog->renewed_blog_id);
            if ($blog) {
                $blog->renewed_blog_id = $renewedBlog->renewed_blog_id;
                $blog->save();
            }
        });
    }
    public function parentBlog()
    {
        return $this->belongsTo(Blog::class, 'parent_blog_id');
    }
}


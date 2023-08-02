<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waranty extends Model
{
    use HasFactory;
    protected $table = 'waranty_histories';
    
    protected $fillable = ['blogs_id', 'titulo', 'contenido'];
    
    // Define la relación con el modelo Blog
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}

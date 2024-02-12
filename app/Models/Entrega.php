<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    use HasFactory;

    protected $fillable = ['blogs_id', 'fecha_entrega', 'pdf_path', 'estado'];

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blogs_id');
    }
}

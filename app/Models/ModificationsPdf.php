<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModificationsPdf extends Model
{
    use HasFactory;
    protected $table = 'modifications_pdf';
    protected $fillable = ['blogs_id', 'pdf_path'];

    // Definir la relación con el modelo Blog
    public function blog()
    {
        return $this->belongsTo(Blog::class,'blogs_id');
    }
}
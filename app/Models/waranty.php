<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waranty extends Model
{
    use HasFactory;
    protected $table = 'waranty_histories';
    
    protected $fillable = [
        'blogs_id', 
        'titulo', 
        'contenido',
        'caracteristicas',
        'observaciones',
        'monto',
        'boleta_pdf',
        'nota_pdf',
        'fecha_inicio',
        'fecha_final',
    ];
    
    protected $dates = ['fecha_inicio', 'fecha_final'];

    // Define la relación con el modelo Blog
    public function blog()
    {
        return $this->belongsTo(Blog::class,'blogs_id');
    }
}

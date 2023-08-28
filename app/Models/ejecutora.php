<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ejecutora extends Model
{
    use HasFactory;
    protected $table = 'unidad_ejecutora';
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
    public function blog()
    {
        return $this->hasOne(Blog::class, 'unidad_ejecutora_id');
    }
}

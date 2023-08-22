<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoGarantia extends Model
{
    use HasFactory;
    protected $table = 'tipo_garantia';
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
    public function blog()
    {
        return $this->hasOne(Blog::class, 'tipo_garantia_id');
    }
}

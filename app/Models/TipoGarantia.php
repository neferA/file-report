<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoGarantia extends Model
{
    use HasFactory;

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'tipo_garantia_id');
    }
}

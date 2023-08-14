<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financiadora extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'boleta_financiadora', 'financiadora_id', 'blogs_id');
    }
    
}

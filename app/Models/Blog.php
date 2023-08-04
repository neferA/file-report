<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'num_boleta',
        'proveedor',
        'motivo',
        'ejecutora',
        'usuario',
    ];
    
    public function waranty()
    {
        return $this->hasOne(waranty::class, 'blogs_id');
    }
}

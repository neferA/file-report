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
    
    public function history()
    {
        return $this->hasMany(waranty::class, 'blogs_id');
    }
}

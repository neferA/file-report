<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class afianzadora extends Model
{
    use HasFactory;
    protected $table = 'afianzadora';
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
    public function blog()
    {
        return $this->hasOne(Blog::class, 'afianzadora_id');
    }
}


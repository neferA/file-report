<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modification extends Model
{
    protected $table = 'modifications';
    use HasFactory;
    protected $fillable = [
        'blogs_id',
        'modification_details',
        'modification_time',
        'usuario',
    ];
    public function blog()
    {
        return $this->belongsTo(Blog::class,'blogs_id');
    }

}

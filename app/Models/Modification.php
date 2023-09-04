<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modification extends Model
{
    use HasFactory;
    protected $fillable = [
        'blogs_id',
        'modification_details'
    ];
    public function blog()
    {
        return $this->belongsTo(Blog::class,'blogs_id');
    }

}

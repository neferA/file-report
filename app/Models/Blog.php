<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipo_garantia_id',
        'num_boleta',
        'proveedor',
        'motivo',
        'ejecutora',
        'usuario',
        'estado',

    ];
    
    const ESTADO_LIBERADO = 'liberado';
    const ESTADO_EJECUTADO = 'ejecutado';
    const ESTADO_RENOVADO = 'renovado';
    
    public function getEstadoColorAttribute()
    {
        switch ($this->estado) {
            case self::ESTADO_LIBERADO:
                return 'badge-success'; // Cambia esto al color que desees
            case self::ESTADO_EJECUTADO:
                return 'badge-warning'; // Cambia esto al color que desees
            case self::ESTADO_RENOVADO:
                return 'badge-info'; // Cambia esto al color que desees
            default:
                return 'badge-secondary'; // Color por defecto si el estado no coincide
        }
    }
    
    public function waranty()
    {
        return $this->hasOne(waranty::class, 'blogs_id');
    }
    public function financiadoras()
    {
        return $this->belongsToMany(Financiadora::class, 'boleta_financiadora', 'blogs_id', 'financiadora_id');
    }
    
    public function assetWaranty()
    {
        return $this->belongsTo(assets_waranty::class);
    }
}

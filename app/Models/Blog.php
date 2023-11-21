<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        //'id_boleta_real',
        'renewed_blog_id',
        'tipo_garantia_id',
        'unidad_ejecutora_id',
        'afianzadora_id',
        'num_boleta',
        'empresa',
        'motivo',
        'ejecutora',
        'usuario',
        'estado',
        'alarm_color',

    ];
    
    const ESTADO_LIBERADO = 'liberado';
    const ESTADO_EJECUTADO = 'ejecutado';
    const ESTADO_RENOVADO = 'renovado';
    const ESTADO_VIGENTE = 'vigente'; 
    const ESTADO_VENCIDO = 'vencido';
    
    public function getEstadoColorAttribute()
    {
        switch ($this->estado) {
            case self::ESTADO_VIGENTE:
                return 'badge-primary'; // Color para el estado "vigente"
            case self::ESTADO_LIBERADO:
                return 'badge-success'; // Cambia esto al color que desees
            case self::ESTADO_EJECUTADO:
                return 'badge-warning'; // Cambia esto al color que desees
            case self::ESTADO_RENOVADO:
                return 'badge-info'; // Cambia esto al color que desees
            case self::ESTADO_VENCIDO:
                return 'badge-danger'; // Color para el estado "vencido"
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
    public function tipoGarantia()
    {
        return $this->belongsTo(TipoGarantia::class, 'tipo_garantia_id');
    }
    public function unidadEjecutora()
    {
        return $this->belongsTo(ejecutora::class, 'unidad_ejecutora_id');
    }
    public function afianzado()
    {
        return $this->belongsTo(afianzadora::class, 'afianzadora_id');
    }
    public function renewedBlogs()
    {
        return $this->hasMany(RenewedBlog::class, 'parent_blog_id');
    }
 
}
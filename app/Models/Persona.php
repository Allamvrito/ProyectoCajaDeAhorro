<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Persona extends Model
{
    protected $table = 'personas';
    protected $primaryKey = 'id_persona';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'numero_identidad',
        'telefono',
        'direccion',
        'ruta_foto',
        'tipo',
        'estado',
        'creado_por',
        'fecha_creacion',
        'actualizado_por',
        'fecha_actualizacion',
    ];

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function actualizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actualizado_por');
    }

    protected static function booted()
    {
        static::creating(function ($persona) {
            $persona->creado_por = Auth::id();
        });

        static::updating(function ($persona) {
            $persona->actualizado_por = Auth::id();
            $persona->fecha_actualizacion = now();
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'fecha_vencimiento',
        'user_id'
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
    ];

    // Relación con Usuario
    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}

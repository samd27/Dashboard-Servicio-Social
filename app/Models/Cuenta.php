<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cuenta extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     * (Todos los campos del RF04 de la cuenta)
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre_cuenta',
        'rfc',
        'telefono',
        'email_2_opcional',
    ];

    /**
     * Define la relación: Una Cuenta tiene muchos Usuarios (RF05-C2)
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
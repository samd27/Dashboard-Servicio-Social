<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cuenta extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre_cuenta',
        'rfc',
        'telefono',
        'email_2_opcional',
    ];
    
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
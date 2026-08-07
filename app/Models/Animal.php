<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Animal extends Model
{
    // El pluralizador de Laravel es inglés: de Animal deduce "animals".
    // Nuestra tabla se llama animales, así que hay que decirlo.
    protected $table = 'animales';

    // Los campos que se pueden llenar de una vez con los datos del formulario.
    // user_id NO está acá a propósito: el dueño no lo elige el formulario.
    protected $fillable = ['nombre', 'edad', 'especie', 'adoptado'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

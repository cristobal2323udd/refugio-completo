<?php

namespace App\Policies;

use App\Models\Animal;
use App\Models\User;

class AnimalPolicy
{
    /**
     * La regla del refugio: cada quien manda sobre sus animales.
     *
     * No hay que registrar esta clase en ninguna parte: Laravel la encuentra
     * por el nombre (Animal -> AnimalPolicy).
     */
    public function update(User $user, Animal $animal): bool
    {
        return $user->id === $animal->user_id;
    }

    public function delete(User $user, Animal $animal): bool
    {
        return $user->id === $animal->user_id;
    }
}

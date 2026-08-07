<?php

use App\Models\Animal;
use App\Models\User;

function duenoDelRefugio(): User
{
    return User::create([
        'name' => 'Ana', 'email' => 'ana@refugio.cl', 'password' => 'refugio123',
    ]);
}

test('la raíz lleva a la lista de animales', function () {
    // Sin sesión: / redirige antes de que corra el middleware auth.
    $this->get('/')->assertRedirect(route('animales.index'));
});

test('la lista muestra los animales del refugio', function () {
    $ana = duenoDelRefugio();
    $ana->animales()->create(['nombre' => 'Firulais', 'edad' => 3, 'especie' => 'perro']);

    $this->actingAs($ana)->get(route('animales.index'))
        ->assertOk()
        ->assertSee('Firulais')
        ->assertSee('3 años');
});

test('guardar un animal con datos buenos lo deja en la tabla', function () {
    $ana = duenoDelRefugio();

    $this->actingAs($ana)->post(route('animales.store'), [
        'nombre'  => 'Michi',
        'edad'    => 2,
        'especie' => 'gato',
    ])->assertRedirect(route('animales.index'));

    expect(Animal::where('nombre', 'Michi')->exists())->toBeTrue();
});

test('un animal sin nombre no se guarda', function () {
    $ana = duenoDelRefugio();

    $this->actingAs($ana)->post(route('animales.store'), [
        'nombre'  => '',
        'edad'    => 2,
        'especie' => 'gato',
    ])->assertSessionHasErrors('nombre');

    expect(Animal::count())->toBe(0);
});

test('adoptar marca la columna y deja el mensaje', function () {
    $ana = duenoDelRefugio();
    $animal = $ana->animales()->create(['nombre' => 'Lola', 'edad' => 4, 'especie' => 'pajaro']);

    $this->actingAs($ana)->patch(route('animales.adoptar', $animal))
        ->assertRedirect(route('animales.index'))
        ->assertSessionHas('ok', 'Lola encontró familia.');

    // Vuelve como 1 y no como true: SQLite no tiene tipo booleano, guarda la
    // columna como tinyint(1). En un @if de Blade da lo mismo, acá no.
    expect($animal->fresh()->adoptado)->toBe(1);
});

test('eliminar saca la ficha', function () {
    $ana = duenoDelRefugio();
    $animal = $ana->animales()->create(['nombre' => 'Pelusa', 'edad' => 5, 'especie' => 'conejo']);

    $this->actingAs($ana)->delete(route('animales.destroy', $animal))
        ->assertRedirect(route('animales.index'));

    expect(Animal::count())->toBe(0);
});

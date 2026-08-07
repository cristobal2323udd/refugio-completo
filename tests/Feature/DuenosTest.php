<?php

use App\Models\Animal;
use App\Models\User;

function refugioSembrado(): array
{
    $ana  = User::create(['name' => 'Ana',  'email' => 'ana@refugio.cl',  'password' => 'refugio123']);
    $beto = User::create(['name' => 'Beto', 'email' => 'beto@refugio.cl', 'password' => 'refugio123']);

    $firulais = $ana->animales()->create(['nombre' => 'Firulais', 'edad' => 3, 'especie' => 'perro']);
    $michi    = $ana->animales()->create(['nombre' => 'Michi', 'edad' => 2, 'especie' => 'gato']);
    $lola     = $beto->animales()->create(['nombre' => 'Lola', 'edad' => 4, 'especie' => 'pajaro']);

    return compact('ana', 'beto', 'firulais', 'michi', 'lola');
}

test('crear a través de la relación llena user_id aunque no esté en fillable', function () {
    ['ana' => $ana, 'firulais' => $firulais] = refugioSembrado();

    expect($firulais->user_id)->toBe($ana->id);
    expect((new Animal)->getFillable())->not->toContain('user_id');
});

test('la relación anda en los dos sentidos', function () {
    ['ana' => $ana, 'lola' => $lola] = refugioSembrado();

    expect($ana->animales)->toHaveCount(2);
    expect($lola->usuario->name)->toBe('Beto');
});

test('cada usuario ve solo sus animales', function () {
    ['ana' => $ana] = refugioSembrado();

    $this->actingAs($ana)->get('/animales')
        ->assertOk()
        ->assertSee('Firulais')
        ->assertSee('Michi')
        ->assertDontSee('Lola');
});

test('guardar deja el animal a nombre del usuario de la sesión', function () {
    ['ana' => $ana] = refugioSembrado();

    $this->actingAs($ana)->post('/animales', [
        'nombre' => 'Rocky', 'edad' => 5, 'especie' => 'perro',
    ])->assertRedirect('/animales');

    expect(Animal::where('nombre', 'Rocky')->first()->user_id)->toBe($ana->id);
});

test('el user_id que manda el formulario se descarta en silencio', function () {
    ['ana' => $ana, 'beto' => $beto] = refugioSembrado();

    $this->actingAs($ana)->post('/animales', [
        'nombre' => 'Colado', 'edad' => 1, 'especie' => 'gato',
        'user_id' => $beto->id,
    ])->assertRedirect('/animales');

    expect(Animal::where('nombre', 'Colado')->first()->user_id)->toBe($ana->id);
});

test('abrir el formulario de edición de un animal ajeno da 403', function () {
    ['ana' => $ana, 'lola' => $lola] = refugioSembrado();

    $this->actingAs($ana)->get("/animales/{$lola->id}/editar")->assertForbidden();
});

test('actualizar un animal ajeno da 403 y no lo cambia', function () {
    ['ana' => $ana, 'lola' => $lola] = refugioSembrado();

    $this->actingAs($ana)->put("/animales/{$lola->id}", [
        'nombre' => 'Robada', 'edad' => 4, 'especie' => 'pajaro',
    ])->assertForbidden();

    expect($lola->fresh()->nombre)->toBe('Lola');
});

test('adoptar y borrar un animal ajeno dan 403', function () {
    ['ana' => $ana, 'lola' => $lola] = refugioSembrado();

    $this->actingAs($ana)->patch("/animales/{$lola->id}/adoptar")->assertForbidden();
    $this->actingAs($ana)->delete("/animales/{$lola->id}")->assertForbidden();

    expect($lola->fresh())->not->toBeNull();
    expect($lola->fresh()->adoptado)->toBeFalsy();
});

test('sobre los animales propios no pasa nada raro', function () {
    ['ana' => $ana, 'firulais' => $firulais] = refugioSembrado();

    $this->actingAs($ana)->get("/animales/{$firulais->id}/editar")->assertOk();

    $this->actingAs($ana)->put("/animales/{$firulais->id}", [
        'nombre' => 'Firulais II', 'edad' => 4, 'especie' => 'perro',
    ])->assertRedirect('/animales');

    expect($firulais->fresh()->nombre)->toBe('Firulais II');
});

test('el Form Request valida y deja los mensajes en español', function () {
    ['ana' => $ana] = refugioSembrado();

    $this->actingAs($ana)->post('/animales', ['nombre' => '', 'edad' => '', 'especie' => 'gato'])
        ->assertSessionHasErrors(['nombre' => 'El nombre es obligatorio.']);
});

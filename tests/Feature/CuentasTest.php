<?php

use App\Models\User;

test('sin sesión, la lista de animales redirige al login', function () {
    $this->get('/animales')->assertRedirect('/login');
});

test('el registro crea la cuenta, deja la sesión iniciada y hashea la contraseña', function () {
    $this->post('/registro', [
        'name' => 'Carla',
        'email' => 'carla@refugio.cl',
        'password' => 'refugio123',
        'password_confirmation' => 'refugio123',
    ])->assertRedirect('/animales');

    $carla = User::where('email', 'carla@refugio.cl')->first();

    expect($carla)->not->toBeNull();
    expect($carla->password)->not->toBe('refugio123');
    expect(Illuminate\Support\Facades\Hash::check('refugio123', $carla->password))->toBeTrue();
    expect(auth()->id())->toBe($carla->id);
});

test('el registro exige que las dos contraseñas coincidan', function () {
    $this->post('/registro', [
        'name' => 'Carla',
        'email' => 'carla@refugio.cl',
        'password' => 'refugio123',
        'password_confirmation' => 'otra-cosa',
    ])->assertSessionHasErrors('password');

    expect(User::count())->toBe(0);
});

test('el registro rechaza un correo repetido', function () {
    User::create(['name' => 'Ana', 'email' => 'ana@refugio.cl', 'password' => 'refugio123']);

    $this->post('/registro', [
        'name' => 'Otra Ana',
        'email' => 'ana@refugio.cl',
        'password' => 'refugio123',
        'password_confirmation' => 'refugio123',
    ])->assertSessionHasErrors('email');

    expect(User::count())->toBe(1);
});

test('el login acepta las credenciales buenas', function () {
    User::create(['name' => 'Ana', 'email' => 'ana@refugio.cl', 'password' => 'refugio123']);

    $this->post('/login', ['email' => 'ana@refugio.cl', 'password' => 'refugio123'])
        ->assertRedirect('/animales');

    expect(auth()->check())->toBeTrue();
});

test('el login rechaza las credenciales malas y no inicia sesión', function () {
    User::create(['name' => 'Ana', 'email' => 'ana@refugio.cl', 'password' => 'refugio123']);

    $this->post('/login', ['email' => 'ana@refugio.cl', 'password' => 'mala'])
        ->assertSessionHasErrors('email');

    expect(auth()->check())->toBeFalse();
});

test('el logout cierra la sesión y devuelve al login', function () {
    $ana = User::create(['name' => 'Ana', 'email' => 'ana@refugio.cl', 'password' => 'refugio123']);

    $this->actingAs($ana)->post('/logout')->assertRedirect('/login');

    expect(auth()->check())->toBeFalse();
});

test('con sesión iniciada, el formulario de login no se muestra', function () {
    $ana = User::create(['name' => 'Ana', 'email' => 'ana@refugio.cl', 'password' => 'refugio123']);

    $this->actingAs($ana)->get('/login')->assertRedirect();
});

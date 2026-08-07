# Refugio completo

CRUD de animales en Laravel, con cuentas, dueños y autorización. Es el proyecto
que armamos en las **clases 10 y 11** de IIP323W (Tecnologías y Aplicaciones Web
y Móviles, UDD), terminado, y el código de acá es el mismo que sale en las
diapositivas.

Son siete rutas: listar, mostrar el formulario, guardar, editar, actualizar,
adoptar y eliminar.

Este repositorio trae el proyecto tal como quedó al final, en un solo commit. Si
quieres verlo crecer paso a paso, clase por clase, esa historia está en
[cristobal2323udd/refugio](https://github.com/cristobal2323udd/refugio).

## Para hacerlo andar

Necesitas PHP 8.2 o más nuevo, Composer y Node.

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed

composer run dev
```

El `--seed` deja dos cuentas listas, **ana@refugio.cl** y **beto@refugio.cl**, las
dos con la contraseña `refugio123`. Ana tiene a Firulais y a Michi, Beto tiene a
Lola. Entra con una y después con la otra: cada una ve solo sus animales.

El sitio queda en http://localhost:8000. Ojo con la última línea: `composer run
dev` levanta el servidor de PHP y Vite al mismo tiempo. Si corres solo
`php artisan serve`, la página aparece sin ningún estilo.

El `--seed` deja los tres animales de las diapositivas (Firulais, Michi y Lola)
para que el refugio no parta vacío.

Si prefieres el atajo, el proyecto trae un script que hace todo lo de arriba
menos el seeder:

```bash
composer run setup
php artisan db:seed
composer run dev
```

## Los tests

El proyecto trae 25 tests: el CRUD, las cuentas y la regla del dueño.

```bash
npm run build
php artisan test
```

El `npm run build` va primero a propósito. Tres de esos tests piden la página
completa, y la página carga sus estilos con `@vite`: en un clon recién bajado no
existe `public/build`, así que Laravel corta con `ViteManifestNotFoundException`
y esos tres fallan. Para mirar el sitio en el navegador no hace falta, porque
`composer run dev` levanta Vite y sirve los estilos al vuelo.

## Los archivos del refugio

| Archivo | Qué hace |
| --- | --- |
| `database/migrations/2026_07_31_105744_create_animales_table.php` | Describe la tabla `animales`: una línea por columna. |
| `app/Models/Animal.php` | El modelo. Las dos líneas de adentro son `$table` (porque el pluralizador de Laravel es inglés y deduciría `animals`) y `$fillable`. |
| `app/Http/Controllers/AnimalController.php` | Un método por ruta: `index`, `create`, `store`, `edit`, `update`, `adoptar` y `destroy`. |
| `routes/web.php` | Las siete rutas, escritas una por una. |
| `resources/views/layouts/app.blade.php` | El marco que comparten todas las páginas, de la clase 9. Acá vive el mensaje verde de confirmación. |
| `resources/views/animales/index.blade.php` | La lista. |
| `resources/views/animales/crear.blade.php` | El formulario para agregar. |
| `resources/views/animales/editar.blade.php` | El formulario para editar. |
| `app/Http/Controllers/AuthController.php` | Registro, login y logout, escritos a mano. |
| `app/Policies/AnimalPolicy.php` | La regla del dueño: solo quien registró un animal puede editarlo o borrarlo. |
| `app/Http/Requests/GuardarAnimalRequest.php` | Las reglas y los mensajes del formulario de animal, en un solo lugar. |
| `resources/views/auth/registro.blade.php` | El formulario para crear cuenta. |
| `resources/views/auth/login.blade.php` | El formulario para entrar. |

La fecha en el nombre de la migración es la del día en que se grabó este
proyecto. Cuando la crees tú con `php artisan make:migration
create_animales_table`, va a llevar la fecha de ese momento, y está bien: ese
número solo sirve para ordenarlas.

## Las siete rutas

```
$ php artisan route:list --path=animales

 GET|HEAD animales .. animales.index › AnimalController@index
 POST animales .. animales.store › AnimalController@store
 GET|HEAD animales/crear .. animales.create › AnimalController@create
 PUT animales/{animal} .. animales.update › AnimalController@update
 DELETE animales/{animal} .. animales.destroy › AnimalController@destroy
 PATCH animales/{animal}/adoptar .. animales.adoptar › AnimalController@adoptar
 GET|HEAD animales/{animal}/editar .. animales.edit › AnimalController@edit

 Showing [7] routes
```

Existe un atajo, `Route::resource('animales', AnimalController::class)`, que
declara seis de ellas en una sola línea. Acá van escritas a mano a propósito,
para ver qué hay adentro. La séptima, `adoptar`, igual iría aparte: no es una de
las seis del CRUD.

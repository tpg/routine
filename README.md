# Routine

A really basic solution to class-based routing for Laravel. It's very simplistic and probably needs a lot of work.
I only wrote this for myself and isn't really intended for the wider world.

But if you want to, install it with Composer:

```shell
composer require thepublicgood/routine
```

Once installed, publish the config file with:

```shell
php ./artisan vendor:publish --provider="TPG\Routine\RoutineServiceProvider"
```

This will place a `routine.php` file in your config directory. Now create a new empty class and extend the `TPG\Routine\RouteRegistrar` class. Create new methods
that are prefixed with `map` and suffixed with `Routes`. Routine will call each method and map the defined routes.

Each map method must accept an instance of `Illuminate\Routing\Router`:

```php
class AuthRoutes extends RouteRegistrar
{
    protected function mapWebRoutes(Router $router): void
    {
        $router->middleware(['guest'])->group(function (Router $router) {
            
            $router->get('/login', [SessionController::class, 'create'])->name('login');
            $router->post('/login', [SessionController::class, 'store']);
        
        });
        
        $router->middleware(['auth'])->post('/logout', [SessionController:class, 'destroy'])->name('logout');
    }
}
```

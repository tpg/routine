# Routine

A really basic solution to class-based routing for Laravel. It's very simplistic and probably needs a lot of work.
I only wrote this for myself and isn't really intended for the wider world.

I don't intend to provide much support for this package and will likely only update it when I need to. I'm not even sure
I'll continue to use it. It's more of an experiment really.

But if you really want to use it, then by all means, install it with Composer:

```shell
composer require thepublicgood/routine
```

Once installed, publish the config file with:

```shell
php ./artisan vendor:publish --provider="TPG\Routine\RoutineServiceProvider"
```

This will place a `routine.php` file in your config directory. Now create a new empty class and extend the
`TPG\Routine\RouteRegistrar` class. Create new methods that are prefixed with `map` and suffixed with `Routes`. Routine
will call each method and map the defined routes.

Each map method must accept an instance of `Illuminate\Routing\Router`:

```php
use App\Http\Controllers\SessionController;
use Illuminuate\Routing\Router;
use TPG\Routine\RouteRegistrar;

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

By default, Routine will set default middleware depending on the name of the method. You can find these defaults in the
`routine.php` config file. For example, the method `mapWebRoutes` will automatically use the middleware set as the `web`
default. The method `mapApiRoutes` will automatically use the middleware set as the `api` default. This means you could
create a default called `ajax` for example, and the method `mapAjaxRoutes` will automatically set those middleware.

## Middleware Contracts
In addition, it can be useful to apply middleware for an entire route file. For example, if you need to specify that
a set of routes need authentication. You can do this by implementing the `TPG\Routing\Contracts\RequiresAuthentication`
interface:

```php
use Illuminuate\Routing\Router;
use TPG\Routine\Contracts\RequiresAuthentication;
use TPG\Routine\RouteRegistrar;

class DashboardRoutes extends RouteRegistrar implements RequiresAuthentication
{
    // ...
}
```

Routine includes a `RequiresAuthentication`, `RequiresSanctumAuthentication` and a `SignedRoute` contract by default.
If you add your own, ensure that you add them to the `routine.php` config file.




<?php

declare(strict_types=1);

namespace TPG\Routine;

use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class RoutineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/routine.php' => config_path('routine.php'),
        ]);

        $this->bootRoutes(function (Router $router) {
            foreach ($this->registrars() as $registrar) {
                if (! class_exists($registrar) && ! is_subclass_of($registrar, RouteRegistrar::class)) {
                    throw new \RuntimeException("Cannot map registrar {$registrar}. It is not a valid router class");
                }

                (new $registrar())->map($router);
            }
        });
    }

    protected function bootRoutes(callable $mapper): void
    {
        if (! ($this->app instanceof CachesRoutes && $this->app->routesAreCached())) {
            $this->app->call($mapper);
        }
    }

    protected function registrars(): array
    {
        return config('routine.registrars');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/routine.php', 'routine');
    }
}

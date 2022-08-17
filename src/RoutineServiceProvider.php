<?php

declare(strict_types=1);

namespace TPG\Routine;

use Illuminate\Support\ServiceProvider;

class RoutineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/routine.php' => config_path('routine.php'),
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/routine.php', 'routine');
    }
}

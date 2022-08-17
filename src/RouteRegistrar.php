<?php

declare(strict_types=1);

namespace TPG\Routine;

use Illuminate\Routing\Router;
use Illuminate\Support\Str;

abstract class RouteRegistrar
{
    public function map(Router $router): void
    {
        $reflection = new \ReflectionClass($this);

        $mapMethods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PROTECTED),
            fn (\ReflectionMethod $method) => Str::startsWith($method->name, 'map') && Str::endsWith($method->name, 'Routes')
        );

        foreach ($mapMethods as $method) {
            $router->middleware(...$this->middlewareFromContracts($reflection))
                ->group(fn () => $this->{$method->name}($router));
        }
    }

    protected function middlewareFromContracts(\ReflectionClass $reflection): array
    {
        $contracts = config('routine.middleware.contracts', []);

        return collect($contracts)
            ->map(fn($middleware, $contract) => $reflection->implementsInterface($contract) ? $middleware : [])
            ->toArray();
    }
}

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

            $router->middleware([
                ...$this->defaultMiddleware(strtolower(Str::between($method->name, 'map', 'Routes'))),
                ...$this->middlewareFromContracts($reflection)
            ])->group(fn () => $this->{$method->name}($router));
        }
    }

    protected function defaultMiddleware(string $key): array
    {
        return config('routine.middleware.defaults.'.$key, []) ?? [];
    }

    protected function middlewareFromContracts(\ReflectionClass $reflection): array
    {
        $contracts = config('routine.middleware.contracts', []);

        return collect($contracts)
            ->map(fn($middleware, $contract) => $reflection->implementsInterface($contract) ? $middleware : null)
            ->values()
            ->flatten()
            ->whereNotNull()
            ->toArray();
    }
}

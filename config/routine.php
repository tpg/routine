<?php

declare(strict_types=1);

use TPG\Routine\Contracts\RequiresAuthentication;
use TPG\Routine\Contracts\RequiresSanctumAuthentication;
use TPG\Routine\Contracts\SignedRoute;

return [
    'registrars' => [
        //..
    ],
    'middleware' => [
        'defaults' => [
            'web' => ['web'],
            'api' => ['api'],
        ],
        'contracts' => [
            RequiresAuthentication::class => [
                'auth',
            ],
            RequiresSanctumAuthentication::class => [
                'auth:sanctum',
            ],
            SignedRoute::class => [
                'signed',
            ],
        ],
    ],
];

<?php

return [
    'frontend' => [
        'itanix/clockwork-init' => [
            'target' => \Itanix\Clockwork\Middleware\ClockworkInitialization::class,
            'before' => [
                'typo3/cms-frontend/timetracker',
            ]
        ],
        'itanix/clockwork-rest-api-endpoint' => [
            'target' => \Itanix\Clockwork\Middleware\ClockworkRestApiEndpoint::class,
            'before' => [
                'typo3/cms-frontend/site',
            ]
        ],
        'itanix/clockwork-request-processed' => [
            'target' => \Itanix\Clockwork\Middleware\ClockworkRequestProcessed::class,
            'before' => [
                'typo3/cms-frontend/content-length-headers',
            ]
        ],
    ]
];

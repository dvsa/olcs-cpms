<?php

use DVSA\CPMS\Queues\QueueAdapters\AmazonSqs\AmazonSqsQueues;
use DVSA\CPMS\Notifications\Messages\Maps\MapNotificationTypes;

return array(
    'service_manager' => [
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
        ],
        'factories' => [
        ],
    ],

    'controller_plugins' => [
        'invokables' => [
        ]
    ],
    'view_helpers' => [
        'invokables' => [
        ]
    ],

    'caches' => [
        'filesystem' => [
            'adapter' => [
                'name' => 'filesystem',
                'lifetime' => 300,
                'options' => [
                    'cache_dir' => 'data/cache/cpms',
                    'ttl' => 300,
                    'namespace' => 'cpms',
                    'dir_permission' => 0775,
                    'file_permission' => 0666,
                ],
                'plugins' => [
                    'exception_handler' => [
                        'throw_exceptions' => false
                    ],
                ],
            ],
            'plugins' => [
                'serializer'
            ],
        ],

        'array' => [
            'adapter' => [
                'name' => 'memory',
                'lifetime' => 0,
                'options' => [
                    'ttl' => 0,
                    'namespace' => 'cpms'
                ],
            ],
        ],
        'apc' => [
            'adapter' => [
                'name' => 'apc',
                'options' => [
                    'ttl' => 3600,
                    'namespace' => 'cpms'
                ],
            ],
            'plugins' => [
                'exception_handler' => [
                    'throw_exceptions' => false
                ],
            ],
        ]
    ],

    'logger' => [
        'location' => '/var/log/dvsa',
        'filename' => date('Y-m-d') . '-cpms-api-client.log'
    ],

    'cpms_api' => [
        'logger_alias' => '',
        'enable_cache' => true,
        'service_class' => '',
        'home_domain' => '', //Used when running in console mode
        'cache_storage' => (extension_loaded('apc') and php_sapi_name() != 'cli') ? 'apc' : 'array',
        'identity_provider' => '',
        'rest_client' => [
            'alias' => 'cpms\client\rest',
            'options' => [
                'domain' => '',
                'version' => 1,
                'client_id' => '',
                'client_secret' => '',
                'user_id' => '',
                'customer_reference' => '',
                'grant_type' => 'client_credentials',
                'timeout' => 15,
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'end_points' => [
                    'access_token' => '/api/token',
                    'refund' => '/api/payment/refund',
                    'transaction' => '/api/transactions'
                ]
            ],
            'adapter' => 'Zend\Http\Client\Adapter\Curl',
        ]
    ],
);

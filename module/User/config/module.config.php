<?php
namespace User;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'user' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/user',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'user',
                    ],
                ],
            ],
            'stats' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/user/stats',
                    'defaults' => [
                        'controller' => Controller\StatsController::class,
                        'action'     => 'stats',
                    ],
                ],
            ],
            'administration' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/user/administration',
                    'defaults' => [
                        'controller' => Controller\AdministrationController::class,
                        'action'     => 'administration',
                    ],
                ],
            ],
            'changerPhoto' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/user/administration/changerPhoto',
                    'defaults' => [
                        'controller' => Controller\AdministrationController::class,
                        'action'     => 'changerPhoto',
                    ],
                ],
            ],
            'changerMail' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/user/administration/changerMail',
                    'defaults' => [
                        'controller' => Controller\AdministrationController::class,
                        'action'     => 'changerMail',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AuthController::class => Controller\Factories\AuthControllerFactory::class,
            Controller\UserController::class => Controller\Factories\UserControllerFactory::class,
            Controller\StatsController::class => Controller\Factories\StatsControllerFactory::class,
            Controller\AdministrationController::class => Controller\Factories\AdministrationControllerFactory::class,

        ],
    ],

    'service_manager' => [
        'factories' => [
            Services\UtilisateurSerieTable::class => Services\Factories\UtilisateurSerieTableFactory::class,
            Services\UtilisateurSerieTableGateway::class => Services\Factories\UtilisateurSerieTableGatewayFactory::class,

            Services\BadgeTable::class => Services\Factories\BadgeTableFactory::class,
            Services\BadgeTableGateway::class => Services\Factories\BadgeTableGatewayFactory::class,

            Services\UtilisateurBadgeTable::class => Services\Factories\UtilisateurBadgeTableFactory::class,
            Services\UtilisateurBadgeTableGateway::class => Services\Factories\UtilisateurBadgeTableGatewayFactory::class,

            Services\UtilisateurEpisodeSerieTable::class => Services\Factories\UtilisateurEpisodeSerieTableFactory::class,
            Services\UtilisateurEpisodeSerieTableGateway::class => Services\Factories\UtilisateurEpisodeSerieTableGatewayFactory::class,

            // Factory permettant de gérer la base de données
            Services\UserManager::class => Services\Factories\UserManagerFactory::class,
            // Factory permettant de gérer la passerelle entre la base de données et UserManager
            Services\UserGateway::class => Services\Factories\UserGatewayFactory::class,
            // Factory permettant de gérer tous les principes d'authentification
            Services\AuthManager::class => Services\Factories\AuthManagerFactory::class,
            // Factory permettant de créer l'Adapteur implémentant l'interface d'authentification
            Services\AuthAdapter::class => Services\Factories\AuthAdapterFactory::class,
            // Service d'authentification de zend
            \Zend\Authentication\AuthenticationService::class => Services\Factories\AuthenticationServiceFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'serie' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/serie/:idSerie',
                    'defaults' => [
                        'controller'    => Controller\SerieController::class,
                        'action'        => 'serie',
                    ],
                ],
            ],
            'saison' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/serie/:idSerie/saison/:idSaison',
                  /*  'constraints' => [
                        'idSerie' => '[0-9]*',
                        'idSaison' => '[0-9]*',
                    ],*/
                    'defaults' => [
                        'controller'    => Controller\SaisonController::class,
                        'action'        => 'saison',
                    ],
                ],
            ],
            // Ajout
            'listeseries' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/listeseries/:page',
                    'constraints' => [
                        'page' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\ListeSeriesController::class,
                        'action'        => 'listeseries',
                        'page'          => '1',
                    ],
                ],
            ],
            'ajoutSerie' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/serie/:idSerie/ajout',
                    'constraints' => [
                        //'idSerie' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\SerieController::class,
                        'action'        => 'ajoutSerie',
                    ],
                ],
            ],
            'suppressionSerie' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/serie/:idSerie/suppression',
                    'constraints' => [
                        //'idSerie' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\SerieController::class,
                        'action'        => 'suppressionSerie',
                    ],
                ],
            ],
            'ajoutFavoris' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/serie/:idSerie/favoris',
                    'constraints' => [
                        //'idSerie' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\SerieController::class,
                        'action'        => 'ajoutFavoris',
                    ],
                ],
            ],
            'supprimerFavoris' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/serie/:idSerie/supprfavoris',
                    'constraints' => [
                        //'idSerie' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\SerieController::class,
                        'action'        => 'supprimerFavoris',
                    ],
                ],
            ],
            'noter' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/serie/:idSerie/note/:note',
                    'constraints' => [
                        'note' => '[1-5]',
                    ],
                    'defaults' => [
                        'controller'    => Controller\SerieController::class,
                        'action'        => 'noter',
                    ],
                ],
            ],
            'unCheck' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/serie/:idSerie/saison/:idSaison/uncheck/:idUnCheck',
                    'defaults' => [
                        'controller'    => Controller\SaisonController::class,
                        'action'        => 'unCheck',
                    ],
                ],
            ],
            'check' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/serie/:idSerie/saison/:idSaison/check/:idCheck',
                    'defaults' => [
                        'controller'    => Controller\SaisonController::class,
                        'action'        => 'check',
                    ],
                ],
            ],
        ],
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\IndexController::class => [
                ['actions' => ['index'], 'allow' => '*'],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Services\NavManager::class => Services\Factories\NavManagerFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factories\IndexControllerFactory::class,
            Controller\SerieController::class => Controller\Factories\SerieControllerFactory::class,
            Controller\SaisonController::class => Controller\Factories\SaisonControllerFactory::class,
            // Ajout
            Controller\ListeSeriesController::class => Controller\Factories\ListeSeriesControllerFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => View\Helper\Factory\MenuFactory::class,
        ],
        'aliases' => [
            'mainMenu' => View\Helper\Menu::class
        ],
    ],


    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];

<?php
namespace User\Controller\Factories;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use User\Controller\StatsController;

class StatsControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,
                             $requestedName, array $options = null)
    {   $authService = $container->get(\Zend\Authentication\AuthenticationService::class);
        $userManager = $container->get(\User\Services\UserManager::class);
        $utilisateurSerie = $container->get(\User\Services\UtilisateurSerieTable::class);
        $utilisateurBadge = $container->get(\User\Services\UtilisateurBadgeTable::class);
        $badge=$container->get(\User\Services\BadgeTable::class);
        return new StatsController($authService,$userManager,$utilisateurSerie,$utilisateurBadge,$badge);
    }
}

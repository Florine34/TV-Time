<?php
namespace User\Services\Factories;

use User\Services\UtilisateurBadgeTable;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use Interop\Container\ContainerInterface;


use User\Services\UtilisateurBadgeTableGateway;


class UtilisateurBadgeTableFactory implements FactoryInterface
{
    /**
     * This method creates the Zend\Authentication\AuthenticationService service
     * and returns its instance.
     */
    public function __invoke(ContainerInterface $container,
                             $requestedName, array $options = null)
    {
        $authService = $container->get(\Zend\Authentication\AuthenticationService::class);
        $userManager = $container->get(\User\Services\UserManager::class);
        $tableGateway = $container->get(UtilisateurBadgeTableGateway::class);
        $utilisateurSerie = $container->get(\User\Services\UtilisateurSerieTable::class);

        return new UtilisateurBadgeTable($tableGateway,$authService,$userManager,$utilisateurSerie);
    }
}

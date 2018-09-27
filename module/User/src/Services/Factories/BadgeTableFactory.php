<?php
namespace User\Services\Factories;

use User\Models\Badge;
use User\Services\BadgeTable;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;
use Zend\Authentication\Storage\Session as SessionStorage;
use Interop\Container\ContainerInterface;


use User\Services\BadgeTableGateway;


class BadgeTableFactory implements FactoryInterface
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
        $tableGateway = $container->get(BadgeTableGateway::class);

        return new BadgeTable($tableGateway,$authService,$userManager);
    }
}

<?php
namespace User;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

use User\Controller\AuthController;
use User\Services\AuthManager;


/*
* Lors de l'ajout d'un module n'oubliez pas d'ajouter le dossier src dans le composer.json
* Il faut également le rajouter dans le fichier module.config.php du dossier config global.
* Ensuite executez la commande `composer dump-autoload` pour regénérer les fichiers nécéssaires
*/
class Module implements ConfigProviderInterface
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Méthodes appelé une seule fois lors de la mise en place du module
     * Permet d'enregistrer des listeners d'évènements
     */
    public function onBootstrap(MvcEvent $event)
    {
        // Récupération du gestionnaire d'évènement
        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();

        // Enregistrement de la méthode onDispatch comme gestionnaire d'évènement
        $sharedEventManager->attach(AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
    }

    /**
     * Intercepte chacune des requêtes envoyé aux contrôleurs et vérifies
     * si il est autoriser en appelant la méthode filterAccess
     */
    public function onDispatch(MvcEvent $event)
    {
        // getTarget nous renvoie le contrôleur visé
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        // Récupération de l'action pour ce contrôleur
        $actionName = $event->getRouteMatch()->getParam('action', null);

        // Permet de convertir edit-member en editMember
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        // Récupère l'instance du gestionnaire d'authentification
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);

        // Pour chaque contrôleur on vérifie si l'accès est autorisé
        // En evitant pour AuthController, cela causerait une boucle infinie
        if ($controllerName!=AuthController::class && !$authManager->filterAccess($controllerName, $actionName)) {

            // Récupération de l'URL que l'utilisateur voulait accéder
            // Permet de le rediriger après authentification
            $uri = $event->getApplication()->getRequest()->getUri();

            // Retire toutes les données non importantes pour ne garder que le chemin relatif
            // Permet de ne pas avoir d'URL de redirection tel que http://malicious.com
            $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);
            $redirectUrl = $uri->toString();

            // Redirige vers la page de login
            return $controller->redirect()->toRoute('login', [],
                ['query'=>['redirectUrl'=>$redirectUrl]]);
        }
    }
}

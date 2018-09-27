<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

use User\Services\UserManager;

use User\Services\UtilisateurBadgeTable;
use User\Services\BadgeTable;
use User\Services\UtilisateurSerieTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class StatsController extends AbstractActionController
{
    private $authService;
    private $userManager;
    private $_utilisateurSerie;
    private $_utilisateurBadge;
    private $_badge;

    public function __construct($authService,UserManager $userManager,UtilisateurSerieTable $utilisateurSerie,UtilisateurBadgeTable $utilisateurBadge,BadgeTable $badge)
    {
        $this->authService = $authService;
        $this->userManager = $userManager;
        $this->_utilisateurSerie = $utilisateurSerie;
        $this->_utilisateurBadge = $utilisateurBadge;
        $this->_badge = $badge;
    }

    public function statsAction()
    {

        $user=$this->userManager->findByMail($this->authService->getIdentity());

        //Récupère nb de séries suivies
        $nbSeries=$this->_utilisateurSerie->countByUserConnected();

        //Récupère nb d'épisodes vus
        $nbEpisodes=$this->_utilisateurSerie->countEpisodesByUserConnected();

        //Badges utilisateur
        $utilisateurBadge=$this->_utilisateurBadge->fetchByUserConnected();

        //Liste badges
        $badges=$this->_badge->fetchAll();

        return new ViewModel([
            'user'=>$user,
            'nbSeries'=>$nbSeries,
            'nbEpisodes'=>$nbEpisodes,
            'utilisateurBadges'=>$utilisateurBadge,
            'badges'=>$badges
        ]);
    }

}


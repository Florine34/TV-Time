<?php
namespace Application\Services;

/**
 * This service is responsible for determining which items should be in the main menu.
 * The items may be different depending on whether the user is authenticated or not.
 */
class NavManager
{

    private $authService;
    private $userManager;
    private $urlHelper;

    public function __construct($authService, $urlHelper, $userManager)
    {
        $this->authService = $authService;
        $this->userManager = $userManager;

        $this->urlHelper = $urlHelper;
    }

    /**
     * This method returns menu items depending on whether user has logged in or not.
     */
    public function getMenuItems()
    {
        $url = $this->urlHelper;
        $items = [];

        // Display "Login" menu item for not authorized user only. On the other hand,
        // display "Admin" and "Logout" menu items only for authorized users.
        if (!$this->authService->hasIdentity()) {
            $items[] = [
                'id' => 'login',
                'label' => 'Connexion',
                'link'  => $url('login')
            ];
        } else {

            //Récupère id de l'utilisateur connecté
            $id=$this->userManager->findByMail($this->authService->getIdentity())->_id;

            //Récupère le pseudo de l'utilisateur connecté
            $username=$this->userManager->findByMail($this->authService->getIdentity())->_username;
           
            $items[] = [
                'id' => 'user',
                'label' => 'Accueil',
                'link'  => $url('user'),
            ];            
            $items[] = [
                'id' => 'listeserie',
                'label' => 'Découvrir des séries',
                'link'  => $url('listeseries'),
            ];
            $items[] = [
                        'id' => 'stat',
                        'label' => 'Statistiques',
                        'link'  => $url('stats')
            ];
            $items[] = [
                'id' => 'logout',
                'label' => $username,
                'dropdown' => [
                    [
                        'id' => 'profil',
                        'label' => 'Gestion du compte',
                        'link'=>$url('administration')
                    ],
                    [
                        'id' => 'logout',
                        'label' => 'Déconnexion',
                        'link' => $url('logout')
                    ],

                ]
            ];
        }

        return $items;
    }
}
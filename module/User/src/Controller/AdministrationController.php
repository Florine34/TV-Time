<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class AdministrationController extends AbstractActionController
{
    private $authService;
    private $userManager;

    public function __construct($authService,$userManager)
    {
        $this->authService = $authService;
        $this->userManager = $userManager;
    }
    public function administrationAction()
    {
        //Récupère id de l'utilisateur connecté
        $id=$this->userManager->findByMail($this->authService->getIdentity())->_id;

        //Récupère mail de l'utilisateur connecté
        $mail=$this->authService->getIdentity();

        //Récupère pseudo de l'utilisateur connecté
        $username=$this->userManager->findByMail($this->authService->getIdentity())->_username;

        return new ViewModel([
            'mail'=>$mail,
            'username'=>$username,
            'id'=>$id
        ]);
    }


    public function changerMailAction(){
        //changer le mail


        //Récupère id de l'utilisateur connecté
        $id=$this->userManager->findByMail($this->authService->getIdentity())->_id;

        $ancienMail=$this->authService->getIdentity();

        //Récupère données du formulaire
        $request= $this->getRequest()->getPost();

        //Recupere le nouveau mail depuis le formulaire
        $nouveauMail=$request['mail'];

        //Demande une maj du mail à UserManager
        $this->userManager->changerMail($ancienMail,$nouveauMail);

        //Déconnexion pour éviter certains problèmes
        return $this->redirect()->toRoute('logout');
    }



    public function changerPhotoAction(){

    }
}


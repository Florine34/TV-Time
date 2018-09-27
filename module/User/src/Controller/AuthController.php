<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\Result;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use User\Services\UserManager;
use User\Services\AuthManager;
use User\Form\LoginForm;

/**
 * Controleur d'authentification, gère le login et le logout
 */

class AuthController extends AbstractActionController
{
    // Gestion de l'utilisateur en base de données
    private $_userManager;
    // Gestionnaire d'authentification
    private $_authManager;

    public function __construct(UserManager $userManager, AuthManager $authManager)
    {
        $this->_userManager = $userManager;
        $this->_authManager = $authManager;
    }

    public function loginAction() {
        $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl)>2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }

        $form = new LoginForm();
        $form->get('redirect_url')->setValue($redirectUrl);

        $isLoginError = false;

        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();
            $form->setData($data);
            if($form->isValid()) {
                $data = $form->getData();
                $result = $this->_authManager->login($data['mail'], $data['password']);

                if ($result->getCode() == Result::SUCCESS) {
                    $redirectUrl = $this->params()->fromPost('redirect_url', '');

                    if(empty($redirectUrl)) {
                        return $this->redirect()->toRoute('home');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                } else {
                    $isLoginError = true;
                }
            } else {
                $isLoginError = true;
            }
        }

        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl
        ]);
    }

    public function logoutAction() {
        $this->_authManager->logout();

        return $this->redirect()->toRoute('login');
    }


}

?>
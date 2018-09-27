<?php
namespace User\Services;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use User\Models\User;
use User\Services\UserManager;

class AuthAdapter implements AdapterInterface
{
    public $_username;
    public $_mail;
    public $_password;
    public $_id;

    private $_userManager;
    private $_userPrivilegeTable;

    public function __construct(UserManager $userManager)
    {
        $this->_userManager = $userManager;
    }

    public function authenticate()
    {
        //Fonction de base adaptée pour une connexion par mail
        $user = $this->_userManager->findByMail($this->_mail);

        if ($user == null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Invalid credentials.']);
        }

        $sentPass = hash('sha256', $this->_password . $user->_salt);


        if ($user->_password == $sentPass) {
            return new Result(
                Result::SUCCESS,
                $this->_mail,
                ['Authenticated successfully.']);
        }

        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid password.']);
    }

}
<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Json\Json;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Json\Decoder;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Services\UserManager;
use User\Services\UtilisateurSerieTable;
use User\Models\Utilisateurserie; 


class SerieController extends AbstractActionController
{
    private $_idSerie;
    private $authService;
    private $userManager;
    private $_utilisateurSerie;

    public function __construct($authService,UserManager $userManager,UtilisateurSerieTable $utilisateurSerie)
    {
        $this->authService = $authService;
        $this->userManager = $userManager;
        $this->_utilisateurSerie = $utilisateurSerie;
    }


    public function serieAction()
    {

        //Récupère de la série depuis l'url
        $this->_idSerie=$this->params()->fromRoute('idSerie');

        //Prépare requete pour obtenir nom et date de la série 
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri('http://api.trakt.tv/shows/'.$this->_idSerie.'?extended=full');
        $request->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/json',
            'trakt-api-key' => '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6',
            'trakt-api-version' => '2',
            'Authorization' => 'Bearer [access_token]',
        ));

        //Prépare requete pour obtenir saisons
        $request2 = new Request();
        $request2->setMethod(Request::METHOD_GET);
        $request2->setUri('http://api.trakt.tv/shows/'.$this->_idSerie.'/seasons');
        $request2->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/json',
            'trakt-api-key' => '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6',
            'trakt-api-version' => '2',
            'Authorization' => 'Bearer [access_token]',
        ));

        //Envoie requete
        $client = new Client();
        $api = $client->send($request);
        $api2 = $client->send($request2); 

        //Décode le json vers php
        $serie = Json::decode($api->getBody());
        $saison = Json::decode($api2->getBody());


        // -----------Requete sur OMDB------------- //
        
        $arrayImages = array();
        // Récupère l'id de la série pour OMDB
        $idOMDB = $serie->ids->imdb;

        //Prépare requete
        $requestOMDB = new Request();
        $requestOMDB->setMethod(Request::METHOD_GET);
        $requestOMDB->setUri('http://www.omdbapi.com/?i='.$idOMDB.'&apikey=215947ec');
        $requestOMDB->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/json',
            'trakt-api-key' => '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6',
            'trakt-api-version' => '2',
            'Authorization' => 'Bearer [access_token]',
        ));

        //Envoie requete
        $clientOMDB = new Client();
        $apiOMDB = $clientOMDB->send($requestOMDB);

        //Décope le json vers php
        $resultatOMDB = Json::decode($apiOMDB->getBody());
        $idUser=$this->userManager->findByMail($this->authService->getIdentity())->_id;
        
        $infoSerie=$this->_utilisateurSerie->findByIdSerie($this->_idSerie);
        $liste=$this->_utilisateurSerie->findByIdSerieUser($idUser,$this->_idSerie); 
        


        //Renvoi de la note de l'utilisateur

        return new ViewModel([
            'idSerie'=>$this->_idSerie,
            'serie'=>$serie,
            'saison'=>$saison,
            'image' => $resultatOMDB,
            'infoSerie'=>$infoSerie,
            'liste'=>$liste,
        ]);
    }

    public function ajoutSerieAction(){
        //Récupère id de l'utilisateur connecté
        $idUser=$this->userManager->findByMail($this->authService->getIdentity())->_id;
        // Récupère l'id de la série
        $idSerie = $this->_idSerie=$this->params()->fromRoute('idSerie');
        $object = new Utilisateurserie(); 
        $object->_idUtilisateur =$idUser; 
        $object->_idSerie = $idSerie; 
        $object->_episodesRestants = NULL; 
        $object->_episodesVus= 0; 
        $object->_note=NULL;
        $object->_favoris=0;
        $ajouterListe = $this->_utilisateurSerie->insertSerie($object); 
        echo "<script type='text/javascript'>alert('Votre série à bien été ajouté à votre liste');</script>";
        return $this->redirect()->toRoute('serie', array(
            'action' =>  'serie',
            'idSerie' =>$idSerie,
        ));
    }

    public function suppressionSerieAction(){
        //Récupère id de l'utilisateur connecté
        $idUser=$this->userManager->findByMail($this->authService->getIdentity())->_id;
        // Récupère l'id de la série
        $idSerie = $this->_idSerie=$this->params()->fromRoute('idSerie');
        // Supprimer la série de ma liste
        $supprimerListe = $this->_utilisateurSerie->delete($idUser, $idSerie);  
        echo "<script type='text/javascript'>alert('Votre série à bien été supprimé de votre liste');</script>";

        return $this->redirect()->toRoute('serie', array(
            'action' =>  'serie',
            'idSerie' =>$idSerie,
        ));
    }

    public function ajoutFavorisAction(){
        $idSerie=$this->params()->fromRoute('idSerie');
        $idUser=$this->userManager->findByMail($this->authService->getIdentity())->_id;
    
        $resultSet=$this->_utilisateurSerie->findByIdSerie($idSerie);
        $resultUpdate=$this->_utilisateurSerie->findByIdSerie($idSerie);
         
        $resultUpdate->_favoris = 1 ; 
        // Transformer l'objet en array
        $resultUpdateA = (array) $resultUpdate ;
        $updateRes = $this->_utilisateurSerie->UpdateStatutSerie($resultSet, $resultUpdateA); 
        echo "<script type='text/javascript'>alert('Votre série est maintenant dans vos favoris');</script>";

        return $this->redirect()->toRoute('serie', array(
            'action' =>  'serie',
            'idSerie' =>$idSerie,
        ));
    }

    public function supprimerFavorisAction(){
        $idSerie=$this->params()->fromRoute('idSerie');
        $idUser=$this->userManager->findByMail($this->authService->getIdentity())->_id;
    
        $resultSet=$this->_utilisateurSerie->findByIdSerie($idSerie);
        $resultUpdate=$this->_utilisateurSerie->findByIdSerie($idSerie);
         
        $resultUpdate->_favoris = 0 ; 
        // Transformer l'objet en array
        $resultUpdateA = (array) $resultUpdate ;
        $updateRes = $this->_utilisateurSerie->UpdateStatutSerie($resultSet, $resultUpdateA); 
        echo "<script type='text/javascript'>alert('Votre série ne fait plus partie de vos favoris');</script>";

        return $this->redirect()->toRoute('serie', array(
            'action' =>  'serie',
            'idSerie' =>$idSerie,
        ));
    }


    public function noterAction(){
        $note=$this->params()->fromRoute('note');

        $idSerie=$this->params()->fromRoute('idSerie');

        $this->_utilisateurSerie->noterByUserConnected($note,$idSerie);

        return $this->redirect()->toRoute('serie', array(
            'action' =>  'serie',
            'idSerie' =>$idSerie,
        ));

    }
}

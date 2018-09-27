<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

use User\Services\UserManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Services\UtilisateurSerieTable;
use Zend\Http\Client;

use Zend\Json\Json;
use Zend\Http\Request;
use Zend\Json\Decoder;

class UserController extends AbstractActionController
{
    private $authService;
    private $userManager;
    private $_utilisateurSerie;

    public function __construct($authService,UserManager $userManager,UtilisateurSerieTable $utilisateurSerie)
    {
        $this->authService = $authService;
        $this->userManager = $userManager;
        $this->_utilisateurSerie = $utilisateurSerie;
    }

    public function userAction()
    {
        //Récupère id de l'utilisateur connecté
        $id=$this->userManager->findByMail($this->authService->getIdentity())->_id;
        $series=$this->_utilisateurSerie->fetchByUserConnected();
        $seriesEnCours=$this->_utilisateurSerie->fetchSeriesEnCoursByUserConnected();
        $seriesADemarrer=$this->_utilisateurSerie->fetchSeriesADemarrerByUserConnected();
        $seriesFavorites=$this->_utilisateurSerie->fetchSeriesFavoritesByUserConnected();
        


        // Pour les séries en cours 
        $resultatEnCours=array();
        for($i=0;$i<sizeof($seriesEnCours); $i++){
            $idSerie=$seriesEnCours[$i]->_idSerie;
            $request = new Request();
            $request->setMethod(Request::METHOD_GET);
            $request->setUri('http://api.trakt.tv/shows/'.$idSerie);
            $request->getHeaders()->addHeaders(array(
                'Content-Type' => 'application/json',
                'trakt-api-key' => '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6',
                'trakt-api-version' => '2',
                'Authorization' => 'Bearer [access_token]',
            ));
            //Envoie requete
            $client = new Client();
            $api = $client->send($request);
            $resultatEnCours[$i] = Json::decode($api->getBody());
        }

        // Pour les séries a démareer
        $resultatADemarrer=array();
        for($i=0;$i<sizeof($seriesADemarrer); $i++){
            $idSerie=$seriesADemarrer[$i]->_idSerie;
            $request = new Request();
            $request->setMethod(Request::METHOD_GET);
            $request->setUri('http://api.trakt.tv/shows/'.$idSerie);
            $request->getHeaders()->addHeaders(array(
                'Content-Type' => 'application/json',
                'trakt-api-key' => '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6',
                'trakt-api-version' => '2',
                'Authorization' => 'Bearer [access_token]',
            ));
            //Envoie requete
            $client = new Client();
            $api = $client->send($request);
            $resultatADemarrer[$i] = Json::decode($api->getBody());
        }

         // Pour les séries favorites
        $resultatFavoris=array();
        for($i=0;$i<sizeof($seriesFavorites); $i++){
            $idSerie=$seriesFavorites[$i]->_idSerie;
            $request = new Request();
            $request->setMethod(Request::METHOD_GET);
            $request->setUri('http://api.trakt.tv/shows/'.$idSerie);
            $request->getHeaders()->addHeaders(array(
                'Content-Type' => 'application/json',
                'trakt-api-key' => '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6',
                'trakt-api-version' => '2',
                'Authorization' => 'Bearer [access_token]',
            ));
            //Envoie requete
            $client = new Client();
            $api = $client->send($request);
            $resultatFavoris[$i] = Json::decode($api->getBody());
        }
         // -----------Requete sur OMDB------------- //
        
        // Pour les séries en cours 
        $arrayImagesEnCours = array();
        // Récupère l'id de la série pour OMDB
        $idOMDB1 = "";
       
        for($i=0; $i<sizeof($resultatEnCours);$i++) {
            // Récupère l'id de la série pour OMDB
            $idOMDB1 = $resultatEnCours[$i]->ids->imdb;

            //Prépare requete
            $requestOMDB1 = new Request();
            $requestOMDB1->setMethod(Request::METHOD_GET);
            $requestOMDB1->setUri('http://www.omdbapi.com/?i='.$idOMDB1.'&apikey=215947ec');
            $requestOMDB1->getHeaders()->addHeaders(array(
                'Content-Type' => 'application/json',
                'trakt-api-key' => '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6',
                'trakt-api-version' => '2',
                'Authorization' => 'Bearer [access_token]',
            ));

            //Envoie requete
            $clientOMDB1 = new Client();
            $apiOMDB1 = $clientOMDB1->send($requestOMDB1);

            //Décope le json vers php
            $resultatOMDB1 = Json::decode($apiOMDB1->getBody());
            $arrayImagesEnCours[$i] = $resultatOMDB1;
        }

        // Pour les séries à démarrer
        $arrayImagesADemarrer = array();
        // Récupère l'id de la série pour OMDB
        $idOMDB2 = "";
        for($i=0; $i<sizeof($resultatADemarrer);$i++) {
            // Récupère l'id de la série pour OMDB
            $idOMDB2 = $resultatADemarrer[$i]->ids->imdb;

            //Prépare requete
            $requestOMDB2 = new Request();
            $requestOMDB2->setMethod(Request::METHOD_GET);
            $requestOMDB2->setUri('http://www.omdbapi.com/?i='.$idOMDB2.'&apikey=215947ec');
            $requestOMDB2->getHeaders()->addHeaders(array(
                'Content-Type' => 'application/json',
                'trakt-api-key' => '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6',
                'trakt-api-version' => '2',
                'Authorization' => 'Bearer [access_token]',
            ));

            //Envoie requete
            $clientOMDB2 = new Client();
            $apiOMDB2 = $clientOMDB2->send($requestOMDB2);

            //Décope le json vers php
            $resultatOMDB2 = Json::decode($apiOMDB2->getBody());
            $arrayImagesADemarrer[$i] = $resultatOMDB2;
        }
        // Pour les séries favorites
        $arrayImagesFavorites = array();
        // Récupère l'id de la série pour OMDB
        $idOMDB3 = "";
        for($i=0; $i<sizeof($resultatFavoris);$i++) {
            // Récupère l'id de la série pour OMDB
            $idOMDB3 = $resultatFavoris[$i]->ids->imdb;

            //Prépare requete
            $requestOMDB3 = new Request();
            $requestOMDB3->setMethod(Request::METHOD_GET);
            $requestOMDB3->setUri('http://www.omdbapi.com/?i='.$idOMDB3.'&apikey=215947ec');
            $requestOMDB3->getHeaders()->addHeaders(array(
                'Content-Type' => 'application/json',
                'trakt-api-key' => '7f64fc2ceef5b70439a9736df3b9b9310eddd6c57ecb55743d178bc1300a40c6',
                'trakt-api-version' => '2',
                'Authorization' => 'Bearer [access_token]',
            ));

            //Envoie requete
            $clientOMDB3 = new Client();
            $apiOMDB3 = $clientOMDB3->send($requestOMDB3);

            //Décope le json vers php
            $resultatOMDB3 = Json::decode($apiOMDB3->getBody());
            $arrayImagesFavorites[$i] = $resultatOMDB3;
        }

        //Récupère pseudo de l'utilisateur connecté
        $username=$this->userManager->findByMail($this->authService->getIdentity())->_username;

        $user=$this->userManager->findByMail($this->authService->getIdentity());



        //Récupère séries en cours
        return new ViewModel([
            'user'=>$user,
            'id'=>$id,
            'series'=>$series,
            'seriesEnCours'=>$seriesEnCours,
            'seriesADemarrer'=>$seriesADemarrer,
            'seriesFavorites'=>$seriesFavorites,
            'imageEnCours'=>$arrayImagesEnCours,
            'imageADemarrer'=>$arrayImagesADemarrer,
            'imageFavorites'=>$arrayImagesFavorites,
        ]);
    }

}

